<?php

namespace App\Services\Order;

use App\Models\Authorization\User;
use App\Models\Order\Order;
use App\Models\Product\ProductVariant;
use App\Services\Authorization\DataVisibilityService;
use App\Services\Coupon\CouponService;
use App\Services\Inventory\InventoryManagementService;
use App\Services\Notification\EmailNotificationService;
use App\Services\Pricing\PriceService;
use App\Services\Utility\OrderItemCalculator;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class OrderLifecycleService
{
    public function __construct(
        protected OrderCalculationService $calculationService,
        protected OrderFormatterService $formatterService,
        protected OrderAddressService $addressService,
        protected DataVisibilityService $dataVisibilityService,
        protected PriceService $priceService,
        protected CouponService $couponService,
        protected EmailNotificationService $emailNotificationService,
        protected OrderItemCalculator $itemCalculator,
        protected InventoryManagementService $inventoryService,
    ) {
    }

    // Prepare all page data for the customer profile orders display.
    public function getCustomerOrdersPageData(User $user): array
    {
        // Load the user's orders with all related data (no N+1 queries)
        $orderQuery = Order::query();
        $orderQuery->with([
            'shippingAddress',
            'billingAddress',
            'items' => fn ($builder) => $builder->orderBy('sort_order')->orderBy('id'),
            'items.product:id,name,sku',
            'items.product.primaryImage:id,file_path',
            'items.variant:id,product_id,sku,variant_name',
        ]);
        $orderQuery->where('placed_by_user_id', $user->id);
        $orderQuery->orderByDesc('created_at');
        $orderQuery->orderByDesc('id');

        $savedOrders = $orderQuery->get();

        // Format orders for display.
        return $this->formatterService->formatCustomerOrdersForDisplay($savedOrders);
    }

    // Prepare page data for the order CRUD interface.
    public function getOrderCrudPageData(User $user, ?int $editingOrderId = null): array
    {
        // Load visible products with current prices.
        $products = $this->getVisibleProductsWithPrices($user);

        // Load existing orders.
        $orders = $this->getOrdersForUserPaginated($user);

        // Load order for editing when requested.
        $editingOrder = null;
        $editingItems = collect();

        if ($editingOrderId) {
            $editingOrder = $this->getOrderById($editingOrderId, $user);
            $editingItems = $editingOrder->items->sortBy('sort_order')->values();
        }

        return [
            'products' => $products,
            'orders' => $orders,
            'editingOrder' => $editingOrder,
            'editingItems' => $editingItems,
        ];
    }

    // Create a new order with all its items.
    public function createOrder(array $validatedOrder, User $user): Order
    {
        // Prepare all order items.
        $preparedOrderItems = $this->prepareOrderItems($validatedOrder, $user);

        // Calculate final totals.
        $orderTotals = $this->calculationService->calculateOrderTotals($validatedOrder, $preparedOrderItems);

        // Save the order and items in a transaction.
        $order = DB::transaction(function () use ($validatedOrder, $user, $preparedOrderItems, $orderTotals): Order {
            $order = Order::query()->create([
                'placed_by_user_id' => $user->id,
                'company_id' => $user->company_id,
                'status' => $validatedOrder['status'],
                'currency' => $orderTotals['currency'],
                'subtotal_amount' => $orderTotals['subtotal_amount'],
                'tax_amount' => $orderTotals['tax_amount'],
                'discount_amount' => $orderTotals['discount_amount'],
                'shipping_amount' => $orderTotals['shipping_amount'],
                'adjustment_amount' => $orderTotals['adjustment_amount'],
                'rounding_amount' => $orderTotals['rounding_amount'],
                'total_amount' => $orderTotals['total_amount'],
                'pricing_snapshot' => $orderTotals['pricing_snapshot'],
                'notes' => $validatedOrder['notes'] ?: null,
                'submitted_at' => $validatedOrder['status'] === 'submitted' ? now() : null,
                'cancelled_at' => $validatedOrder['status'] === 'cancelled' ? now() : null,
            ]);

            foreach ($preparedOrderItems as $preparedOrderItem) {
                $order->items()->create($preparedOrderItem);
            }

            return $order;
        });

        Log::info('Order created successfully.', ['order_id' => $order->id, 'user_id' => $user->id]);

        return $this->getOrderById($order->id, $user);
    }

    // Update an existing order with new items and totals.
    public function updateOrderById(int $orderId, array $validatedOrder, User $user): Order
    {
        // Load the existing order.
        $order = $this->getOrderById($orderId, $user);

        // Prepare fresh items from new form data.
        $preparedOrderItems = $this->prepareOrderItems($validatedOrder, $user);

        // Recalculate totals.
        $orderTotals = $this->calculationService->calculateOrderTotals($validatedOrder, $preparedOrderItems);

        // Update in a transaction.
        DB::transaction(function () use ($order, $validatedOrder, $user, $preparedOrderItems, $orderTotals): void {
            // Determine submitted and cancelled timestamps.
            $submittedAt = $order->submitted_at;
            $cancelledAt = $order->cancelled_at;

            if ($validatedOrder['status'] === 'submitted' && ! $submittedAt) {
                $submittedAt = now();
            }

            if ($validatedOrder['status'] === 'cancelled') {
                $cancelledAt = now();
            }

            if ($validatedOrder['status'] !== 'cancelled') {
                $cancelledAt = null;
            }

            // Update the order header.
            $order->update([
                'company_id' => $user->company_id,
                'status' => $validatedOrder['status'],
                'currency' => $orderTotals['currency'],
                'subtotal_amount' => $orderTotals['subtotal_amount'],
                'tax_amount' => $orderTotals['tax_amount'],
                'discount_amount' => $orderTotals['discount_amount'],
                'shipping_amount' => $orderTotals['shipping_amount'],
                'adjustment_amount' => $orderTotals['adjustment_amount'],
                'rounding_amount' => $orderTotals['rounding_amount'],
                'total_amount' => $orderTotals['total_amount'],
                'pricing_snapshot' => $orderTotals['pricing_snapshot'],
                'notes' => $validatedOrder['notes'] ?: null,
                'submitted_at' => $submittedAt,
                'cancelled_at' => $cancelledAt,
            ]);

            // Replace items.
            $order->items()->delete();

            foreach ($preparedOrderItems as $preparedOrderItem) {
                $order->items()->create($preparedOrderItem);
            }
        });

        Log::info('Order updated successfully.', ['order_id' => $orderId, 'user_id' => $user->id]);

        return $this->getOrderById($orderId, $user);
    }

    // Prepare a reorder from an existing order.
    public function prepareReOrder(int $orderId, User $user, Request $request): void
    {
        // Load the existing order.
        $order = $this->getOrderById($orderId, $user);
        $fallbackImage = asset('upload/categories/image1.jpg');

        // Check that order has items to reorder.
        if ($order->items->isEmpty()) {
            throw ValidationException::withMessages([
                'order' => 'This order does not have any items to reorder.',
            ]);
        }

        // Prepare reorder checkout items with latest prices.
        $checkoutItems = [];
        $subtotalAmount = 0;
        $taxAmount = 0;
        $totalAmount = 0;
        $currency = $order->currency ?: 'INR';

        foreach ($order->items as $orderItem) {
            $productId = (int) $orderItem->product_id;
            $productVariantId = $orderItem->product_variant_id ? (int) $orderItem->product_variant_id : null;
            $quantity = (int) $orderItem->quantity;
            $resolvedPrice = null;

            if ($productId === 0) {
                $productId = (int) ($orderItem->product?->id ?? 0);
            }

            // Resolve current price.
            if ($productVariantId) {
                $resolvedPrice = $this->priceService->resolveVariantPrice($productVariantId, $user, $quantity);
            }

            if (! $productVariantId) {
                $resolvedPrice = $this->priceService->resolveProductPrice($productId, $user, $quantity);
            }

            if (! $resolvedPrice) {
                throw ValidationException::withMessages([
                    'order' => 'One order item is no longer available for reorder.',
                ]);
            }

            // Calculate line totals.
            $unitPrice = round((float) ($resolvedPrice['amount'] ?? 0), 4);
            $unitTaxAmount = round((float) ($resolvedPrice['tax_amount'] ?? 0), 4);
            $unitPriceAfterGst = round((float) ($resolvedPrice['price_after_gst'] ?? ($unitPrice + $unitTaxAmount)), 4);
            $lineSubtotal = round($unitPrice * $quantity, 4);
            $lineTaxAmount = round($unitTaxAmount * $quantity, 4);
            $lineTotal = round($unitPriceAfterGst * $quantity, 4);
            $imagePath = $orderItem->product?->primaryImage?->file_path;
            $imageUrl = $fallbackImage;
            $currency = (string) ($resolvedPrice['currency'] ?? $currency);

            if (filled($imagePath)) {
                $imageUrl = asset($imagePath);
            }

            // Add to checkout items.
            $checkoutItems[] = [
                'productId' => $productId,
                'variantId' => $productVariantId,
                'quantity' => $quantity,
                'unitPrice' => $unitPrice,
                'unitTaxAmount' => $unitTaxAmount,
                'unitPriceAfterGst' => $unitPriceAfterGst,
                'taxAmount' => $lineTaxAmount,
                'lineSubtotal' => $lineSubtotal,
                'lineTotal' => $lineTotal,
                'discountAmount' => round((float) ($resolvedPrice['discount_amount'] ?? 0) * $quantity, 4),
                'currency' => $currency,
                'priceType' => $resolvedPrice['price_type'] ?? null,
                'name' => (string) ($orderItem->product_name ?: 'Product'),
                'model' => (string) ($orderItem->sku ?: 'N/A'),
                'image' => $imageUrl,
                'minOrderQuantity' => (int) ($resolvedPrice['min_order_quantity'] ?? 1),
                'maxOrderQuantity' => $resolvedPrice['max_order_quantity'] === null ? null : (int) $resolvedPrice['max_order_quantity'],
                'lotSize' => (int) ($resolvedPrice['lot_size'] ?? 1),
            ];

            $subtotalAmount += $lineSubtotal;
            $taxAmount += $lineTaxAmount;
            $totalAmount += $lineTotal;
        }

        // Store reorder checkout in session.
        $request->session()->put('reorder_checkout', [
            'source' => 'reorder',
            'orderId' => $order->id,
            'currency' => $currency,
            'items_count' => count($checkoutItems),
            'subtotal_amount' => round($subtotalAmount, 4),
            'tax_amount' => round($taxAmount, 4),
            'total_amount' => round($totalAmount, 4),
            'items' => $checkoutItems,
        ]);
    }

    // Submit a reorder checkout.
    public function submitReOrderCheckout(array $validatedCheckout, User $user): array
    {
        // Validate coupon code.
        $couponCode = $this->couponService->readValidatedCouponCode($validatedCheckout['coupon_code'] ?? null);

        // Prepare reorder items.
        $preparedOrderItems = $this->prepareReOrderCheckoutItems($validatedCheckout, $user, $couponCode);

        // Ensure coupon applies to items if present.
        $this->couponService->ensureCouponAppliesToPreparedItems(
            $couponCode,
            $preparedOrderItems,
            'The selected coupon does not apply to the current reorder items.',
        );

        // Calculate totals.
        $orderTotals = $this->calculationService->calculateOrderTotals($validatedCheckout, $preparedOrderItems, 'reorder_checkout');

        // Resolve address.
        $checkoutAddressData = $this->addressService->resolveCheckoutAddress($user, $validatedCheckout);

        // Create order in a transaction.
        $order = DB::transaction(function () use ($user, $validatedCheckout, $preparedOrderItems, $orderTotals, $checkoutAddressData): Order {
            $order = Order::query()->create([
                'placed_by_user_id' => $user->id,
                'company_id' => $user->company_id,
                'status' => 'submitted',
                'currency' => $orderTotals['currency'],
                'subtotal_amount' => $orderTotals['subtotal_amount'],
                'tax_amount' => $orderTotals['tax_amount'],
                'discount_amount' => $orderTotals['discount_amount'],
                'shipping_amount' => $orderTotals['shipping_amount'],
                'adjustment_amount' => $orderTotals['adjustment_amount'],
                'rounding_amount' => $orderTotals['rounding_amount'],
                'total_amount' => $orderTotals['total_amount'],
                'pricing_snapshot' => $orderTotals['pricing_snapshot'],
                'notes' => $validatedCheckout['notes'] ?? null,
                'submitted_at' => now(),
                'cancelled_at' => null,
            ]);

            foreach ($preparedOrderItems as $preparedOrderItem) {
                $order->items()->create($preparedOrderItem);
            }

            // Save shipping and billing addresses.
            $shippingAddressPayload = $this->addressService->buildOrderAddressPayload($order, 'shipping', $checkoutAddressData, $user, $validatedCheckout);
            $billingAddressPayload = $this->addressService->buildOrderAddressPayload($order, 'billing', $checkoutAddressData, $user, $validatedCheckout);
            $order->addresses()->create($shippingAddressPayload);
            $order->addresses()->create($billingAddressPayload);

            // Deduct stock for each successful order item
            foreach ($preparedOrderItems as $preparedOrderItem) {
                $this->inventoryService->deductStock(
                    (int) $preparedOrderItem['product_id'],
                    $preparedOrderItem['product_variant_id'] ? (int) $preparedOrderItem['product_variant_id'] : null,
                    (int) $preparedOrderItem['quantity']
                );
            }

            return Order::query()
                ->with([
                    'items' => fn ($builder) => $builder->orderBy('sort_order')->orderBy('id'),
                ])
                ->findOrFail($order->id);
        });

        // Send confirmation email.
        if (filled($user->email)) {
            $this->emailNotificationService->sendOrderSubmittedConfirmation($user, $order);
        }

        // Build confirmation summary.
        $orderItems = [];

        foreach ($order->items as $orderItem) {
            $orderItems[] = [
                'id' => $orderItem->id,
                'product_name' => $orderItem->product_name,
                'variant_name' => $orderItem->variant_name,
                'sku' => $orderItem->sku,
                'quantity' => (int) $orderItem->quantity,
                'unit_price' => (float) $orderItem->unit_price,
                'tax_amount' => (float) $orderItem->tax_amount,
                'total_amount' => (float) $orderItem->total_amount,
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
                'items_count' => count($orderItems),
                'items' => $orderItems,
            ],
        ];
    }

    // Preview coupon on reorder checkout.
    public function previewReOrderCoupon(array $validatedCouponInput, User $user): array
    {
        // Validate coupon.
        $couponCode = $this->couponService->readValidatedCouponCode($validatedCouponInput['coupon_code'] ?? null);

        // Prepare items with this coupon.
        $preparedOrderItems = $this->prepareReOrderCheckoutItems($validatedCouponInput, $user, $couponCode);

        // Ensure coupon applies.
        $this->couponService->ensureCouponAppliesToPreparedItems(
            $couponCode,
            $preparedOrderItems,
            'The selected coupon does not apply to the current reorder items.',
        );

        // Build preview.
        return $this->couponService->buildCouponPreview(
            $couponCode,
            $preparedOrderItems,
            'Coupon applied successfully.',
            'The selected coupon does not apply to the current reorder items.',
        );
    }

    // Preview pricing updates on reorder checkout.
    public function previewReOrderPricing(array $validatedPreviewInput, User $user): array
    {
        // Read applied coupon.
        $couponCode = null;
        $enteredCouponCode = trim((string) ($validatedPreviewInput['coupon_code'] ?? ''));

        if ($enteredCouponCode !== '') {
            $couponCode = $this->couponService->readValidatedCouponCode($enteredCouponCode);
        }

        // Prepare items with latest pricing.
        $preparedOrderItems = $this->prepareReOrderCheckoutItems($validatedPreviewInput, $user, $couponCode);

        // Decode posted items to preserve UI state.
        $decodedReOrderItems = json_decode((string) ($validatedPreviewInput['reorder_items'] ?? ''), true);

        if (! is_array($decodedReOrderItems)) {
            throw ValidationException::withMessages([
                'reorder_items' => 'Reorder items are not available for checkout.',
            ]);
        }

        // Build refreshed checkout items.
        $previewItems = [];

        foreach ($preparedOrderItems as $index => $preparedOrderItem) {
            $sourceItem = is_array($decodedReOrderItems[$index] ?? null) ? $decodedReOrderItems[$index] : [];
            $itemSnapshot = is_array($preparedOrderItem['item_snapshot'] ?? null) ? $preparedOrderItem['item_snapshot'] : [];
            $quantity = (int) ($preparedOrderItem['quantity'] ?? 0);
            $unitPrice = round((float) ($preparedOrderItem['unit_price'] ?? 0), 4);
            $unitTaxAmount = round((float) ($itemSnapshot['unit_tax_amount'] ?? 0), 4);
            $unitPriceAfterGst = round((float) ($itemSnapshot['unit_price_after_gst'] ?? ($unitPrice + $unitTaxAmount)), 4);
            $lineSubtotal = round((float) ($preparedOrderItem['subtotal_amount'] ?? 0), 4);
            $lineTaxAmount = round((float) ($preparedOrderItem['tax_amount'] ?? 0), 4);
            $lineTotal = round((float) ($preparedOrderItem['total_amount'] ?? 0), 4);
            $lineDiscountAmount = round((float) ($preparedOrderItem['discount_amount'] ?? 0), 4);
            $productId = (int) ($preparedOrderItem['product_id'] ?? ($sourceItem['productId'] ?? 0));
            $productVariantId = $preparedOrderItem['product_variant_id'] ?? ($sourceItem['variantId'] ?? null);
            $imageUrl = (string) ($sourceItem['image'] ?? $sourceItem['image_url'] ?? 'https://via.placeholder.com/96x96?text=Bio');
            $productName = (string) ($preparedOrderItem['product_name'] ?? ($sourceItem['name'] ?? 'Product'));
            $productModel = (string) ($preparedOrderItem['sku'] ?? ($sourceItem['model'] ?? 'N/A'));

            if ($productVariantId !== null && $productVariantId !== '') {
                $productVariantId = (int) $productVariantId;
            } else {
                $productVariantId = null;
            }

            $previewItems[] = [
                'productId' => $productId,
                'variantId' => $productVariantId,
                'quantity' => $quantity,
                'unitPrice' => $unitPrice,
                'unitTaxAmount' => $unitTaxAmount,
                'unitPriceAfterGst' => $unitPriceAfterGst,
                'taxAmount' => $lineTaxAmount,
                'lineSubtotal' => $lineSubtotal,
                'lineTotal' => $lineTotal,
                'discountAmount' => $lineDiscountAmount,
                'currency' => (string) ($itemSnapshot['currency'] ?? 'INR'),
                'priceType' => $itemSnapshot['price_type'] ?? null,
                'name' => $productName,
                'model' => $productModel,
                'image' => $imageUrl,
                'minOrderQuantity' => (int) ($itemSnapshot['min_order_quantity'] ?? 1),
                'maxOrderQuantity' => $itemSnapshot['max_order_quantity'] === null ? null : (int) $itemSnapshot['max_order_quantity'],
                'lotSize' => (int) ($itemSnapshot['lot_size'] ?? 1),
            ];
        }

        // Build coupon preview if active.
        $couponPreview = null;

        if ($couponCode !== null) {
            $couponPreview = $this->couponService->buildCouponPreview(
                $couponCode,
                $preparedOrderItems,
                'Coupon applied successfully.',
                'The selected coupon does not apply to the current reorder items.',
            );
        }

        return [
            'items' => $previewItems,
            'coupon_preview' => $couponPreview,
        ];
    }

    // Load visible products with their current prices.
    protected function getVisibleProductsWithPrices(User $user): Collection
    {
        // Load products with variants eager-loaded (single query, no N+1)
        $products = $this->dataVisibilityService->visibleProductQuery($user)
            ->with([
                'variants' => function ($query): void {
                    $query->where('is_active', true)->orderBy('id');
                },
            ])
            ->orderBy('products.name')
            ->get();

        // Attach pricing data to each product
        return $products->map(function ($product) use ($user) {
            // Get first active variant from already-loaded collection
            $firstVariant = $product->variants->first();

            if ($firstVariant) {
                // Resolve pricing for this variant (no extra query per product)
                $price = $this->priceService->resolveVariantPrice((int) $firstVariant->id, $user);

                if ($price) {
                    $product->visible_price = $price['amount'] ?? null;
                    $product->visible_currency = $price['currency'] ?? 'INR';
                    $product->visible_price_type = $price['price_type'] ?? null;
                    $product->gst_rate = $price['gst_rate'] ?? 0;
                    $product->tax_amount = $price['tax_amount'] ?? 0;
                    $product->price_after_gst = $price['price_after_gst'] ?? 0;
                    $product->min_order_quantity = $price['min_order_quantity'] ?? 1;
                    $product->max_order_quantity = $price['max_order_quantity'] ?? null;
                    $product->lot_size = $price['lot_size'] ?? 1;
                    $product->product_variant_id = $price['product_variant_id'] ?? null;
                    $product->variant_name = $price['variant_name'] ?? null;
                    $product->variant_sku = $price['variant_sku'] ?? null;

                    return $product;
                }
            }

            // Fallback defaults if no pricing found
            $product->visible_price = 0.0;
            $product->visible_currency = 'INR';
            $product->visible_price_type = 'manual_review';
            $product->gst_rate = 18.0;
            $product->tax_amount = 0.0;
            $product->price_after_gst = 0.0;
            $product->min_order_quantity = $firstVariant ? max(1, (int) $firstVariant->min_order_quantity) : 1;
            $product->max_order_quantity = $firstVariant?->max_order_quantity ?? null;
            $product->lot_size = $firstVariant ? max(1, (int) $firstVariant->lot_size) : 1;
            $product->product_variant_id = $firstVariant?->id ?? null;
            $product->variant_name = $firstVariant?->variant_name ?? null;
            $product->variant_sku = $firstVariant?->sku ?? null;

            return $product;
        });
    }

    // Prepare order items from form input.
    protected function prepareOrderItems(array $validatedOrder, User $user): array
    {
        // Read product and quantity arrays.
        $productIds = is_array($validatedOrder['product_id'] ?? null) ? $validatedOrder['product_id'] : [];
        $quantities = is_array($validatedOrder['quantity'] ?? null) ? $validatedOrder['quantity'] : [];
        $rowCount = max(count($productIds), count($quantities));
        $preparedItems = [];

        // Collect all product IDs that need validation.
        $productsToLoad = [];
        $indexMap = []; // Track which products are needed at which indices.

        for ($index = 0; $index < $rowCount; $index++) {
            $productId = (int) ($productIds[$index] ?? 0);
            if ($productId > 0) {
                $productsToLoad[] = $productId;
                $indexMap[$productId] = $indexMap[$productId] ?? [];
                $indexMap[$productId][] = $index;
            }
        }

        // Stop early if no products.
        if (empty($productsToLoad)) {
            throw ValidationException::withMessages([
                'product_id' => 'Add at least one order item.',
            ]);
        }

        // Load all visible products in a single query using whereIn (no N+1).
        $visibleProducts = $this->dataVisibilityService->visibleProductQuery($user)
            ->whereIn('products.id', array_unique($productsToLoad))
            ->get()
            ->keyBy('id');

        // Prepare each item.
        for ($index = 0; $index < $rowCount; $index++) {
            $productId = (int) ($productIds[$index] ?? 0);
            $quantity = (int) ($quantities[$index] ?? 0);

            if ($productId === 0) {
                continue;
            }

            // Retrieve product from already-loaded collection.
            $visibleProduct = $visibleProducts->get($productId);

            if (! $visibleProduct) {
                throw ValidationException::withMessages([
                    "product_id.$index" => 'The selected product is not available for this user.',
                ]);
            }

            // Resolve price.
            $price = $this->priceService->resolveProductPrice($productId, $user, $quantity);

            if (! $price) {
                throw ValidationException::withMessages([
                    "product_id.$index" => 'No visible price is configured for the selected product.',
                ]);
            }

            // Validate quantity rules.
            $this->calculationService->validateOrderQuantity($quantity, $price, $index);

            // Build the item.
            $preparedItems[] = $this->calculationService->buildOrderItemPayload($visibleProduct, $price, $quantity, $index);
        }

        // Stop if no valid items.
        if ($preparedItems === []) {
            throw ValidationException::withMessages([
                'product_id' => 'Add at least one order item.',
            ]);
        }

        return $preparedItems;
    }

    // Prepare reorder checkout items with pricing.
    protected function prepareReOrderCheckoutItems(array $validatedCheckout, User $user, ?string $couponCode = null): array
    {
        // Decode posted items.
        $encodedReOrderItems = (string) ($validatedCheckout['reorder_items'] ?? '');
        $decodedReOrderItems = json_decode($encodedReOrderItems, true);

        if (! is_array($decodedReOrderItems)) {
            throw ValidationException::withMessages([
                'reorder_items' => 'Reorder items are not available for checkout.',
            ]);
        }

        // Collect variant IDs and product IDs to batch-load.
        $variantIds = [];
        $productIds = [];

        foreach ($decodedReOrderItems as $decodedReOrderItem) {
            $productVariantId = $decodedReOrderItem['variantId'] ?? null;
            $productId = (int) ($decodedReOrderItem['productId'] ?? 0);

            if ($productVariantId !== null && $productVariantId !== '') {
                $variantIds[] = (int) $productVariantId;
            }

            if ($productId > 0) {
                $productIds[] = $productId;
            }
        }

        // Batch-load all variants with their products (no N+1 for variants).
        $relevantVariants = [];
        if (! empty($variantIds)) {
            $relevantVariants = ProductVariant::query()
                ->with('product:id,name')
                ->whereIn('id', array_unique($variantIds))
                ->get()
                ->keyBy('id');
        }

        // Batch-load all visible products (no N+1 for products).
        $relevantProducts = [];
        if (! empty($productIds)) {
            $relevantProducts = $this->dataVisibilityService->visibleProductQuery($user)
                ->whereIn('products.id', array_unique($productIds))
                ->get()
                ->keyBy('id');
        }

        // Prepare each item using pre-loaded data.
        $preparedOrderItems = [];

        foreach ($decodedReOrderItems as $index => $decodedReOrderItem) {
            $productId = (int) ($decodedReOrderItem['productId'] ?? 0);
            $productVariantId = $decodedReOrderItem['variantId'] ?? null;
            $quantity = (int) ($decodedReOrderItem['quantity'] ?? 0);

            if ($productVariantId !== null && $productVariantId !== '') {
                $productVariantId = (int) $productVariantId;
            }

            if ($productVariantId === '') {
                $productVariantId = null;
            }

            if ($quantity <= 0 || ($productId === 0 && ! $productVariantId)) {
                continue;
            }

            $resolvedPrice = null;
            $productVariant = null;
            $visibleProduct = null;

            // Resolve price from variant (use pre-loaded variant from collection).
            if ($productVariantId) {
                $productVariant = $relevantVariants[$productVariantId] ?? null;
                $resolvedPrice = $this->priceService->resolveVariantPrice($productVariantId, $user, $quantity, $couponCode);
                $visibleProduct = $productVariant?->product;
            }

            // Resolve price from product (use pre-loaded product from collection).
            if (! $productVariantId) {
                $resolvedPrice = $this->priceService->resolveProductPrice($productId, $user, $quantity, $couponCode);

                if (($resolvedPrice['product_variant_id'] ?? null) !== null) {
                    $productVariantId = (int) $resolvedPrice['product_variant_id'];
                    $productVariant = $relevantVariants[$productVariantId] ?? null;
                    $visibleProduct = $productVariant?->product;
                }

                if (! $visibleProduct) {
                    $visibleProduct = $relevantProducts[$productId] ?? null;
                }
            }

            if (! $resolvedPrice) {
                throw ValidationException::withMessages([
                    'reorder_items' => 'One reorder item is no longer available for checkout.',
                ]);
            }

            // Validate quantity using centralized validation service.
            $this->calculationService->validateOrderQuantity($quantity, $resolvedPrice, $index);

            // Check if sufficient stock is available for this reorder item
            $hasStock = $this->inventoryService->checkAvailability(
                $visibleProduct?->id ?: $productId,
                $productVariantId,
                $quantity
            );

            if (! $hasStock) {
                $productName = $visibleProduct?->name ?? 'Product';
                throw ValidationException::withMessages([
                    'reorder_items' => "Insufficient stock for {$productName}. Try a lower quantity.",
                ]);
            }

            // Build the item using centralized method.
            $preparedOrderItems[] = $this->calculationService->buildOrderItemPayload($visibleProduct, $resolvedPrice, $quantity, $index);
        }

        // Stop if no valid items.
        if ($preparedOrderItems === []) {
            throw ValidationException::withMessages([
                'reorder_items' => 'No reorder items are available for checkout.',
            ]);
        }

        return $preparedOrderItems;
    }

    // Load one order with all its related data for the user.
    public function getOrderById(int $orderId, User $user): Order
    {
        // Load the order and its relations.
        $query = Order::query()
            ->with([
                'placedByUser:id,name,email,user_type',
                'company:id,name,company_type',
                'items' => fn ($builder) => $builder->orderBy('sort_order')->orderBy('id'),
                'items.product:id,name,sku',
                'items.product.primaryImage:id,file_path',
                'items.variant:id,product_id,sku,variant_name',
            ]);

        // Regular users can only see their own orders.
        if (! $user->isAdmin()) {
            $query->where('placed_by_user_id', $user->id);
        }

        return $query->findOrFail($orderId);
    }

    // Load paginated orders for the user.
    public function getOrdersForUserPaginated(User $user): LengthAwarePaginator
    {
        $query = Order::query()
            ->with([
                'company:id,name',
            ])
            ->orderByDesc('created_at');

        // Regular users see only their own orders.
        if (! $user->isAdmin()) {
            $query->where('placed_by_user_id', $user->id);
        }

        return $query->paginate(10);
    }

    // Soft delete one order so it no longer appears in normal queries.
    public function softDeleteOrderById(int $orderId, User $user): void
    {
        $order = $this->getOrderById($orderId, $user);
        $order->delete();

        Log::info('Order soft deleted successfully.', ['order_id' => $orderId, 'user_id' => $user->id]);
    }
}
