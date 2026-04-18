<?php

namespace App\Services\Invoice;

use App\Models\Authorization\Company;
use App\Models\Authorization\User;
use App\Models\Product\Product;
use App\Models\Product\UserActivityLog;
use App\Models\Proforma\ProformaInvoice;
use App\Models\Quotation\Quotation;
use App\Services\Authorization\DataVisibilityService;
use App\Services\Pricing\PriceService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class InvoiceService
{
    public function __construct(
        protected DataVisibilityService $dataVisibilityService,
        protected PriceService $priceService,
    ) {
    }

    // Load page data for invoice creation or proforma request.
    public function loadFormPageData(?User $user, string $documentType = 'quotation', ?int $prefilledProductId = null): array
    {
        $visibleProducts = $this->loadVisibleProducts($user);
        $clientCompanies = $this->loadClientCompanies($user);

        return [
            'products' => $visibleProducts,
            'clientCompanies' => $clientCompanies,
            'prefilledProductId' => $prefilledProductId,
        ];
    }

    // Validate and convert submitted rows into invoice items.
    public function prepareInvoiceItems(array $formData, ?User $user): array
    {
        $productIds = is_array($formData['product_id'] ?? null) ? $formData['product_id'] : [];
        $quantities = is_array($formData['quantity'] ?? null) ? $formData['quantity'] : [];
        $rowCount = max(count($productIds), count($quantities));
        $preparedItems = [];

        for ($rowIndex = 0; $rowIndex < $rowCount; $rowIndex++) {
            $productId = (int) ($productIds[$rowIndex] ?? 0);
            $quantity = (int) ($quantities[$rowIndex] ?? 0);

            if ($productId === 0 && $quantity === 0) {
                continue;
            }

            $product = $this->findVisibleProduct($user, $productId);

            if (! $product) {
                throw ValidationException::withMessages([
                    "product_id.$rowIndex" => 'The selected product is outside your visibility scope.',
                ]);
            }

            $priceData = $this->priceService->resolveProductPrice($productId, $user, $quantity);

            if (! $priceData) {
                throw ValidationException::withMessages([
                    "product_id.$rowIndex" => 'No visible price is configured for the selected product.',
                ]);
            }

            $this->validateItemQuantity($quantity, $priceData, $rowIndex);
            $preparedItems[] = $this->buildInvoiceItemData($product, $priceData, $quantity);
        }

        if ($preparedItems === []) {
            throw ValidationException::withMessages([
                'product_id' => 'Add at least one item.',
            ]);
        }

        return $preparedItems;
    }

    // Calculate totals from prepared invoice items.
    public function calculateInvoiceTotals(array $preparedItems): array
    {
        $subtotal = 0;
        $taxAmount = 0;
        $discountAmount = 0;
        $priceAfterGst = 0;
        $totalAmount = 0;

        foreach ($preparedItems as $item) {
            $subtotal += (float) $item['line_subtotal'];
            $taxAmount += (float) $item['line_tax_amount'];
            $discountAmount += (float) $item['line_discount_amount'];
            $priceAfterGst += (float) $item['line_price_after_gst'];
            $totalAmount += (float) $item['line_total'];
        }

        return [
            'currency' => (string) ($preparedItems[0]['currency'] ?? 'INR'),
            'subtotal' => round($subtotal, 2),
            'tax_amount' => round($taxAmount, 2),
            'discount_amount' => round($discountAmount, 2),
            'price_after_gst' => round($priceAfterGst, 2),
            'total_amount' => round($totalAmount, 2),
        ];
    }

    // Save a new quotation with its items.
    public function saveQuotation(
        array $formData,
        ?User $user,
        array $preparedItems,
        array $invoiceTotals,
        string $documentNumber,
        string $guestSessionId,
    ): Quotation {
        return DB::transaction(function () use ($formData, $user, $preparedItems, $invoiceTotals, $documentNumber, $guestSessionId): Quotation {
            $quotation = Quotation::query()->create([
                'quotation_number' => $documentNumber,
                'requester_type' => $user ? 'user' : 'guest',
                'created_by_user_id' => $user?->id,
                'owner_user_id' => $user?->id,
                'owner_company_id' => $user?->company_id,
                'target_type' => $formData['purpose'],
                'target_name' => $formData['customer_name'],
                'target_email' => $formData['customer_email'],
                'target_phone' => $formData['customer_phone'] ?: null,
                'target_company_id' => isset($formData['target_company_id']) ? (int) $formData['target_company_id'] : null,
                'status' => 'generated',
                'currency' => $invoiceTotals['currency'],
                'subtotal' => $invoiceTotals['subtotal'],
                'tax_amount' => $invoiceTotals['tax_amount'],
                'discount_amount' => $invoiceTotals['discount_amount'],
                'price_after_gst' => $invoiceTotals['price_after_gst'],
                'total_amount' => $invoiceTotals['total_amount'],
                'guest_session_id' => $user ? null : $guestSessionId,
                'notes' => $formData['notes'] ?: null,
            ]);

            foreach ($preparedItems as $item) {
                $quotation->items()->create($item);
            }

            return $this->loadQuotationWithRelations($quotation->id);
        });
    }

    // Save a new proforma invoice request with its items.
    public function saveProformaInvoice(
        array $formData,
        ?User $user,
        array $preparedItems,
        array $invoiceTotals,
        string $documentNumber,
        string $guestSessionId,
    ): void {
        DB::transaction(function () use ($formData, $user, $preparedItems, $invoiceTotals, $documentNumber, $guestSessionId): void {
            $proforma = ProformaInvoice::query()->create([
                'pi_number' => $documentNumber,
                'requester_type' => $user ? 'user' : 'guest',
                'created_by_user_id' => $user?->id,
                'owner_user_id' => $user?->id,
                'owner_company_id' => $user?->company_id,
                'target_type' => $formData['purpose'],
                'target_name' => $formData['customer_name'],
                'target_email' => $formData['customer_email'],
                'target_phone' => $formData['customer_phone'] ?: null,
                'target_company_id' => isset($formData['target_company_id']) ? (int) $formData['target_company_id'] : null,
                'status' => 'pending_review',
                'currency' => $invoiceTotals['currency'],
                'subtotal' => $invoiceTotals['subtotal'],
                'tax_amount' => $invoiceTotals['tax_amount'],
                'discount_amount' => $invoiceTotals['discount_amount'],
                'price_after_gst' => $invoiceTotals['price_after_gst'],
                'total_amount' => $invoiceTotals['total_amount'],
                'guest_session_id' => $user ? null : $guestSessionId,
                'notes' => $formData['notes'] ?: null,
            ]);

            foreach ($preparedItems as $item) {
                $proforma->items()->create($item);
            }
        });
    }

    // Log activity for submitted proforma requests.
    public function logProformaSubmission(?User $user, string $sessionId, string $path, string $documentNumber): void
    {
        UserActivityLog::query()->create([
            'session_id' => $sessionId,
            'user_id' => $user?->id,
            'user_type' => $user?->user_type ?: 'guest',
            'user_name' => $user?->name,
            'user_email' => $user?->email,
            'activity_type' => 'pi_request_submitted',
            'path' => $path,
            'payload' => ['request_reference' => $documentNumber],
            'created_at' => now(),
        ]);
    }

    // Download PDF for quotation.
    public function downloadQuotationPdf(Quotation $quotation): Response
    {
        $pdf = Pdf::loadView('invoice.quotation-pdf', [
            'quotation' => $quotation,
        ])->setPaper(
            config('invoice.pdf.paper', 'a4'),
            config('invoice.pdf.orientation', 'portrait'),
        );

        return $pdf->download($quotation->quotation_number.'.pdf');
    }

    // Load visible products for form page.
    protected function loadVisibleProducts(?User $user)
    {
        // Load products with variants eager-loaded in a single query (no N+1)
        $products = $this->dataVisibilityService->visibleProductQuery($user)
            ->with([
                'variants' => function ($query): void {
                    $query->where('is_active', true)->orderBy('id');
                },
            ])
            ->orderBy('products.name')
            ->get();

        if ($products->isEmpty()) {
            return $this->loadFallbackProducts();
        }

        // Process each product and attach pricing data
        foreach ($products as $product) {
            // Get the first active variant from the already-loaded collection
            $firstVariant = $product->variants->first();

            if ($firstVariant) {
                // Resolve pricing for this variant (single query, not per-product loop)
                $priceData = $this->priceService->resolveVariantPrice((int) $firstVariant->id, $user);
                
                if ($priceData) {
                    $product->visible_price = $priceData['amount'] ?? null;
                    $product->visible_currency = $priceData['currency'] ?? 'INR';
                    $product->visible_price_type = $priceData['price_type'] ?? null;
                    $product->gst_rate = $priceData['gst_rate'] ?? 0;
                    $product->tax_amount = $priceData['tax_amount'] ?? 0;
                    $product->price_after_gst = $priceData['price_after_gst'] ?? null;
                    $product->min_order_quantity = $priceData['min_order_quantity'] ?? 1;
                    $product->max_order_quantity = $priceData['max_order_quantity'] ?? null;
                    $product->lot_size = $priceData['lot_size'] ?? 1;
                    continue;
                }
            }

            // If no pricing found, use fallback defaults
            $product->sku = $product->sku ?: ($firstVariant?->sku ?? '');
            $product->visible_price = 0.0;
            $product->visible_currency = 'INR';
            $product->visible_price_type = 'manual_review';
            $product->gst_rate = 18.0;
            $product->tax_amount = 0.0;
            $product->price_after_gst = 0.0;
            $product->min_order_quantity = max(1, (int) ($firstVariant?->min_order_quantity ?? 1));
            $product->max_order_quantity = $firstVariant?->max_order_quantity;
            $product->lot_size = max(1, (int) ($firstVariant?->lot_size ?? 1));
        }

        return $products;
    }

    // Load fallback products when pricing is not ready.
    protected function loadFallbackProducts()
    {
        $products = Product::query()
            ->with([
                'variants' => function ($query): void {
                    $query->where('is_active', true)->orderBy('id');
                },
            ])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        foreach ($products as $product) {
            $firstVariant = $product->variants->first();

            $product->sku = $product->sku ?: ($firstVariant?->sku ?? '');
            $product->visible_price = 0.0;
            $product->visible_currency = 'INR';
            $product->visible_price_type = 'manual_review';
            $product->gst_rate = 18.0;
            $product->tax_amount = 0.0;
            $product->price_after_gst = 0.0;
            $product->min_order_quantity = max(1, (int) ($firstVariant?->min_order_quantity ?? 1));
            $product->max_order_quantity = $firstVariant?->max_order_quantity;
            $product->lot_size = max(1, (int) ($firstVariant?->lot_size ?? 1));
        }

        return $products->values();
    }

    // Load client companies for B2B users.
    protected function loadClientCompanies(?User $user)
    {
        if (! $user || ! $user->isB2b()) {
            return collect();
        }

        $companyIds = $this->dataVisibilityService->assignedClientCompanyIds($user);

        if ($user->company_id) {
            $companyIds[] = $user->company_id;
        }

        return Company::query()
            ->whereIn('id', array_unique($companyIds))
            ->orderBy('name')
            ->get();
    }

    // Find product within user's visibility scope.
    protected function findVisibleProduct(?User $user, int $productId): ?object
    {
        return $this->dataVisibilityService->visibleProductQuery($user)
            ->where('products.id', $productId)
            ->first();
    }

    // Build item row for invoice.
    protected function buildInvoiceItemData(object $product, array $priceData, int $quantity): array
    {
        $unitPrice = (float) ($priceData['amount'] ?? 0);
        $baseAmount = (float) ($priceData['base_amount'] ?? $unitPrice);
        $unitTaxAmount = (float) ($priceData['tax_amount'] ?? 0);
        $unitPriceAfterGst = (float) ($priceData['price_after_gst'] ?? 0);
        $unitDiscountAmount = round((float) ($priceData['discount_amount'] ?? 0), 2);

        $discountPercent = $baseAmount > 0 ? round(($unitDiscountAmount / $baseAmount) * 100, 2) : 0.00;
        $lineSubtotal = round($unitPrice * $quantity, 2);
        $lineTaxAmount = round($unitTaxAmount * $quantity, 2);
        $linePriceAfterGst = round($unitPriceAfterGst * $quantity, 2);
        $lineDiscountAmount = round($unitDiscountAmount * $quantity, 2);
        $lineTotal = $linePriceAfterGst;

        return [
            'product_id' => (int) $product->id,
            'product_variant_id' => $priceData['product_variant_id'] ?? null,
            'product_name' => (string) $product->name,
            'sku' => (string) ($priceData['variant_sku'] ?? $product->sku),
            'variant_name' => $priceData['variant_name'] ?? null,
            'price_type' => $priceData['price_type'] ?? null,
            'currency' => $priceData['currency'] ?? 'INR',
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'gst_rate' => (float) ($priceData['gst_rate'] ?? 0),
            'unit_tax_amount' => $unitTaxAmount,
            'unit_price_after_gst' => $unitPriceAfterGst,
            'discount_percent' => $discountPercent,
            'unit_discount_amount' => $unitDiscountAmount,
            'line_subtotal' => $lineSubtotal,
            'line_tax_amount' => $lineTaxAmount,
            'line_price_after_gst' => $linePriceAfterGst,
            'line_discount_amount' => $lineDiscountAmount,
            'line_total' => $lineTotal,
        ];
    }

    // Validate item quantity against pricing rules.
    protected function validateItemQuantity(int $quantity, array $priceData, int $rowIndex): void
    {
        $minimumQuantity = max(1, (int) ($priceData['min_order_quantity'] ?? 1));
        $maximumQuantity = $priceData['max_order_quantity'] ?? null;
        $lotSize = max(1, (int) ($priceData['lot_size'] ?? 1));

        if ($quantity < $minimumQuantity) {
            throw ValidationException::withMessages([
                "quantity.$rowIndex" => "Quantity for item ".($rowIndex + 1)." must be at least {$minimumQuantity}.",
            ]);
        }

        if ($maximumQuantity !== null && $quantity > (int) $maximumQuantity) {
            throw ValidationException::withMessages([
                "quantity.$rowIndex" => "Quantity for item ".($rowIndex + 1)." must not exceed {$maximumQuantity}.",
            ]);
        }

        if ($lotSize > 1 && $quantity % $lotSize !== 0) {
            throw ValidationException::withMessages([
                "quantity.$rowIndex" => "Quantity for item ".($rowIndex + 1)." must be in multiples of {$lotSize}.",
            ]);
        }
    }

    // Load quotation with all relations for PDF generation.
    protected function loadQuotationWithRelations(int $quotationId): Quotation
    {
        return Quotation::query()
            ->with([
                'creator:id,name,email',
                'ownerUser:id,name,email',
                'ownerCompany:id,name,company_type',
                'targetCompany:id,name,company_type',
                'items' => function ($query): void {
                    $query->orderBy('id');
                },
            ])
            ->findOrFail($quotationId);
    }
}
