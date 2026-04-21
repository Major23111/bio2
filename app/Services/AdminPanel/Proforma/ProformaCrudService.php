<?php

namespace App\Services\AdminPanel\Proforma;

use App\Mail\Proforma\ProformaInvoicePdfEmail;
use App\Models\Proforma\ProformaInvoice;
use App\Models\Proforma\ProformaInvoiceItem;
use App\Models\Product\Product;
use App\Models\Product\ProductVariant;
use App\Services\Notification\Providers\BrevoEmailProvider;
use App\Services\Notification\Providers\LogEmailProvider;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

class ProformaCrudService
{
    // Get all proforma invoices for admin list page.
    public function getAllProformasForAdminList(): Collection
    {
        $allProformas = ProformaInvoice::with(['creator'])
            ->orderBy('created_at', 'desc')
            ->get();

        $proformasList = [];

        foreach ($allProformas as $proforma) {
            $creatorName  = $proforma->creator?->name ?? 'Admin';
            $targetName   = $proforma->target_name ?? 'N/A';
            $status       = $proforma->status ?? 'draft';
            $totalAmount  = $proforma->total_amount ?? 0;
            $targetEmail  = $proforma->target_email ?? '';

            $proformasList[] = [
                'id'          => $proforma->id,
                'piNumber'    => $proforma->pi_number,
                'creatorName' => $creatorName,
                'targetName'  => $targetName,
                'targetEmail' => $targetEmail,
                'status'      => $status,
                'totalAmount' => $totalAmount,
                'createdDate' => $proforma->created_at,
            ];
        }

        return collect($proformasList);
    }

    // Get all active product variants for selection in PI creation.
    public function getProductsForSelection(): Collection
    {
        $products = Product::with(['category', 'variants.prices'])
            ->where('is_active', true)
            ->get();

        $selectionList = [];

        foreach ($products as $product) {
            $categoryName = $product->category?->name ?? 'General';
            
            foreach ($product->variants as $variant) {
                if (!$variant->is_active) continue;

                // Find the first active price
                $priceRow = $variant->prices->where('is_active', true)->first();
                $rate = $priceRow?->amount ?? 0;
                $gst  = $priceRow?->gst_rate ?? $product->gst_rate ?? 18;

                $selectionList[] = [
                    'id'           => $product->id,
                    'variantId'    => $variant->id,
                    'name'         => $product->name,
                    'category'     => $categoryName,
                    'sku'          => $variant->sku ?? $product->sku,
                    'catNo'        => $variant->catalog_number ?? '',
                    'packSize'     => $variant->variant_name ?? 'Unit',
                    'rate'         => (float) $rate,
                    'gst'          => (float) $gst,
                    // Search string for easy filtering
                    'searchString' => strtolower($product->name . ' ' . ($variant->catalog_number ?? '') . ' ' . ($variant->sku ?? '') . ' ' . $categoryName)
                ];
            }
        }

        return collect($selectionList);
    }

    // Get one proforma with all fields and its line items for the edit form.
    public function getProformaForView(int $proformaId): ?array
    {
        $proforma = ProformaInvoice::with(['items'])->find($proformaId);

        if (!$proforma) {
            return null;
        }

        // Build a simple items array that the JS table can consume.
        $itemsList = [];
        foreach ($proforma->items as $item) {
            $itemsList[] = [
                'productId'   => $item->product_id,
                'variantId'   => $item->product_variant_id,
                'catNo'       => $item->sku,
                'productName' => $item->product_name,
                'packSize'    => $item->variant_name,
                'qty'         => $item->quantity,
                'rate'        => $item->unit_price,
                'gst'         => $item->gst_rate,
            ];
        }

        return [
            'id'              => $proforma->id,
            'piNumber'        => $proforma->pi_number,
            'piDate'          => $proforma->pi_date,
            'sellerStateCode' => $proforma->seller_state_code,
            'sellerGstin'     => $proforma->seller_gstin,
            'billingAddress'  => $proforma->billing_address,
            'shippingAddress' => $proforma->shipping_address,
            'targetName'      => $proforma->target_name,
            'targetEmail'     => $proforma->target_email,
            'customerGstin'   => $proforma->customer_gstin,
            'targetPhone'     => $proforma->target_phone,
            'status'          => $proforma->status,
            'subtotal'        => $proforma->subtotal,
            'taxAmount'       => $proforma->tax_amount,
            'freightCharges'  => $proforma->freight_charges,
            'totalAmount'     => $proforma->total_amount,
            'terms'           => $proforma->terms,
            'items'           => $itemsList,
        ];
    }

