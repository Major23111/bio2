<?php

namespace App\Services\Checkout;

use App\Models\Authorization\User;
use App\Models\Cart\Cart;
use App\Models\Order\Order;
use App\Services\Cart\CartService;
use App\Services\Coupon\CouponService;
use App\Services\Inventory\InventoryManagementService;
use App\Services\Notification\EmailNotificationService;
use App\Services\Order\OrderAddressService;
use App\Services\Order\OrderCalculationService;
use App\Services\Pricing\PriceService;
use App\Services\Utility\OrderItemCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;

class CheckoutService
{
    public function __construct(
        protected CartService $cartService,
        protected PriceService $priceService,
        protected CouponService $couponService,
        protected EmailNotificationService $emailNotificationService,
        protected OrderItemCalculator $itemCalculator,
        protected OrderCalculationService $calculationService,
        protected OrderAddressService $addressService,
        protected InventoryManagementService $inventoryService,
    ) {
    }

    // This loads all data needed by the existing checkout page.
    public function loadCheckoutPageData(Request $request): array
    {
        $cart = $this->cartService->showCurrentCart($request);
        $addresses = $this->loadSavedUserAddressesForCheckout($request->user());
        $businessDetails = $this->loadUserBusinessInvoiceDetailsForCheckout($request->user());

        return [
            'initialCart' => $cart,
            'savedAddresses' => $addresses,
            'checkoutBusinessDetails' => $businessDetails,
        ];
    }

    // This creates one submitted order from the current signed-in cart.
    public function placeOrderFromCart(array $validatedCheckout, User $user): array
    {
        $cart = $this->cartService->findUserCart($user);

        if (! $cart || $cart->items->isEmpty()) {
            throw ValidationException::withMessages([
                'cart' => 'Your cart is empty.',
            ]);
        }

        $couponCode = $this->couponService->readValidatedCouponCode($validatedCheckout['coupon_code'] ?? null);
        $orderItems = $this->prepareCheckoutOrderItems($cart, $user, $couponCode);

        $this->couponService->ensureCouponAppliesToPreparedItems(
            $couponCode,
            $orderItems,
            'The selected coupon does not apply to the current cart.',
        );

        $orderTotals = $this->calculateCheckoutTotals($validatedCheckout, $orderItems);

        DB::beginTransaction();

        $order = $this->createOrderFromItems($user, $validatedCheckout, $orderItems, $orderTotals);
        $createdItems = $this->createOrderItemRows($order, $orderItems);

        $this->storeCheckoutDiscountLines($order, $createdItems, $orderItems);
        $this->storeCheckoutOrderAddresses($order, $user, $validatedCheckout);

        // Deduct stock for each successful order item
        foreach ($orderItems as $orderItem) {
            $this->inventoryService->deductStock(
                (int) $orderItem['product_id'],
                $orderItem['product_variant_id'] ? (int) $orderItem['product_variant_id'] : null,
                (int) $orderItem['quantity']
            );
        }

        $cart->items()->delete();
        $cart->delete();

        DB::commit();

        $this->sendOrderSubmittedEmail($user, $order);

        return $this->buildOrderResponse($order, $createdItems);
    }

    // Create order record from checkout data.
    private function createOrderFromItems(User $user, array $checkoutData, array $orderItems, array $totals): Order
    {
        return Order::query()->create([
            'placed_by_user_id' => $user->id,
            'company_id' => $user->company_id,
            'status' => 'submitted',
            'currency' => $totals['currency'],
            'subtotal_amount' => $totals['subtotal_amount'],
            'tax_amount' => $totals['tax_amount'],
            'discount_amount' => $totals['discount_amount'],
            'shipping_amount' => $totals['shipping_amount'],
            'adjustment_amount' => $totals['adjustment_amount'],
            'rounding_amount' => $totals['rounding_amount'],
            'total_amount' => $totals['total_amount'],
            'pricing_snapshot' => $totals['pricing_snapshot'],
            'notes' => $checkoutData['notes'] ?? null,
            'submitted_at' => now(),
            'cancelled_at' => null,
        ]);
    }

    // Create all order item rows from prepared items.
    private function createOrderItemRows(Order $order, array $preparedItems): array
    {
        $createdItems = [];

        foreach ($preparedItems as $item) {
            $createdItems[] = $order->items()->create($item);
        }

        return $createdItems;
    }

