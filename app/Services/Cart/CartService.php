<?php

namespace App\Services\Cart;

use App\Models\Authorization\User;
use App\Models\Cart\Cart;
use App\Models\Cart\CartItem;
use App\Models\Product\ProductVariant;
use App\Services\Order\OrderCalculationService;
use App\Services\Pricing\PriceService;
use App\Services\Utility\OrderItemCalculator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CartService
{
    public function __construct(
        protected PriceService $priceService,
        protected OrderItemCalculator $itemCalculator,
        protected OrderCalculationService $calculationService,
    ) {
    }

    // This returns the current shopper cart as a JSON-ready array.
    public function showCurrentCart(Request $request): array
    {
        $user = $request->user();
        $guestSessionId = $user ? null : $this->resolveGuestCartSessionId($request);

        $cart = $this->findCart($user, $guestSessionId);

        return $this->buildCartResponse($cart, $user, $guestSessionId);
    }

    // This adds one item into the current shopper cart.
    public function addItemToCurrentCart(array $validatedItem, Request $request): array
    {
        $user = $request->user();
        $guestSessionId = $user ? null : $this->resolveGuestCartSessionId($request);

        $cart = $this->findOrCreateCart($user, $guestSessionId);
        $variantId = $this->resolveCartVariantId($validatedItem, $user);

        $cartItem = $cart->items()
            ->where('product_variant_id', $variantId)
            ->first();

        $requestedQuantity = (int) $validatedItem['quantity'];
        $finalQuantity = $requestedQuantity;

        $priceData = $this->priceService->resolveVariantPrice($variantId, $user, $finalQuantity);

        if (! $priceData) {
            $message = $user
                ? 'The selected product variant is not available for this user.'
                : 'The selected product variant is not available right now.';

            throw ValidationException::withMessages([
                'product_variant_id' => $message,
            ]);
        }

        if (! $this->calculationService->isValidQuantity($finalQuantity, $priceData)) {
            throw ValidationException::withMessages([
                'quantity' => $this->calculationService->getQuantityErrorMessage($finalQuantity, $priceData),
            ]);
        }

        if ($cartItem) {
            $cartItem->update(['quantity' => $finalQuantity]);
        } else {
            $cart->items()->create([
                'product_variant_id' => $variantId,
                'quantity' => $finalQuantity,
            ]);
        }

        $cart->update([
            'currency' => $priceData['currency'] ?? 'INR',
        ]);

        $refreshedCart = $this->findCart($user, $guestSessionId);

        return $this->buildCartResponse($refreshedCart, $user, $guestSessionId);
    }

    // This updates one item quantity in the current shopper cart.
    public function updateCurrentCartItemQuantity(int $cartItemId, array $validatedItem, Request $request): array
    {
        $user = $request->user();
        $guestSessionId = $user ? null : $this->resolveGuestCartSessionId($request);

        $cartItem = $this->findCartItem($cartItemId, $user, $guestSessionId);
        $updatedQuantity = (int) $validatedItem['quantity'];

        $priceData = $this->priceService->resolveVariantPrice(
            (int) $cartItem->product_variant_id,
            $user,
            $updatedQuantity
        );

        if (! $priceData) {
            throw ValidationException::withMessages([
                'cart_item_id' => 'The selected cart item is no longer available for checkout.',
            ]);
        }

        if (! $this->calculationService->isValidQuantity($updatedQuantity, $priceData)) {
            throw ValidationException::withMessages([
                'quantity' => $this->calculationService->getQuantityErrorMessage($updatedQuantity, $priceData),
            ]);
        }

        $cartItem->update(['quantity' => $updatedQuantity]);

        $cartItem->cart->update([
            'currency' => $priceData['currency'] ?? 'INR',
        ]);

        $refreshedCart = $this->findCart($user, $guestSessionId);

        return $this->buildCartResponse($refreshedCart, $user, $guestSessionId);
    }

    // This removes one item from the current shopper cart.
    public function removeItemFromCurrentCart(int $cartItemId, Request $request): array
    {
        $user = $request->user();
        $guestSessionId = $user ? null : $this->resolveGuestCartSessionId($request);

        $cartItem = $this->findCartItem($cartItemId, $user, $guestSessionId);
        $cart = $cartItem->cart;

        $cartItem->delete();

        if (! $cart->items()->exists()) {
            $cart->delete();

            return $this->buildCartResponse(null, $user, $guestSessionId);
        }

        $refreshedCart = $this->findCart($user, $guestSessionId);

        return $this->buildCartResponse($refreshedCart, $user, $guestSessionId);
    }

    // This moves guest cart items into the signed-in account cart.
    public function moveGuestCartItemsToUserCart(Request $request, User $user): array
    {
        $guestSessionId = $this->readGuestCartSessionId($request);

        if (! $guestSessionId) {
            return $this->emptyMoveSummary();
        }

        $guestCart = $this->findGuestCartBySessionId($guestSessionId);

        if (! $guestCart || $guestCart->items->isEmpty()) {
            $this->clearGuestCartSessionId($request);
            return $this->emptyMoveSummary();
        }

        $moveSummary = $this->mergeGuestItemsIntoUserCart($guestCart, $user);
        $this->deleteGuestCart($guestCart);
        $this->clearGuestCartSessionId($request);

        return $moveSummary;
    }

    // Merge each guest cart item into the user cart.
    private function mergeGuestItemsIntoUserCart($guestCart, User $user): array
    {
        $moveSummary = [
            'moved_items_count' => 0,
            'skipped_items_count' => 0,
        ];

        $userCart = $this->findUserCart($user);

        DB::transaction(function () use (&$moveSummary, $guestCart, &$userCart, $user): void {
            foreach ($guestCart->items as $guestItem) {
                $wasMoved = $this->moveGuestItemToUserCart($guestItem, $userCart, $user);

                if ($wasMoved) {
                    $moveSummary['moved_items_count']++;
                    $userCart = $this->findUserCart($user);
                } else {
                    $moveSummary['skipped_items_count']++;
                }
            }
        });

        return $moveSummary;
    }

    // Move one guest cart item to user cart.
    private function moveGuestItemToUserCart($guestItem, &$userCart, User $user): bool
    {
        $variantId = (int) $guestItem->product_variant_id;
        $guestQuantity = (int) $guestItem->quantity;

        $priceData = $this->priceService->resolveVariantPrice($variantId, $user, $guestQuantity);

        if (! $priceData) {
            return false;
        }

        if (! $this->calculationService->isValidQuantity($guestQuantity, $priceData)) {
            return false;
        }

        if (! $userCart) {
            $userCart = $this->createUserCart($user, $priceData);
        }

        $existingItem = $userCart->items()
            ->where('product_variant_id', $variantId)
            ->first();

        $finalQuantity = $guestQuantity;

        if ($existingItem) {
            $finalQuantity = (int) $existingItem->quantity + $guestQuantity;

            if (! $this->calculationService->isValidQuantity($finalQuantity, $priceData)) {
                return false;
            }

            $existingItem->update(['quantity' => $finalQuantity]);
        } else {
            $userCart->items()->create([
                'product_variant_id' => $variantId,
                'quantity' => $finalQuantity,
            ]);
        }

        $userCart->update([
            'currency' => $priceData['currency'] ?? 'INR',
        ]);

        return true;
    }

    // Check if quantity complies with pricing rules.
    private function createUserCart(User $user, array $priceData): Cart
    {
        return Cart::query()->create([
            'user_id' => $user->id,
            'currency' => $priceData['currency'] ?? 'INR',
        ]);
    }

    // Delete guest cart after migration.
    private function deleteGuestCart($guestCart): void
    {
        $guestCart->items()->delete();
        $guestCart->delete();
    }

    // Return empty move summary.
    private function emptyMoveSummary(): array
    {
        return [
            'moved_items_count' => 0,
            'skipped_items_count' => 0,
        ];
    }

    // This loads one cart for the current shopper identity.
    protected function findCart(?User $user, ?string $guestCartSessionId): ?Cart
    {
        // Step 1: start the shared cart query with the relations used by the storefront.
        $cartQuery = Cart::query()->with([
            'items' => fn ($builder) => $builder->orderBy('id'),
            'items.variant.product.primaryImage',
        ]);

        // Step 2: limit the cart query to the signed-in shopper or the guest session.
        if ($user) {
            $cartQuery->where('user_id', $user->id);
        } else {
            $cartQuery->where('session_id', $guestCartSessionId);
        }

        // Step 3: return the first matching cart.
        return $cartQuery->first();
    }

    // This finds or creates one cart row for the current shopper identity.
    protected function findOrCreateCart(?User $user, ?string $guestCartSessionId): Cart
    {
        // Step 1: create the user cart only when one does not already exist.
        if ($user) {
            return Cart::query()->firstOrCreate(
                ['user_id' => $user->id],
                ['currency' => 'INR'],
            );
        }

        // Step 2: create the guest cart only when one does not already exist.
        return Cart::query()->firstOrCreate(
            ['session_id' => $guestCartSessionId],
            ['currency' => 'INR'],
        );
    }

    // This loads one cart item and confirms it belongs to the current shopper.
    protected function findCartItem(int $cartItemId, ?User $user, ?string $guestCartSessionId): CartItem
    {
        // Step 1: start the shared cart item query with the relations used by the cart UI.
        $cartItemQuery = CartItem::query()
            ->with(['cart', 'variant.product'])
            ->whereKey($cartItemId);

        // Step 2: limit the cart item query to the signed-in shopper or the guest session.
        if ($user) {
            $cartItemQuery->whereHas('cart', function ($builder) use ($user): void {
                $builder->where('user_id', $user->id);
            });
        } else {
            $cartItemQuery->whereHas('cart', function ($builder) use ($guestCartSessionId): void {
                $builder->where('session_id', $guestCartSessionId);
            });
        }

        // Step 3: load the owned cart item.
        $cartItem = $cartItemQuery->first();

        if (! $cartItem) {
            throw (new ModelNotFoundException())->setModel(CartItem::class, [$cartItemId]);
        }

        // Step 4: return the owned cart item row.
        return $cartItem;
    }

    // This resolves which product variant should be stored in the current cart.
    protected function resolveCartVariantId(array $validatedCartItem, ?User $user): int
    {
        // Step 1: use the provided product variant when the client sends one.
        if (! empty($validatedCartItem['product_variant_id'])) {
            $selectedVariant = ProductVariant::query()
                ->whereKey((int) $validatedCartItem['product_variant_id'])
                ->where('product_id', (int) $validatedCartItem['product_id'])
                ->where('is_active', true)
                ->first();

            if (! $selectedVariant) {
                throw ValidationException::withMessages([
                    'product_variant_id' => 'The selected product variant does not belong to the selected product.',
                ]);
            }

            return (int) $selectedVariant->id;
        }

        // Step 2: fall back to the first visible priced variant for the selected product.
        $resolvedProductPrice = $this->priceService->resolveProductPrice((int) $validatedCartItem['product_id'], $user);

        if (! $resolvedProductPrice) {
            $productMessage = 'The selected product is not available right now.';

            if ($user) {
                $productMessage = 'The selected product is not available for this user.';
            }

            throw ValidationException::withMessages([
                'product_id' => $productMessage,
            ]);
        }

        // Step 3: return the resolved default variant id.
        return (int) ($resolvedProductPrice['product_variant_id'] ?? 0);
    }

    // This reads the saved guest cart session id when one already exists.
    protected function buildCartResponse(?Cart $cart, ?User $user, ?string $guestCartSessionId): array
    {
        // Step 1: return a clean empty cart structure when no cart exists.
        if (! $cart) {
            $emptyCart = [
                'id' => null,
                'user_id' => null,
                'currency' => 'INR',
                'items_count' => 0,
                'subtotal_amount' => 0,
                'tax_amount' => 0,
                'total_amount' => 0,
                'items' => [],
            ];

            if ($user) {
                $emptyCart['user_id'] = $user->id;
                return $emptyCart;
            }

            $emptyCart['session_id'] = $guestCartSessionId;

            return $emptyCart;
        }

        // Step 2: build the current cart item payloads from all saved item rows.
        $cartItems = [];

        foreach ($cart->items as $cartItem) {
            $cartItems[] = $this->buildCartItemResponse($cartItem, $user);
        }

        // Step 3: calculate current totals from the built cart items.
        $cartTotals = $this->calculateCartTotals($cartItems);

        // Step 4: prepare the final cart response array.
        $cartResponse = [
            'id' => $cart->id,
            'user_id' => $cart->user_id,
            'currency' => $cartTotals['currency'],
            'items_count' => count($cartItems),
            'subtotal_amount' => $cartTotals['subtotal_amount'],
            'tax_amount' => $cartTotals['tax_amount'],
            'total_amount' => $cartTotals['total_amount'],
            'items' => $cartItems,
        ];

        // Step 5: keep the guest session id only on guest cart responses.
        if (! $user) {
            $cartResponse['session_id'] = $cart->session_id ?: $guestCartSessionId;
        }

        // Step 6: return the final cart response.
        return $cartResponse;
    }

    // This builds one cart item payload with live price values.
    protected function buildCartItemResponse(CartItem $cartItem, ?User $user): array
    {
        // Step 1: load the product, variant, and primary image used across the cart UI.
        $productVariant = $cartItem->variant;
        $product = $productVariant?->product;
        $imagePath = $product?->primaryImage?->file_path;

        // Step 2: resolve the live current price for the selected variant.
        $resolvedVariantPrice = $this->priceService->resolveVariantPrice((int) $cartItem->product_variant_id, $user, (int) $cartItem->quantity);

        if (! $resolvedVariantPrice) {
            throw ValidationException::withMessages([
                'cart_item_id' => 'One cart item is no longer available with current pricing.',
            ]);
        }

        // Step 3: validate quantity constraints using centralized validator.
        if (! $this->calculationService->isValidQuantity((int) $cartItem->quantity, $resolvedVariantPrice)) {
            throw ValidationException::withMessages([
                'quantity' => $this->calculationService->getQuantityErrorMessage((int) $cartItem->quantity, $resolvedVariantPrice),
            ]);
        }

        // Step 4: calculate all pricing fields using centralized calculator.
        $pricing = $this->itemCalculator->calculateItemPricing($resolvedVariantPrice, (int) $cartItem->quantity);

        // Step 5: return a cart item payload that the storefront can render directly.
        return [
            'id' => encrypt_url_value($cartItem->id),
            'product_id' => $product?->id,
            'product_variant_id' => $cartItem->product_variant_id,
            'product_name' => $product?->name,
            'variant_name' => $productVariant?->variant_name,
            'sku' => $productVariant?->sku,
            'image_url' => filled($imagePath) ? asset($imagePath) : null,
            'quantity' => (int) $cartItem->quantity,
            'currency' => $pricing['currency'],
            'price_type' => $pricing['price_type'],
            'unit_price' => $pricing['unit_price'],
            'base_unit_price' => $pricing['base_unit_price'],
            'gst_rate' => $pricing['gst_rate'],
            'tax_amount' => $pricing['tax_amount'],
            'unit_price_after_gst' => $pricing['unit_price_after_gst'],
            'line_subtotal' => $pricing['subtotal_amount'],
            'line_total' => $pricing['total_amount'],
            'discount_amount' => $pricing['discount_amount'],
            'min_order_quantity' => $pricing['min_order_quantity'],
            'max_order_quantity' => $pricing['max_order_quantity'],
            'lot_size' => $pricing['lot_size'],
        ];
    }

    // This calculates cart totals from all current cart item payloads.
    protected function calculateCartTotals(array $cartItems): array
    {
        // Step 1: use INR when the cart currently has no items.
        if ($cartItems === []) {
            return [
                'currency' => 'INR',
                'subtotal_amount' => 0,
                'tax_amount' => 0,
                'total_amount' => 0,
            ];
        }

        // Step 2: sum all line values from the built cart item payloads.
        $currency = (string) ($cartItems[0]['currency'] ?? 'INR');
        $subtotalAmount = 0;
        $taxAmount = 0;
        $totalAmount = 0;

        foreach ($cartItems as $cartItem) {
            $subtotalAmount += (float) ($cartItem['line_subtotal'] ?? 0);
            $taxAmount += (float) ($cartItem['tax_amount'] ?? 0);
            $totalAmount += (float) ($cartItem['line_total'] ?? 0);
        }

        // Step 3: return the calculated cart totals.
        return [
            'currency' => $currency,
            'subtotal_amount' => round($subtotalAmount, 4),
            'tax_amount' => round($taxAmount, 4),
            'total_amount' => round($totalAmount, 4),
        ];
    }

    // This loads the current user's cart with relations.
    public function findUserCart(User $user): ?Cart
    {
        // Step 1: load the signed-in shopper cart from the shared cart flow.
        return $this->findCart($user, null);
    }

    // This reads the saved guest cart session id when one already exists.
    protected function readGuestCartSessionId(Request $request): ?string
    {
        $guestCartSessionId = (string) $request->session()->get('guest_cart_session_id', '');

        if ($guestCartSessionId === '') {
            return null;
        }

        return $guestCartSessionId;
    }

    // This resolves one stable guest cart session id for the current browser session.
    protected function resolveGuestCartSessionId(Request $request): string
    {
        $guestCartSessionId = $this->readGuestCartSessionId($request);

        if ($guestCartSessionId) {
            return $guestCartSessionId;
        }

        $guestCartSessionId = (string) $request->session()->getId();
        $request->session()->put('guest_cart_session_id', $guestCartSessionId);

        return $guestCartSessionId;
    }

    // This clears the saved guest cart session id after the move is complete.
    protected function clearGuestCartSessionId(Request $request): void
    {
        $request->session()->forget('guest_cart_session_id');
    }

    // This loads one guest cart by the saved session id.
    protected function findGuestCartBySessionId(string $guestCartSessionId): ?Cart
    {
        // Step 1: load the guest shopper cart from the shared cart flow.
        return $this->findCart(null, $guestCartSessionId);
    }
}