    // Create a new proforma invoice from the admin create form.
    public function createProforma(array $proformaData): int
    {
        // Decode the product rows submitted as JSON from the form.
        $itemRows = json_decode($proformaData['items_json'] ?? '[]', true) ?? [];

        // Calculate subtotal and tax from all product rows.
        $subtotal  = 0;
        $taxAmount = 0;
        foreach ($itemRows as $item) {
            $lineSubtotal  = $item['qty'] * $item['rate'];
            $lineTax       = $lineSubtotal * $item['gst'] / 100;
            $subtotal     += $lineSubtotal;
            $taxAmount    += $lineTax;
        }

        // Calculate freight tax and grand total.
        $freightCharges = (float) ($proformaData['freight_charges'] ?? 0);
        $freightTax     = $freightCharges * 0.18;
        $grandTotal     = round($subtotal + $taxAmount + $freightCharges + $freightTax);

        // Prepare the main PI header values.
        $proformaHeaderData = [
            'pi_number'        => $proformaData['pi_number'],
            'pi_date'          => $proformaData['pi_date'] ?? null,
            'requester_type'   => 'admin',
            'created_by_user_id' => auth()->id(),
            'target_name'      => $proformaData['contact_person'] ?? null,
            'target_email'     => $proformaData['target_email'] ?? null,
            'target_phone'     => $proformaData['target_phone'] ?? null,
            'status'           => $proformaData['status'] ?? 'draft',
            'currency'         => 'INR',
            'subtotal'         => $subtotal,
            'tax_amount'       => $taxAmount,
            'discount_amount'  => 0,
            'price_after_gst'  => $subtotal + $taxAmount,
            'total_amount'     => $grandTotal,
            'billing_address'  => $proformaData['billing_address'] ?? null,
            'shipping_address' => $proformaData['shipping_address'] ?? null,
            'customer_gstin'   => $proformaData['customer_gstin'] ?? null,
            'seller_state_code' => $proformaData['seller_state_code'] ?? null,
            'seller_gstin'     => $proformaData['seller_gstin'] ?? null,
            'freight_charges'  => $freightCharges,
            'freight_tax_amount' => $freightTax,
            'terms'            => $proformaData['terms'] ?? null,
        ];

        // Save the main PI header record.
        $newProforma = ProformaInvoice::create($proformaHeaderData);

        // Save each product row as a line item.
        $this->saveProformaLineItems($newProforma->id, $itemRows);

        return $newProforma->id;
    }

    // Update an existing proforma invoice from the admin edit form.
    public function updateProforma(int $proformaId, array $proformaData): bool
    {
        $proforma = ProformaInvoice::find($proformaId);

        if (!$proforma) {
            return false;
        }

        // Decode the product rows submitted as JSON from the form.
        $itemRows = json_decode($proformaData['items_json'] ?? '[]', true) ?? [];

        // Recalculate totals from updated product rows.
        $subtotal  = 0;
        $taxAmount = 0;
        foreach ($itemRows as $item) {
            $lineSubtotal  = $item['qty'] * $item['rate'];
            $lineTax       = $lineSubtotal * $item['gst'] / 100;
            $subtotal     += $lineSubtotal;
            $taxAmount    += $lineTax;
        }

        $freightCharges = (float) ($proformaData['freight_charges'] ?? 0);
        $freightTax     = $freightCharges * 0.18;
        $grandTotal     = round($subtotal + $taxAmount + $freightCharges + $freightTax);

        // Prepare the PI header values.
        $proformaHeaderData = [
            'pi_number'        => $proformaData['pi_number'],
            'pi_date'          => $proformaData['pi_date'] ?? null,
            'target_name'      => $proformaData['contact_person'] ?? $proforma->target_name,
            'target_email'     => $proformaData['target_email'] ?? $proforma->target_email,
            'target_phone'     => $proformaData['target_phone'] ?? $proforma->target_phone,
            'status'           => $proformaData['status'] ?? $proforma->status,
            'subtotal'         => $subtotal,
            'tax_amount'       => $taxAmount,
            'price_after_gst'  => $subtotal + $taxAmount,
            'total_amount'     => $grandTotal,
            'billing_address'  => $proformaData['billing_address'] ?? null,
            'shipping_address' => $proformaData['shipping_address'] ?? null,
            'customer_gstin'   => $proformaData['customer_gstin'] ?? null,
            'seller_state_code' => $proformaData['seller_state_code'] ?? null,
            'seller_gstin'     => $proformaData['seller_gstin'] ?? null,
            'freight_charges'  => $freightCharges,
            'freight_tax_amount' => $freightTax,
            'terms'            => $proformaData['terms'] ?? null,
        ];

        // Update the PI header fields.
        $proforma->update($proformaHeaderData);

        // Remove old line items and re-save the updated ones.
        $proforma->items()->delete();
        $this->saveProformaLineItems($proforma->id, $itemRows);

        return true;
    }

    // Get one saved PI with all details needed for document actions.
    public function getProformaForDocument(int $proformaId): ?ProformaInvoice
    {
        $proforma = ProformaInvoice::query()
            ->with([
                'creator:id,name,email',
                'ownerUser:id,name,email',
                'ownerCompany:id,name,company_type',
                'targetCompany:id,name,company_type',
                'items' => function ($query): void {
                    $query->orderBy('id');
                },
            ])
            ->find($proformaId);

        return $proforma;
    }