    // Build order response for checkout completion.
    private function buildOrderResponse(Order $order, array $orderItems): array
    {
        $itemData = [];

        foreach ($orderItems as $item) {
            $itemData[] = [
                'id' => $item->id,
                'product_name' => $item->product_name,
                'variant_name' => $item->variant_name,
                'sku' => $item->sku,
                'quantity' => (int) $item->quantity,
                'unit_price' => (float) $item->unit_price,
                'tax_amount' => (float) $item->tax_amount,
                'total_amount' => (float) $item->total_amount,
            ];
        }

        return [
            'order' => [
                'id' => $order->id,
                'status' => $order->status,
                'currency' => $order->currency,
                'subtotal_amount' => (float) $order->subtotal_amount,
                'tax_amount' => (float) $order->tax_amount,
                'discount_amount' => (float) $order->discount_amount,
                'shipping_amount' => (float) $order->shipping_amount,
                'adjustment_amount' => (float) $order->adjustment_amount,
                'rounding_amount' => (float) $order->rounding_amount,
                'total_amount' => (float) $order->total_amount,
                'notes' => $order->notes,
                'items_count' => count($itemData),
                'items' => $itemData,
            ],
        ];
    }

    // This prepares the live coupon preview used by the checkout AJAX call.
    public function previewCheckoutCoupon(array $validatedCouponInput, User $user): array
    {
        $cart = $this->cartService->findUserCart($user);

        if (! $cart || $cart->items->isEmpty()) {
            throw ValidationException::withMessages([
                'coupon_code' => 'Add at least one cart item before applying a coupon.',
            ]);
        }

        $couponCode = $this->couponService->readValidatedCouponCode($validatedCouponInput['coupon_code'] ?? null);
        $orderItems = $this->prepareCheckoutOrderItems($cart, $user, $couponCode);

        $this->couponService->ensureCouponAppliesToPreparedItems(
            $couponCode,
            $orderItems,
            'The selected coupon does not apply to the current cart.',
        );

        return $this->couponService->buildCouponPreview(
            $couponCode,
            $orderItems,
            'Coupon applied successfully.',
            'The selected coupon does not apply to the current cart.',
        );
    }

    // This loads saved user addresses for the checkout address selector.
    protected function loadSavedUserAddressesForCheckout(?User $user)
    {
        // Step 1: keep the address list empty for guests.
        if (! $user) {
            return collect();
        }

        // Step 2: load default shipping first so checkout opens with the most useful address.
        return $user->addresses()
            ->orderByDesc('is_default_shipping')
            ->orderByDesc('is_default_billing')
            ->orderByDesc('id')
            ->get();
    }

    // This prepares GST and business invoice details for the checkout page.
    protected function loadUserBusinessInvoiceDetailsForCheckout(?User $user): array
    {
        // Step 1: keep the invoice details hidden for guests and B2C users.
        if (! $user || ! $user->isB2b()) {
            return [
                'show_business_fields' => false,
                'gstin' => null,
                'pan_number' => null,
                'registered_business_name' => null,
            ];
        }

        // Step 2: load the linked company for saved B2B details.
        $company = $user->company;

        // Step 3: return the saved B2B values for checkout.
        return [
            'show_business_fields' => true,
            'gstin' => $company?->gst_number,
            'pan_number' => $company?->pan_number,
            'registered_business_name' => $company?->legal_name ?: $company?->name,
        ];
    }

