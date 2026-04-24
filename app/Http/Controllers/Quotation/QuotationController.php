<?php

namespace App\Http\Controllers\Quotation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Quotation\GenerateQuotationRequest;
use App\Services\Invoice\InvoiceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class QuotationController extends Controller
{
    public function showCreatePage(Request $request, InvoiceService $invoiceService): View
    {
        $selectedProductId = decrypt_url_value($request->query('product_id'));
        $selectedProductId = $selectedProductId === null ? null : (int) $selectedProductId;

        try {
            // Step 1: load the quotation form with visible products and allowed companies.
            $pageData = $invoiceService->loadFormPageData($request->user(), 'quotation', $selectedProductId);

            return view('information.generate-quotation', $pageData);
        } catch (Throwable $exception) {
            Log::error('Failed to load quotation page.', ['error' => $exception->getMessage()]);

            return $this->viewWithError('information.generate-quotation', [
                'products' => collect(),
                'clientCompanies' => collect(),
                'prefilledProductId' => $selectedProductId,
            ], $exception, 'Unable to load quotation form.');
        }
    }

    public function generate(GenerateQuotationRequest $request, InvoiceService $invoiceService): Response|RedirectResponse
    {
        try {
            // Step 1: validate the submitted quotation details.
            $quotationData = $request->validated();
            $signedInUser = $request->user();

            // Step 2: prepare item rows and calculate the final totals.
            $quotationItems = $invoiceService->prepareInvoiceItems($quotationData, $signedInUser);
            $quotationTotals = $invoiceService->calculateInvoiceTotals($quotationItems);
            $quotationNumber = $this->createQuotationNumber();

            // Step 3: save the quotation and load the final PDF record.
            $quotation = $invoiceService->saveQuotation(
                $quotationData,
                $signedInUser,
                $quotationItems,
                $quotationTotals,
                $quotationNumber,
                $request->session()->getId(),
            );

            // Step 4: return the quotation PDF file.
            return $invoiceService->downloadQuotationPdf($quotation);
        } catch (Throwable $exception) {
            Log::error('Failed to create quotation.', ['error' => $exception->getMessage()]);

            return $this->redirectBackWithError($exception, 'Unable to generate quotation.');
        }
    }

    protected function createQuotationNumber(): string
    {
        $datePart = now()->format('YmdHis');
        $randomPart = str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);

        return 'QT-'.$datePart.'-'.$randomPart;
    }
}
