<?php

namespace App\Http\Controllers\Proforma;

use App\Http\Controllers\Controller;
use App\Http\Requests\Proforma\SubmitProformaRequest;
use App\Services\Invoice\InvoiceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ProformaInvoiceController extends Controller
{
    // this method will return the view with form to submit a new PI request, and the form will be pre-filled with the selected product if product_id is provided in the query string.
    public function showRequestPage(Request $request, InvoiceService $invoiceService): View
    {
        $selectedProductId = decrypt_url_value($request->query('product_id'));
        $selectedProductId = $selectedProductId === null ? null : (int) $selectedProductId;

        try {
            // Step 1: load the PI request page with visible products and allowed companies.
            $pageData = $invoiceService->loadFormPageData($request->user(), 'proforma', $selectedProductId);

            return view('information.pi-quotation', $pageData);
        } catch (Throwable $exception) {
            Log::error('Failed to load PI request page.', ['error' => $exception->getMessage()]);

            return $this->viewWithError('information.pi-quotation', [
                'products' => collect(),
                'clientCompanies' => collect(),
                'prefilledProductId' => $selectedProductId,
            ], $exception, 'Unable to load PI request form.');
        }
    }

    public function submitRequest(SubmitProformaRequest $request, InvoiceService $invoiceService): RedirectResponse
    {
        try {
            // Step 1: validate the submitted PI request details.
            $requestData = $request->validated();
            $signedInUser = $request->user();

            // Step 2: prepare item rows and calculate the final request totals.
            $requestedItems = $invoiceService->prepareInvoiceItems($requestData, $signedInUser);
            $requestTotals = $invoiceService->calculateInvoiceTotals($requestedItems);
            $requestNumber = $this->createRequestNumber();

            // Step 3: save the PI request for later internal review.
            $invoiceService->saveProformaInvoice(
                $requestData,
                $signedInUser,
                $requestedItems,
                $requestTotals,
                $requestNumber,
                $request->session()->getId(),
            );

            // Step 4: store a simple activity log for the submitted request.
            $invoiceService->logProformaSubmission(
                $signedInUser,
                $request->session()->getId(),
                $request->path(),
                $requestNumber,
            );

            return redirect()->route('pi-quotation.generate')
                ->with('status', "PI request {$requestNumber} submitted successfully. Our team will review it and issue the final PI after verification.");
        } catch (Throwable $exception) {
            Log::error('Failed to submit PI request.', ['error' => $exception->getMessage()]);

            return $this->redirectBackWithError($exception, 'Unable to submit PI request.');
        }
    }

    protected function createRequestNumber(): string
    {
        $datePart = now()->format('YmdHis');
        $randomPart = str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);

        return 'PI-REQ-'.$datePart.'-'.$randomPart;
    }
}