    // This prepares final order item rows from the current cart during checkout.
    protected function prepareCheckoutOrderItems(Cart $cart, User $user, ?string $couponCode = null): array
    {
        // Step 1: prepare an array that will hold all final order item rows.
        $preparedOrderItems = [];

        // Step 2: convert each cart item into one final order item payload.
        foreach ($cart->items as $index => $cartItem) {
            $productVariant = $cartItem->variant;
            $product = $productVariant?->product;

            if (! $productVariant || ! $product) {
                throw ValidationException::withMessages([
                    'cart' => 'One cart item is no longer available.',
                ]);
            }

            $quantity = (int) $cartItem->quantity;
            $resolvedVariantPrice = $this->priceService->resolveVariantPrice(
                (int) $cartItem->product_variant_id,
                $user,
                $quantity,
                $couponCode
            );

            if (! $resolvedVariantPrice) {
                throw ValidationException::withMessages([
                    'cart' => 'One cart item is no longer available for checkout.',
                ]);
            }

            // Step 3: validate quantity constraints before creating the order item.
            if (! $this->calculationService->isValidQuantity($quantity, $resolvedVariantPrice)) {
                throw ValidationException::withMessages([
                    'quantity' => $this->calculationService->getQuantityErrorMessage($quantity, $resolvedVariantPrice),
                ]);
            }

            // Step 4: check if sufficient stock is available for this item
            $hasStock = $this->inventoryService->checkAvailability(
                (int) $product->id,
                (int) $cartItem->product_variant_id,
                $quantity
            );

            if (! $hasStock) {
                throw ValidationException::withMessages([
                    'stock' => "{$product->name} - {$productVariant->variant_name} is out of stock.",
                ]);
            }

            // Step 5: calculate all pricing fields using centralized calculator.
            $pricing = $this->itemCalculator->calculateItemPricing($resolvedVariantPrice, $quantity);

            // Step 6: add the prepared order item row.
            $preparedOrderItems[] = [
                'product_id' => $product->id,
                'product_variant_id' => $productVariant->id,
                'sku' => $productVariant->sku,
                'product_name' => $product->name,
                'variant_name' => $productVariant->variant_name,
                'description' => 'Created from cart checkout.',
                'quantity' => $quantity,
                'unit_price' => $pricing['unit_price'],
                'subtotal_amount' => $pricing['subtotal_amount'],
                'discount_amount' => $pricing['discount_amount'],
                'tax_amount' => $pricing['tax_amount'],
                'total_amount' => $pricing['total_amount'],
                'sort_order' => $index,
                'item_snapshot' => $this->itemCalculator->buildMinimalSnapshot($resolvedVariantPrice),
            ];
        }

        // Step 7: return all final prepared order item rows.
        return $preparedOrderItems;
    }

    // This calculates final order totals during cart checkout.
    protected function calculateCheckoutTotals(array $validatedCheckout, array $preparedOrderItems): array
    {
        // Step 1: read the optional extra amounts passed during checkout.
        $shippingAmount = round((float) ($validatedCheckout['shipping_amount'] ?? 0), 4);
        $adjustmentAmount = round((float) ($validatedCheckout['adjustment_amount'] ?? 0), 4);
        $roundingAmount = round((float) ($validatedCheckout['rounding_amount'] ?? 0), 4);
        $currency = (string) ($preparedOrderItems[0]['item_snapshot']['currency'] ?? 'INR');

        // Step 2: sum all order item amounts into final order totals.
        $subtotalAmount = 0;
        $taxAmount = 0;
        $discountAmount = 0;
        $itemsTotal = 0;
        $priceTypes = [];
        $pricingStages = [];

        foreach ($preparedOrderItems as $preparedOrderItem) {
            $itemSnapshot = $preparedOrderItem['item_snapshot'] ?? [];

            $subtotalAmount += (float) ($preparedOrderItem['subtotal_amount'] ?? 0);
            $taxAmount += (float) ($preparedOrderItem['tax_amount'] ?? 0);
            $discountAmount += (float) ($preparedOrderItem['discount_amount'] ?? 0);
            $itemsTotal += (float) ($preparedOrderItem['total_amount'] ?? 0);

            $priceType = $itemSnapshot['price_type'] ?? null;
            $pricingStage = $itemSnapshot['pricing_stage'] ?? null;

            if ($priceType && ! in_array($priceType, $priceTypes, true)) {
                $priceTypes[] = $priceType;
            }

            if ($pricingStage && ! in_array($pricingStage, $pricingStages, true)) {
                $pricingStages[] = $pricingStage;
            }
        }

        $subtotalAmount = round($subtotalAmount, 4);
        $taxAmount = round($taxAmount, 4);
        $discountAmount = round($discountAmount, 4);
        $itemsTotal = round($itemsTotal, 4);
        $totalAmount = round($itemsTotal + $shippingAmount + $adjustmentAmount + $roundingAmount, 4);

        // Step 3: keep a readable pricing snapshot on the order header.
        $pricingSnapshot = [
            'source' => 'cart_checkout',
            'currency' => $currency,
            'items_count' => count($preparedOrderItems),
            'coupon_code' => $validatedCheckout['coupon_code'] ?? null,
            'subtotal_amount' => $subtotalAmount,
            'tax_amount' => $taxAmount,
            'discount_amount' => $discountAmount,
            'items_total' => $itemsTotal,
            'shipping_amount' => $shippingAmount,
            'adjustment_amount' => $adjustmentAmount,
            'rounding_amount' => $roundingAmount,
            'total_amount' => $totalAmount,
            'price_types' => $priceTypes,
            'pricing_stages' => $pricingStages,
        ];

        // Step 4: return the final order totals array.
        return [
            'currency' => $currency,
            'subtotal_amount' => $subtotalAmount,
            'tax_amount' => $taxAmount,
            'discount_amount' => $discountAmount,
            'shipping_amount' => $shippingAmount,
            'adjustment_amount' => $adjustmentAmount,
            'rounding_amount' => $roundingAmount,
            'total_amount' => $totalAmount,
            'pricing_snapshot' => $pricingSnapshot,
        ];
    }

