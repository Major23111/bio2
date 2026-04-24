<?php

namespace App\Services\AdminPanel\Order;

use App\Models\Order\Order;
use App\Models\Order\OrderAddress;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class OrderCrudService
{
    // Get all orders with backend data for the admin list page.
    public function getAllOrdersForAdminList(int $perPage = 10): LengthAwarePaginator
    {
        // Load the main order rows with customer, item, and shipping details.
        $savedOrderList = Order::query()
            ->with([
                'placedByUser:id,name',
                'company:id,name',
                'items:id,order_id,product_name,sort_order',
                'shippingAddress:id,order_id,city,state',
            ])
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate($perPage);

        // Collect the current order ids for related records.
        $orderIdList = [];

        foreach ($savedOrderList->items() as $savedOrder) {
            $orderIdList[] = (int) $savedOrder->id;
        }

        // Prepare the saved payment rows by order id.
        $paymentRowMap = [];

        if ($orderIdList !== []) {
            $paymentRowList = DB::table('payments')
                ->whereIn('order_id', $orderIdList)
                ->orderByDesc('id')
                ->get();

            foreach ($paymentRowList as $paymentRow) {
                if (! isset($paymentRowMap[$paymentRow->order_id])) {
                    $paymentRowMap[$paymentRow->order_id] = $paymentRow;
                }
            }
        }

        // Prepare the saved shipment rows by order id.
        $shipmentRowMap = [];

        if ($orderIdList !== []) {
            $shipmentRowList = DB::table('shipments')
                ->whereIn('order_id', $orderIdList)
                ->orderByDesc('id')
                ->get();

            foreach ($shipmentRowList as $shipmentRow) {
                if (! isset($shipmentRowMap[$shipmentRow->order_id])) {
                    $shipmentRowMap[$shipmentRow->order_id] = $shipmentRow;
                }
            }
        }

        // Prepare the latest saved page stage by order id.
        $savedStageLabelMap = [];

        if ($orderIdList !== []) {
            $savedStageHistoryList = DB::table('order_status_history')
                ->whereIn('order_id', $orderIdList)
                ->orderByDesc('id')
                ->get();

            foreach ($savedStageHistoryList as $savedStageHistory) {
                if (! isset($savedStageLabelMap[$savedStageHistory->order_id])) {
                    $savedStageLabelMap[$savedStageHistory->order_id] = (string) $savedStageHistory->to_status;
                }
            }
        }

        // Build the final order rows for the list page.
        $preparedOrderList = [];

        foreach ($savedOrderList->items() as $savedOrder) {
            // Read the related rows for the current order.
            $orderPaymentRow = $paymentRowMap[$savedOrder->id] ?? null;
            $orderShipmentRow = $shipmentRowMap[$savedOrder->id] ?? null;
            $savedStageLabel = $savedStageLabelMap[$savedOrder->id] ?? null;

            // Read the first product row for a quick order summary.
            $firstOrderItem = $savedOrder->items->sortBy('sort_order')->first();

            // Prepare the second customer line for the list table.
            $customerSummary = $savedOrder->company?->name ?? 'Direct Customer';
            $customerCity = trim((string) ($savedOrder->shippingAddress?->city ?? ''));
            $customerState = trim((string) ($savedOrder->shippingAddress?->state ?? ''));

            if ($customerCity !== '' && $customerState !== '') {
                $customerSummary = $customerCity . ', ' . $customerState;
            }

            if ($customerCity !== '' && $customerState === '') {
                $customerSummary = $customerCity;
            }

            if ($customerCity === '' && $customerState !== '') {
                $customerSummary = $customerState;
            }

            // Prepare the current page stage label.
            $orderStageLabel = $this->getOrderStageLabel(
                $savedStageLabel,
                (string) ($savedOrder->status ?? 'draft'),
                (string) ($orderShipmentRow->status ?? ''),
                (string) ($orderPaymentRow->status ?? ''),
                (string) ($orderPaymentRow->method ?? ''),
            );

            // Prepare the created date text for the table.
            $createdDateText = 'Not Available';

            if ($savedOrder->created_at) {
                $createdDateText = $savedOrder->created_at->format('M d, Y');
            }

            // Build the final row values.
            $preparedOrderList[] = [
                'id' => (int) $savedOrder->id,
                'orderNumber' => $this->getAdminOrderNumber((int) $savedOrder->id),
                'customerName' => $savedOrder->placedByUser?->name ?? 'Unknown Customer',
                'customerSummary' => $customerSummary,
                'primaryItemName' => $firstOrderItem?->product_name ?? 'Items awaiting review',
                'itemCount' => $savedOrder->items->count(),
                'createdDateText' => $createdDateText,
                'totalAmountText' => $this->getAmountText(
                    (float) ($savedOrder->total_amount ?? 0),
                    (string) ($savedOrder->currency ?? 'INR'),
                ),
                'paymentStatusLabel' => $this->getPaymentStatusLabel($orderPaymentRow),
                'fulfillmentStatusLabel' => $orderStageLabel,
            ];
        }

        // Replace the paginator rows with the prepared rows.
        $savedOrderList->setCollection(collect($preparedOrderList));

        return $savedOrderList;
    }

    // Get the full order details for the admin detail page.
    public function getOrderForView(int $orderId): ?array
    {
        // Load the order with the related data needed by the page.
        $savedOrder = Order::query()
            ->with([
                'placedByUser:id,name,email,phone',
                'company:id,name',
                'items:id,order_id,sku,product_name,variant_name,quantity,unit_price,total_amount,sort_order',
                'shippingAddress',
                'billingAddress',
            ])
            ->find($orderId);

        // Stop when the order is not available.
        if (! $savedOrder) {
            return null;
        }

        // Load the latest payment row for the order.
        $paymentRow = DB::table('payments')
            ->where('order_id', $orderId)
            ->orderByDesc('id')
            ->first();

        // Load the latest shipment row for the order.
        $shipmentRow = DB::table('shipments')
            ->where('order_id', $orderId)
            ->orderByDesc('id')
            ->first();

        // Load the latest saved stage row for the order.
        $savedStageHistory = DB::table('order_status_history')
            ->where('order_id', $orderId)
            ->orderByDesc('id')
            ->first();

        // Pick the best address row for display.
        $displayAddress = $savedOrder->shippingAddress ?: $savedOrder->billingAddress;

        // Prepare the customer details for the page.
        $customerName = $savedOrder->placedByUser?->name ?? 'Unknown Customer';
        $companyName = $savedOrder->company?->name ?? 'Direct Customer';
        $customerEmailValue = $displayAddress?->email ?: ($savedOrder->placedByUser?->email ?? '');
        $customerPhoneValue = $displayAddress?->phone ?: ($savedOrder->placedByUser?->phone ?? '');
        $customerEmail = $customerEmailValue !== '' ? $customerEmailValue : 'Not Available';
        $customerPhone = $customerPhoneValue !== '' ? $customerPhoneValue : 'Not Available';

        // Prepare the current page stage.
        $selectedStageLabel = $this->getOrderStageLabel(
            $savedStageHistory?->to_status,
            (string) ($savedOrder->status ?? 'draft'),
            (string) ($shipmentRow->status ?? ''),
            (string) ($paymentRow->status ?? ''),
            (string) ($paymentRow->method ?? ''),
        );

        // Prepare the payment flow for the stepper.
        $paymentFlowType = 'prepaid';

        if (strtolower((string) ($paymentRow->method ?? '')) === 'cod') {
            $paymentFlowType = 'cod';
        }

        if ($selectedStageLabel === 'Payment Received (COD)') {
            $paymentFlowType = 'cod';
        }

        // Prepare the order item rows for the summary table.
        $preparedItemList = [];

        foreach ($savedOrder->items->sortBy('sort_order') as $savedOrderItem) {
            $itemName = $savedOrderItem->product_name ?: 'Product';
            $variantName = trim((string) ($savedOrderItem->variant_name ?? ''));

            if ($variantName !== '') {
                $itemName = $itemName . ' (' . $variantName . ')';
            }

            $preparedItemList[] = [
                'itemName' => $itemName,
                'sku' => $savedOrderItem->sku ?: 'N/A',
                'quantity' => (int) ($savedOrderItem->quantity ?? 0),
                'unitPriceText' => $this->getAmountText(
                    (float) ($savedOrderItem->unit_price ?? 0),
                    (string) ($savedOrder->currency ?? 'INR'),
                ),
                'lineTotalText' => $this->getAmountText(
                    (float) ($savedOrderItem->total_amount ?? 0),
                    (string) ($savedOrder->currency ?? 'INR'),
                ),
            ];
        }

        // Prepare the order date and time text.
        $placedAt = $savedOrder->submitted_at ?: $savedOrder->created_at;
        $placedDateText = 'Not Available';
        $placedTimeText = '';

        if ($placedAt) {
            $placedDateText = $placedAt->format('M d, Y');
            $placedTimeText = $placedAt->format('h:i A');
        }

        // Prepare the subtotal shown on the page.
        $subtotalAmount = (float) ($savedOrder->subtotal_amount ?? 0);
        $taxAmount = (float) ($savedOrder->tax_amount ?? 0);
        $displaySubtotalAmount = $subtotalAmount + $taxAmount;

        // Build the final page values.
        $orderViewData = [];
        $orderViewData['id'] = (int) $savedOrder->id;
        $orderViewData['orderNumber'] = $this->getAdminOrderNumber((int) $savedOrder->id);
        $orderViewData['selectedStageLabel'] = $selectedStageLabel;
        $orderViewData['paymentFlowType'] = $paymentFlowType;
        $orderViewData['paymentMethodLabel'] = $this->getPaymentMethodLabel($paymentRow);
        $orderViewData['paymentStatusLabel'] = $this->getPaymentStatusLabel($paymentRow);
        $orderViewData['trackingNumber'] = (string) ($shipmentRow->tracking_number ?? '');
        $orderViewData['trackingUrl'] = (string) ($shipmentRow->tracking_url ?? '');
        $orderViewData['customerName'] = $customerName;
        $orderViewData['companyName'] = $companyName;
        $orderViewData['customerEmail'] = $customerEmail;
        $orderViewData['customerEmailValue'] = $customerEmailValue;
        $orderViewData['customerPhone'] = $customerPhone;
        $orderViewData['customerPhoneValue'] = $customerPhoneValue;
        $orderViewData['shippingAddressLines'] = $this->formatCustomerAddressLines($displayAddress);
        $orderViewData['shippingAddressText'] = $this->formatCustomerAddressText($displayAddress);
        $orderViewData['itemCount'] = count($preparedItemList);
        $orderViewData['items'] = $preparedItemList;
        $orderViewData['subtotalAmountText'] = $this->getAmountText($displaySubtotalAmount, (string) ($savedOrder->currency ?? 'INR'));
        $orderViewData['shippingAmountText'] = $this->getAmountText((float) ($savedOrder->shipping_amount ?? 0), (string) ($savedOrder->currency ?? 'INR'));
        $orderViewData['totalAmountText'] = $this->getAmountText((float) ($savedOrder->total_amount ?? 0), (string) ($savedOrder->currency ?? 'INR'));
        $orderViewData['notes'] = $savedOrder->notes ?? '';
        $orderViewData['placedDateText'] = $placedDateText;
        $orderViewData['placedTimeText'] = $placedTimeText;

        return $orderViewData;
    }

    // Save the order changes from the admin detail page.
    public function updateOrder(int $orderId, array $orderData): bool
    {
        // Load the order with the editable related rows.
        $savedOrder = Order::query()
            ->with([
                'placedByUser',
                'company',
                'shippingAddress',
            ])
            ->find($orderId);

        // Stop when the order is not available.
        if (! $savedOrder) {
            return false;
        }

        // Load the current related rows for stage history and page sync.
        $paymentRow = DB::table('payments')
            ->where('order_id', $orderId)
            ->orderByDesc('id')
            ->first();

        $shipmentRow = DB::table('shipments')
            ->where('order_id', $orderId)
            ->orderByDesc('id')
            ->first();

        $savedStageHistory = DB::table('order_status_history')
            ->where('order_id', $orderId)
            ->orderByDesc('id')
            ->first();

        // Read the stage selected on the page.
        $selectedStageLabel = $orderData['order_stage'] ?? 'Order Received';

        // Read the current stage before saving new values.
        $previousStageLabel = $this->getOrderStageLabel(
            $savedStageHistory?->to_status,
            (string) ($savedOrder->status ?? 'draft'),
            (string) ($shipmentRow->status ?? ''),
            (string) ($paymentRow->status ?? ''),
            (string) ($paymentRow->method ?? ''),
        );

        // Start with the draft order status.
        $savedOrderStatus = 'draft';

        if ($selectedStageLabel === 'Cancelled') {
            $savedOrderStatus = 'cancelled';
        }

        if ($selectedStageLabel !== 'Cancelled' && $selectedStageLabel !== 'Order Received') {
            $savedOrderStatus = 'submitted';
        }

        // Prepare the submitted time for active orders.
        $submittedAt = $savedOrder->submitted_at;

        if ($savedOrderStatus === 'submitted' && ! $submittedAt) {
            $submittedAt = now();
        }

        if ($savedOrderStatus === 'draft') {
            $submittedAt = null;
        }

        // Prepare the cancelled time for cancelled orders.
        $cancelledAt = null;

        if ($savedOrderStatus === 'cancelled') {
            $cancelledAt = now();
        }

        // Save the main order values.
        $savedOrder->status = $savedOrderStatus;
        $savedOrder->submitted_at = $submittedAt;
        $savedOrder->cancelled_at = $cancelledAt;
        $savedOrder->notes = $orderData['notes'] ?? $savedOrder->notes;
        $savedOrder->save();

        // Save the customer values when the user row exists.
        if ($savedOrder->placedByUser) {
            $savedOrder->placedByUser->name = $orderData['customer_name'] ?? $savedOrder->placedByUser->name;
            $savedOrder->placedByUser->email = $orderData['customer_email'] ?? $savedOrder->placedByUser->email;
            $savedOrder->placedByUser->phone = $orderData['customer_phone'] ?? $savedOrder->placedByUser->phone;
            $savedOrder->placedByUser->save();
        }

        // Read the address text from the page.
        $shippingAddressText = trim((string) ($orderData['shipping_address_text'] ?? ''));

        // Save the shipping address when address details are available.
        if ($shippingAddressText !== '') {
            $shippingAddressData = $this->prepareShippingAddressData(
                $shippingAddressText,
                $orderData['customer_name'] ?? ($savedOrder->placedByUser?->name ?? null),
                $orderData['customer_email'] ?? ($savedOrder->placedByUser?->email ?? null),
                $orderData['customer_phone'] ?? ($savedOrder->placedByUser?->phone ?? null),
                $savedOrder->company?->name ?? null,
            );

            if ($savedOrder->shippingAddress) {
                $savedOrder->shippingAddress->update($shippingAddressData);
            }

            if (! $savedOrder->shippingAddress) {
                $savedOrder->shippingAddress()->create($shippingAddressData);
            }
        }

        // Refresh the order with the latest shipping row.
        $savedOrder->load('shippingAddress');

        // Save the shipment values shown on the page.
        $this->saveShipmentDetails($savedOrder, $selectedStageLabel, $orderData);

        // Save the payment values shown on the page.
        $this->savePaymentDetails($savedOrder, $selectedStageLabel);

        // Save the selected page stage for future loads.
        $this->saveOrderStageHistory($savedOrder, $previousStageLabel, $selectedStageLabel);

        return true;
    }

    // Build the admin order number shown on the page.
    private function getAdminOrderNumber(int $orderId): string
    {
        // Prepare the padded order id.
        $paddedOrderId = str_pad((string) $orderId, 5, '0', STR_PAD_LEFT);

        // Build the final order number.
        $adminOrderNumber = 'ORD-' . $paddedOrderId;

        return $adminOrderNumber;
    }

    // Build the display amount text shown on the page.
    private function getAmountText(float $amount, string $currency): string
    {
        // Prepare the saved currency text.
        $currencyText = trim($currency);

        if ($currencyText === '') {
            $currencyText = 'INR';
        }

        // Build the final amount text.
        $amountText = $currencyText . ' ' . number_format($amount, 2, '.', ',');

        return $amountText;
    }

    // Convert saved values into the order stage shown on the page.
    private function getOrderStageLabel(?string $savedStageLabel, string $orderStatus, string $shipmentStatus, string $paymentStatus, string $paymentMethod): string
    {
        // Use the saved page stage first when it exists.
        if ($savedStageLabel) {
            return $savedStageLabel;
        }

        // Show cancelled orders first.
        if ($orderStatus === 'cancelled') {
            return 'Cancelled';
        }

        // Show delivered COD payment last in the workflow.
        if ($paymentStatus === 'paid' && strtolower($paymentMethod) === 'cod') {
            return 'Payment Received (COD)';
        }

        // Show delivered orders next.
        if ($shipmentStatus === 'delivered') {
            return 'Delivered';
        }

        // Show dispatched orders next.
        if ($shipmentStatus === 'shipped') {
            return 'Dispatched';
        }

        // Show prepaid payment when payment is completed.
        if ($paymentStatus === 'paid') {
            return 'Payment Received (Prepaid)';
        }

        // Show processing for submitted orders.
        if ($orderStatus === 'submitted') {
            return 'Processing';
        }

        // Start new orders from the first stage.
        return 'Order Received';
    }

    // Convert the saved payment row into the page payment label.
    private function getPaymentStatusLabel(mixed $paymentRow): string
    {
        // Show pending when payment row is not available.
        if (! $paymentRow) {
            return 'Pending';
        }

        // Convert paid payment rows into the page label.
        if ($paymentRow->status === 'paid') {
            return 'Paid';
        }

        // Convert refunded payment rows into the page label.
        if ($paymentRow->status === 'refunded') {
            return 'Refunded';
        }

        // Convert remaining values into a readable label.
        $paymentStatusLabel = ucfirst((string) $paymentRow->status);

        return $paymentStatusLabel;
    }

    // Convert the saved payment row into the page method label.
    private function getPaymentMethodLabel(mixed $paymentRow): string
    {
        // Show a neutral label when payment data is not available.
        if (! $paymentRow) {
            return 'Not Available';
        }

        // Read the saved payment method.
        $savedMethod = trim((string) ($paymentRow->method ?? ''));

        if ($savedMethod === '') {
            $savedMethod = trim((string) ($paymentRow->provider ?? ''));
        }

        if ($savedMethod === '') {
            return 'Not Available';
        }

        // Build the final readable payment method.
        $paymentMethodLabel = ucwords(str_replace(['_', '-'], ' ', $savedMethod));

        return $paymentMethodLabel;
    }

    // Build the customer address lines shown in the side card.
    private function formatCustomerAddressLines(?OrderAddress $savedAddress): array
    {
        // Show a fallback line when no address is available.
        if (! $savedAddress) {
            return ['Address details not available'];
        }

        // Start with an empty line list.
        $addressLineList = [];

        // Add the first address line.
        if ($savedAddress->line1) {
            $addressLineList[] = $savedAddress->line1;
        }

        // Add the second address line when available.
        if ($savedAddress->line2) {
            $addressLineList[] = $savedAddress->line2;
        }

        // Build the city and state line.
        $locationLine = '';
        $cityName = trim((string) ($savedAddress->city ?? ''));
        $stateName = trim((string) ($savedAddress->state ?? ''));
        $postalCode = trim((string) ($savedAddress->postal_code ?? ''));

        if ($cityName !== '' && $stateName !== '') {
            $locationLine = $cityName . ', ' . $stateName;
        }

        if ($cityName !== '' && $stateName === '') {
            $locationLine = $cityName;
        }

        if ($cityName === '' && $stateName !== '') {
            $locationLine = $stateName;
        }

        if ($postalCode !== '' && $locationLine !== '') {
            $locationLine = $locationLine . ' ' . $postalCode;
        }

        if ($postalCode !== '' && $locationLine === '') {
            $locationLine = $postalCode;
        }

        if ($locationLine !== '') {
            $addressLineList[] = $locationLine;
        }

        // Add the country code when available.
        if ($savedAddress->country_code) {
            $addressLineList[] = strtoupper((string) $savedAddress->country_code);
        }

        if ($addressLineList === []) {
            $addressLineList[] = 'Address details not available';
        }

        return $addressLineList;
    }

    // Build the address textarea value for the edit modal.
    private function formatCustomerAddressText(?OrderAddress $savedAddress): string
    {
        // Return an empty value when no address is available.
        if (! $savedAddress) {
            return '';
        }

        // Read the display lines for the textarea.
        $addressLineList = $this->formatCustomerAddressLines($savedAddress);

        // Build the final address text.
        $addressText = implode(PHP_EOL, $addressLineList);

        return $addressText;
    }

    // Convert the address textarea into stored order address values.
    private function prepareShippingAddressData(
        string $shippingAddressText,
        ?string $customerName,
        ?string $customerEmail,
        ?string $customerPhone,
        ?string $companyName
    ): array {
        // Split the textarea into clean lines.
        $addressLineList = preg_split('/\r\n|\r|\n/', $shippingAddressText) ?: [];
        $cleanAddressLineList = [];

        foreach ($addressLineList as $addressLine) {
            $cleanAddressLine = trim($addressLine);

            if ($cleanAddressLine !== '') {
                $cleanAddressLineList[] = $cleanAddressLine;
            }
        }

        // Read the main lines from the textarea.
        $lineOne = $cleanAddressLineList[0] ?? 'Address details not available';
        $lineTwo = null;
        $locationLine = '';
        $countryLine = '';

        if (count($cleanAddressLineList) >= 4) {
            $lineTwo = $cleanAddressLineList[1];
            $locationLine = $cleanAddressLineList[2];
            $countryLine = strtoupper((string) $cleanAddressLineList[3]);
        }

        if (count($cleanAddressLineList) === 3) {
            $locationLine = $cleanAddressLineList[1];
            $countryLine = strtoupper((string) $cleanAddressLineList[2]);
        }

        if (count($cleanAddressLineList) === 2) {
            $locationLine = $cleanAddressLineList[1];
        }

        // Start with default location values.
        $cityName = 'Not Available';
        $stateName = 'Not Available';
        $postalCode = '000000';

        if ($locationLine !== '') {
            $locationParts = explode(',', $locationLine, 2);

            if (count($locationParts) === 2) {
                $cityCandidate = trim($locationParts[0]);
                $statePostalText = trim($locationParts[1]);
                $statePostalPartList = preg_split('/\s+/', $statePostalText) ?: [];

                if ($cityCandidate !== '') {
                    $cityName = $cityCandidate;
                }

                if (count($statePostalPartList) > 1) {
                    $postalCandidate = (string) array_pop($statePostalPartList);
                    $stateCandidate = trim(implode(' ', $statePostalPartList));

                    if ($stateCandidate !== '') {
                        $stateName = $stateCandidate;
                    }

                    if ($postalCandidate !== '') {
                        $postalCode = $postalCandidate;
                    }
                }

                if (count($statePostalPartList) === 1) {
                    $stateCandidate = trim((string) $statePostalPartList[0]);

                    if ($stateCandidate !== '') {
                        $stateName = $stateCandidate;
                    }
                }
            }

            if (count($locationParts) !== 2) {
                $cityCandidate = trim($locationLine);

                if ($cityCandidate !== '') {
                    $cityName = $cityCandidate;
                }
            }
        }

        // Start with India as the default country code.
        $countryCode = 'IN';

        if ($countryLine === 'US' || str_contains($countryLine, 'UNITED STATES')) {
            $countryCode = 'US';
        }

        if ($countryLine === 'UK' || $countryLine === 'GB' || str_contains($countryLine, 'UNITED KINGDOM')) {
            $countryCode = 'GB';
        }

        if ($countryLine === 'IN' || str_contains($countryLine, 'INDIA')) {
            $countryCode = 'IN';
        }

        // Build the final stored address values.
        $shippingAddressData = [];
        $shippingAddressData['address_type'] = 'shipping';
        $shippingAddressData['contact_name'] = $customerName;
        $shippingAddressData['company_name'] = $companyName;
        $shippingAddressData['email'] = $customerEmail;
        $shippingAddressData['phone'] = $customerPhone;
        $shippingAddressData['gstin'] = null;
        $shippingAddressData['line1'] = $lineOne;
        $shippingAddressData['line2'] = $lineTwo;
        $shippingAddressData['landmark'] = null;
        $shippingAddressData['city'] = $cityName;
        $shippingAddressData['state'] = $stateName;
        $shippingAddressData['postal_code'] = $postalCode;
        $shippingAddressData['country_code'] = $countryCode;

        return $shippingAddressData;
    }

    // Save the shipment values used on the order page.
    private function saveShipmentDetails(Order $savedOrder, string $selectedStageLabel, array $orderData): void
    {
        // Load the current shipment row for the order.
        $savedShipmentRow = DB::table('shipments')
            ->where('order_id', $savedOrder->id)
            ->orderByDesc('id')
            ->first();

        // Read the submitted tracking values.
        $trackingNumber = trim((string) ($orderData['tracking_number'] ?? ''));
        $trackingUrl = trim((string) ($orderData['tracking_url'] ?? ''));

        // Decide whether shipment data should be saved.
        $shouldSaveShipment = false;

        if ($savedShipmentRow) {
            $shouldSaveShipment = true;
        }

        if ($trackingNumber !== '') {
            $shouldSaveShipment = true;
        }

        if ($trackingUrl !== '') {
            $shouldSaveShipment = true;
        }

        if ($selectedStageLabel === 'Dispatched') {
            $shouldSaveShipment = true;
        }

        if ($selectedStageLabel === 'Delivered') {
            $shouldSaveShipment = true;
        }

        if ($selectedStageLabel === 'Payment Received (COD)') {
            $shouldSaveShipment = true;
        }

        if (! $shouldSaveShipment) {
            return;
        }

        // Start with the pending shipment status.
        $shipmentStatus = 'pending';

        if ($selectedStageLabel === 'Dispatched') {
            $shipmentStatus = 'shipped';
        }

        if ($selectedStageLabel === 'Delivered' || $selectedStageLabel === 'Payment Received (COD)') {
            $shipmentStatus = 'delivered';
        }

        // Prepare the shipment times.
        $shippedAt = $savedShipmentRow->shipped_at ?? null;
        $deliveredAt = $savedShipmentRow->delivered_at ?? null;

        if ($shipmentStatus === 'pending') {
            $shippedAt = null;
            $deliveredAt = null;
        }

        if ($shipmentStatus === 'shipped') {
            if (! $shippedAt) {
                $shippedAt = now();
            }

            $deliveredAt = null;
        }

        if ($shipmentStatus === 'delivered') {
            if (! $shippedAt) {
                $shippedAt = now();
            }

            if (! $deliveredAt) {
                $deliveredAt = now();
            }
        }

        // Build the shipment number used for this order.
        $shipmentNumber = 'SHIP-' . str_pad((string) $savedOrder->id, 5, '0', STR_PAD_LEFT);

        if ($savedShipmentRow?->shipment_number) {
            $shipmentNumber = $savedShipmentRow->shipment_number;
        }

        // Build the final shipment values.
        $shipmentData = [];
        $shipmentData['order_id'] = $savedOrder->id;
        $shipmentData['shipping_address_id'] = $savedOrder->shippingAddress?->id;
        $shipmentData['shipment_number'] = $shipmentNumber;
        $shipmentData['carrier'] = $savedShipmentRow->carrier ?? 'Manual Entry';
        $shipmentData['tracking_number'] = $trackingNumber !== '' ? $trackingNumber : ($savedShipmentRow->tracking_number ?? null);
        $shipmentData['tracking_url'] = $trackingUrl !== '' ? $trackingUrl : ($savedShipmentRow->tracking_url ?? null);
        $shipmentData['status'] = $shipmentStatus;
        $shipmentData['shipped_at'] = $shippedAt;
        $shipmentData['delivered_at'] = $deliveredAt;
        $shipmentData['notes'] = $savedShipmentRow->notes ?? null;
        $shipmentData['updated_at'] = now();

        // Update the saved shipment row when it exists.
        if ($savedShipmentRow) {
            DB::table('shipments')
                ->where('id', $savedShipmentRow->id)
                ->update($shipmentData);
        }

        // Create a new shipment row when needed.
        if (! $savedShipmentRow) {
            $shipmentData['created_at'] = now();

            DB::table('shipments')->insert($shipmentData);
        }
    }

    // Save the payment values used on the order page.
    private function savePaymentDetails(Order $savedOrder, string $selectedStageLabel): void
    {
        // Load the current payment row for the order.
        $savedPaymentRow = DB::table('payments')
            ->where('order_id', $savedOrder->id)
            ->orderByDesc('id')
            ->first();

        // Decide whether payment data should be saved.
        $shouldSavePayment = false;

        if ($savedPaymentRow) {
            $shouldSavePayment = true;
        }

        if ($selectedStageLabel === 'Payment Received (Prepaid)') {
            $shouldSavePayment = true;
        }

        if ($selectedStageLabel === 'Payment Received (COD)') {
            $shouldSavePayment = true;
        }

        if (! $shouldSavePayment) {
            return;
        }

        // Start with the current payment values.
        $paymentMethod = $savedPaymentRow->method ?? 'prepaid';
        $paymentStatus = $savedPaymentRow->status ?? 'pending';
        $paidAt = $savedPaymentRow->paid_at ?? null;

        if ($selectedStageLabel === 'Payment Received (Prepaid)') {
            $paymentMethod = 'prepaid';
            $paymentStatus = 'paid';

            if (! $paidAt) {
                $paidAt = now();
            }
        }

        if ($selectedStageLabel === 'Payment Received (COD)') {
            $paymentMethod = 'cod';
            $paymentStatus = 'paid';

            if (! $paidAt) {
                $paidAt = now();
            }
        }

        if ($selectedStageLabel === 'Order Received' && $savedPaymentRow) {
            $paymentStatus = 'pending';
            $paidAt = null;
        }

        // Build the payment number used for this order.
        $paymentNumber = 'PAY-' . str_pad((string) $savedOrder->id, 5, '0', STR_PAD_LEFT);

        if ($savedPaymentRow?->payment_number) {
            $paymentNumber = $savedPaymentRow->payment_number;
        }

        // Build the final payment values.
        $paymentData = [];
        $paymentData['order_id'] = $savedOrder->id;
        $paymentData['user_id'] = $savedOrder->placed_by_user_id;
        $paymentData['payment_number'] = $paymentNumber;
        $paymentData['provider'] = $savedPaymentRow->provider ?? 'manual';
        $paymentData['provider_reference'] = $savedPaymentRow->provider_reference ?? null;
        $paymentData['method'] = $paymentMethod;
        $paymentData['status'] = $paymentStatus;
        $paymentData['currency'] = $savedOrder->currency ?: 'INR';
        $paymentData['amount'] = $savedOrder->total_amount;
        $paymentData['paid_at'] = $paidAt;
        $paymentData['notes'] = $savedPaymentRow->notes ?? null;
        $paymentData['updated_at'] = now();

        // Update the saved payment row when it exists.
        if ($savedPaymentRow) {
            DB::table('payments')
                ->where('id', $savedPaymentRow->id)
                ->update($paymentData);
        }

        // Create a new payment row when needed.
        if (! $savedPaymentRow) {
            $paymentData['created_at'] = now();

            DB::table('payments')->insert($paymentData);
        }
    }

    // Save the selected stage shown on the order page.
    private function saveOrderStageHistory(Order $savedOrder, string $previousStageLabel, string $selectedStageLabel): void
    {
        // Skip a new history row when the stage is unchanged.
        if ($previousStageLabel === $selectedStageLabel) {
            return;
        }

        // Save the latest stage selection for this order.
        DB::table('order_status_history')->insert([
            'order_id' => $savedOrder->id,
            'from_status' => $previousStageLabel,
            'to_status' => $selectedStageLabel,
            'changed_by_user_id' => auth()->id(),
            'remarks' => null,
            'created_at' => now(),
        ]);
    }
}