    // Generate the PI PDF file for download.
    public function downloadProformaPdf(ProformaInvoice $proforma): Response
    {
        // Load the PI PDF template with the saved PI details.
        $pdfFile = Pdf::loadView('invoice.Proforma-invoice-pdf', [
            'proforma' => $proforma,
        ])->setPaper(
            config('invoice.pdf.paper', 'a4'),
            config('invoice.pdf.orientation', 'portrait'),
        );

        // Prepare a safe file name for browser download.
        $pdfFileName = trim((string) $proforma->pi_number);
        $pdfFileName = str_replace('\\', '-', $pdfFileName);
        $pdfFileName = str_replace('/', '-', $pdfFileName);
        $pdfFileName = $pdfFileName.'.pdf';

        // Return the generated PDF as a download.
        return $pdfFile->download($pdfFileName);
    }

    // Send the PI PDF to the selected customer email.
    public function sendProformaPdfEmail(ProformaInvoice $proforma): void
    {
        // Read the customer email and name from the saved PI.
        $customerEmail = trim((string) ($proforma->target_email ?? ''));
        $customerName = trim((string) ($proforma->target_name ?? 'Customer'));

        if ($customerEmail === '') {
            throw new RuntimeException('Customer email is required to send the Proforma Invoice.');
        }

        // Generate the PI PDF content for the email attachment.
        $pdfFile = Pdf::loadView('invoice.Proforma-invoice-pdf', [
            'proforma' => $proforma,
        ])->setPaper(
            config('invoice.pdf.paper', 'a4'),
            config('invoice.pdf.orientation', 'portrait'),
        );
        $pdfContent = $pdfFile->output();

        // Prepare a safe file name for the email attachment.
        $pdfFileName = trim((string) $proforma->pi_number);
        $pdfFileName = str_replace('\\', '-', $pdfFileName);
        $pdfFileName = str_replace('/', '-', $pdfFileName);
        $pdfFileName = $pdfFileName.'.pdf';

        // Build the PI email with the PDF attachment.
        $emailMessage = new ProformaInvoicePdfEmail($proforma, $pdfContent, $pdfFileName);
        $preparedEmail = $emailMessage->to($customerEmail, $customerName);

        // Pick the configured email provider for the send action.
        $providerName = strtolower(trim((string) config('common.email_notifications.provider', 'log')));

        if ($providerName === 'brevo') {
            $emailProvider = new BrevoEmailProvider();
        } elseif ($providerName === 'log') {
            $emailProvider = new LogEmailProvider();
        } else {
            throw new RuntimeException("Unsupported email provider [{$providerName}] configured for notifications.");
        }

        // Send the PI email through the selected provider.
        $emailProvider->send($preparedEmail);
    }

    // Save product line items for a proforma invoice.
    private function saveProformaLineItems(int $proformaId, array $itemRows): void
    {
        foreach ($itemRows as $item) {
            $productId = (int) ($item['productId'] ?? 0);
            $variantId = (int) ($item['variantId'] ?? 0);
            $productName = trim((string) ($item['productName'] ?? ''));
            $catalogNumber = trim((string) ($item['catNo'] ?? ''));

            // Resolve the product from the selected row details when ids are missing.
            if ($productId === 0 && $catalogNumber !== '') {
                $matchedVariant = ProductVariant::query()
                    ->where('catalog_number', $catalogNumber)
                    ->orWhere('sku', $catalogNumber)
                    ->first();

                if ($matchedVariant) {
                    $productId = (int) $matchedVariant->product_id;
                    $variantId = (int) $matchedVariant->id;
                }
            }

            // Resolve the product by product name when the catalog match is not available.
            if ($productId === 0 && $productName !== '') {
                $matchedProduct = Product::query()
                    ->where('name', $productName)
                    ->first();

                if ($matchedProduct) {
                    $productId = (int) $matchedProduct->id;
                }
            }

            // Stop the save when the row is not linked to a real product.
            if ($productId === 0) {
                throw new RuntimeException('Please select the product from the product list before saving the Proforma Invoice.');
            }

            $lineSubtotal      = $item['qty'] * $item['rate'];
            $lineTax           = $lineSubtotal * $item['gst'] / 100;
            $unitTax           = $item['rate'] * $item['gst'] / 100;
            $linePriceAfterGst = $lineSubtotal + $lineTax;

            // Prepare the PI line item values.
            $lineItemData = [
                'proforma_invoice_id'   => $proformaId,
                'product_id'            => $productId,
                'product_variant_id'    => $variantId > 0 ? $variantId : null,
                'product_name'          => $productName,
                'sku'                   => $catalogNumber,
                'variant_name'          => $item['packSize'] ?? null,
                'price_type'            => null,
                'currency'              => 'INR',
                'quantity'              => (int) $item['qty'],
                'unit_price'            => $item['rate'],
                'gst_rate'              => $item['gst'],
                'unit_tax_amount'       => $unitTax,
                'unit_price_after_gst'  => $item['rate'] + $unitTax,
                'discount_percent'      => 0,
                'unit_discount_amount'  => 0,
                'line_subtotal'         => $lineSubtotal,
                'line_tax_amount'       => $lineTax,
                'line_price_after_gst'  => $linePriceAfterGst,
                'line_discount_amount'  => 0,
                'line_total'            => $linePriceAfterGst,
            ];

            ProformaInvoiceItem::create($lineItemData);
        }
    }
}