    // This stores the item-level discount reasons created during checkout.
    protected function storeCheckoutDiscountLines(Order $order, array $createdOrderItems, array $preparedOrderItems): void
    {
        // Step 1: stop early when there are no created items to map against.
        if ($createdOrderItems === []) {
            return;
        }

        $discountRows = [];

        // Step 2: build one readable discount row per applied pricing rule on each order item.
        foreach ($createdOrderItems as $index => $createdOrderItem) {
            $itemSnapshot = $preparedOrderItems[$index]['item_snapshot'] ?? [];

            if (($itemSnapshot['product_discount_amount'] ?? 0) > 0) {
                $discountRows[] = [
                    'order_id' => $order->id,
                    'order_item_id' => $createdOrderItem->id,
                    'discount_type' => 'product_discount',
                    'discount_code' => null,
                    'discount_name' => 'Product Discount',
                    'discount_rate' => null,
                    'discount_amount' => round((float) $itemSnapshot['product_discount_amount'] * (int) $createdOrderItem->quantity, 4),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            if (($itemSnapshot['bulk_discount_amount'] ?? 0) > 0) {
                $discountRows[] = [
                    'order_id' => $order->id,
                    'order_item_id' => $createdOrderItem->id,
                    'discount_type' => 'bulk',
                    'discount_code' => null,
                    'discount_name' => 'Bulk Price Benefit',
                    'discount_rate' => null,
                    'discount_amount' => round((float) $itemSnapshot['bulk_discount_amount'] * (int) $createdOrderItem->quantity, 4),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            if (($itemSnapshot['coupon_discount_amount'] ?? 0) > 0) {
                $discountRows[] = [
                    'order_id' => $order->id,
                    'order_item_id' => $createdOrderItem->id,
                    'discount_type' => 'coupon',
                    'discount_code' => $itemSnapshot['applied_coupon_code'] ?? null,
                    'discount_name' => 'Coupon Discount',
                    'discount_rate' => null,
                    'discount_amount' => round((float) $itemSnapshot['coupon_discount_amount'] * (int) $createdOrderItem->quantity, 4),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Step 3: insert all discount lines together.
        if ($discountRows !== []) {
            DB::table('order_discount_lines')->insert($discountRows);
        }
    }

    // This stores the selected shipping and billing addresses for a submitted order.
    protected function storeCheckoutOrderAddresses(Order $order, User $user, array $validatedCheckout): void
    {
        // Step 1: resolve the checkout address using the address service.
        $checkoutAddressData = $this->addressService->resolveCheckoutAddress($user, $validatedCheckout);

        // Step 2: create the shipping address row using the address service.
        $shippingAddressPayload = $this->addressService->buildOrderAddressPayload($order, 'shipping', $checkoutAddressData, $user, $validatedCheckout);
        $order->addresses()->create($shippingAddressPayload);

        // Step 3: create the billing address row using the address service.
        $billingAddressPayload = $this->addressService->buildOrderAddressPayload($order, 'billing', $checkoutAddressData, $user, $validatedCheckout);
        $order->addresses()->create($billingAddressPayload);
    }

    // This sends the order-submitted customer email without interrupting a successful checkout.
    protected function sendOrderSubmittedEmail(User $user, Order $order): void
    {
        // Step 1: skip the send cleanly when the account does not have an email address yet.
        if (! filled($user->email)) {
            Log::warning('Order submitted email skipped because the user email is empty.', [
                'user_id' => $user->id,
                'order_id' => $order->id,
            ]);

            return;
        }

        // Step 2: send the confirmation email using the shared notification service.
        $this->emailNotificationService->sendOrderSubmittedConfirmation($user, $order);
    }
}
