<?php

namespace App\Services\AdminPanel\Proforma;

use App\Models\Proforma\ProformaInvoice;
use App\Models\Proforma\ProformaInvoiceItem;
use Illuminate\Support\Collection;

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

        // Save the main PI header record.
        $newProforma = ProformaInvoice::create([
            'pi_number'        => $proformaData['pi_number'],
            'pi_date'          => $proformaData['pi_date'] ?? null,
            'requester_type'   => 'admin',
            'created_by_user_id' => auth()->id(),
            'target_name'      => $proformaData['contact_person'] ?? null,
            'target_email'     => $proformaData['target_email'] ?? null,
            'target_phone'     => $proformaData['target_phone'] ?? null,
            'status'           => $proformaData['status'] ?? 'draft',
            'currency'         => 'INR',
            'billing_address'  => $proformaData['billing_address'] ?? null,
            'shipping_address' => $proformaData['shipping_address'] ?? null,
            'customer_gstin'   => $proformaData['customer_gstin'] ?? null,
            'seller_state_code' => $proformaData['seller_state_code'] ?? null,
            'seller_gstin'     => $proformaData['seller_gstin'] ?? null,
            'subtotal'         => $subtotal,
            'tax_amount'       => $taxAmount,
            'discount_amount'  => 0,
            'freight_charges'  => $freightCharges,
            'freight_tax_amount' => $freightTax,
            'price_after_gst'  => $subtotal + $taxAmount,
            'total_amount'     => $grandTotal,
            'terms'            => $proformaData['terms'] ?? null,
        ]);

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

        // Update the PI header fields.
        $proforma->update([
            'pi_number'        => $proformaData['pi_number'],
            'pi_date'          => $proformaData['pi_date'] ?? null,
            'target_name'      => $proformaData['contact_person'] ?? $proforma->target_name,
            'target_email'     => $proformaData['target_email'] ?? $proforma->target_email,
            'target_phone'     => $proformaData['target_phone'] ?? $proforma->target_phone,
            'status'           => $proformaData['status'] ?? $proforma->status,
            'billing_address'  => $proformaData['billing_address'] ?? null,
            'shipping_address' => $proformaData['shipping_address'] ?? null,
            'customer_gstin'   => $proformaData['customer_gstin'] ?? null,
            'seller_state_code' => $proformaData['seller_state_code'] ?? null,
            'seller_gstin'     => $proformaData['seller_gstin'] ?? null,
            'subtotal'         => $subtotal,
            'tax_amount'       => $taxAmount,
            'freight_charges'  => $freightCharges,
            'freight_tax_amount' => $freightTax,
            'price_after_gst'  => $subtotal + $taxAmount,
            'total_amount'     => $grandTotal,
            'terms'            => $proformaData['terms'] ?? null,
        ]);

        // Remove old line items and re-save the updated ones.
        $proforma->items()->delete();
        $this->saveProformaLineItems($proforma->id, $itemRows);

        return true;
    }

    // Save product line items for a proforma invoice.
    private function saveProformaLineItems(int $proformaId, array $itemRows): void
    {
        foreach ($itemRows as $item) {
            $lineSubtotal      = $item['qty'] * $item['rate'];
            $lineTax           = $lineSubtotal * $item['gst'] / 100;
            $unitTax           = $item['rate'] * $item['gst'] / 100;
            $linePriceAfterGst = $lineSubtotal + $lineTax;

            ProformaInvoiceItem::create([
                'proforma_invoice_id'   => $proformaId,
                'product_id'            => null,
                'product_name'          => $item['productName'],
                'sku'                   => $item['catNo'] ?? '',
                'variant_name'          => $item['packSize'] ?? null,
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
            ]);
        }
    }
}
