<?php

namespace App\Http\Controllers\AdminPanel\Proforma;

use App\Http\Controllers\Controller;
use App\Services\AdminPanel\Proforma\ProformaCrudService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ProformaCrudController extends Controller
{
    public function __construct(protected ProformaCrudService $proformaCrudService)
    {
    }

    // Show the list of all proforma invoices.
    public function index(): View
    {
        try {
            // Fetch all PI records for the admin list.
            $proformas = $this->proformaCrudService->getAllProformasForAdminList();

            return view('admin.pi-quotation', [
                'proformas' => $proformas,
            ]);
        } catch (Throwable $exception) {
            return view('admin.pi-quotation', [
                'proformas' => collect([]),
            ]);
        }
    }

    // Show the blank create form for a new PI.
    public function create(): View
    {
        try {
            $products = $this->proformaCrudService->getProductsForSelection();

            return view('admin.pi-quotation-create', [
                'products' => $products,
            ]);
        } catch (Throwable $exception) {
            return view('admin.pi-quotation-create', [
                'products' => collect([]),
            ]);
        }
    }

    // Save a new PI from the create form.
    public function store(Request $request): Response|RedirectResponse
    {
        $createdProformaId = null;

        try {
            // Validate all submitted PI form fields.
            $validated = $request->validate([
                'pi_number'         => 'required|string|max:50',
                'pi_date'           => 'nullable|date',
                'seller_state_code' => 'nullable|string|max:100',
                'seller_gstin'      => 'nullable|string|max:20',
                'billing_address'   => 'nullable|string',
                'shipping_address'  => 'nullable|string',
                'contact_person'    => 'nullable|string|max:255',
                'target_email'      => 'nullable|email|max:255',
                'customer_gstin'    => 'nullable|string|max:20',
                'target_phone'      => 'nullable|string|max:20',
                'status'            => 'required|string|max:30',
                'freight_charges'   => 'nullable|numeric|min:0',
                'terms'             => 'nullable|string',
                'items_json'        => 'nullable|string',
                'submit_action'     => 'nullable|string|in:save,download_pdf,send_email',
            ]);

            // Read the requested page action from the submitted form.
            $submitAction = $validated['submit_action'] ?? 'save';
            $targetEmail = trim((string) ($validated['target_email'] ?? ''));

            // Stop the email flow when the customer email is not provided.
            if ($submitAction === 'send_email' && $targetEmail === '') {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Email address is required to send Proforma Invoice.');
            }

            // Create the PI and its line items.
            $createdProformaId = $this->proformaCrudService->createProforma($validated);

            // Load the saved PI when the next action needs the final record.
            if ($submitAction === 'download_pdf' || $submitAction === 'send_email') {
                $savedProforma = $this->proformaCrudService->getProformaForDocument($createdProformaId);

                if (! $savedProforma) {
                    return redirect()->route('admin.pi-quotation.index')
                        ->with('error', 'Proforma Invoice was saved, but the document could not be prepared.');
                }

                // Return the generated PI PDF file.
                if ($submitAction === 'download_pdf') {
                    return $this->proformaCrudService->downloadProformaPdf($savedProforma);
                }

                // Send the PI PDF email to the customer.
                $this->proformaCrudService->sendProformaPdfEmail($savedProforma);

                return redirect()->route('admin.pi-quotation.edit', $createdProformaId)
                    ->with('success', 'Proforma Invoice created and sent successfully.');
            }

            return redirect()->route('admin.pi-quotation.index')
                ->with('success', 'Proforma Invoice created successfully.');
        } catch (Throwable $exception) {
            Log::error('Failed to create admin proforma invoice.', [
                'error' => $exception->getMessage(),
            ]);

            $errorMessage = 'Failed to create Proforma Invoice. Please try again.';

            if ($exception instanceof \RuntimeException) {
                $errorMessage = $exception->getMessage();
            }

            if ($createdProformaId) {
                return redirect()->route('admin.pi-quotation.edit', $createdProformaId)
                    ->with('error', 'Proforma Invoice was saved, but the requested action could not be completed.');
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $errorMessage);
        }
    }

    // Show the edit form for an existing PI (reuses the create view with prefilled data).
    public function show(int $proformaId): View
    {
        try {
            // Fetch the PI details for prefilling the form.
            $proforma = $this->proformaCrudService->getProformaForView($proformaId);
            $products = $this->proformaCrudService->getProductsForSelection();

            if (!$proforma) {
                abort(404);
            }

            // Reuse the create view; presence of $proforma signals edit mode.
            return view('admin.pi-quotation-create', [
                'proforma' => $proforma,
                'products' => $products,
            ]);
        } catch (Throwable $exception) {
            abort(500);
        }
    }

    // Save updates to an existing PI from the edit form.
    public function update(Request $request, int $proformaId): Response|RedirectResponse
    {
        try {
            // Validate all submitted PI form fields.
            $validated = $request->validate([
                'pi_number'         => 'required|string|max:50',
                'pi_date'           => 'nullable|date',
                'seller_state_code' => 'nullable|string|max:100',
                'seller_gstin'      => 'nullable|string|max:20',
                'billing_address'   => 'nullable|string',
                'shipping_address'  => 'nullable|string',
                'contact_person'    => 'nullable|string|max:255',
                'target_email'      => 'nullable|email|max:255',
                'customer_gstin'    => 'nullable|string|max:20',
                'target_phone'      => 'nullable|string|max:20',
                'status'            => 'required|string|max:30',
                'freight_charges'   => 'nullable|numeric|min:0',
                'terms'             => 'nullable|string',
                'items_json'        => 'nullable|string',
                'submit_action'     => 'nullable|string|in:save,download_pdf,send_email',
            ]);

            // Read the requested page action from the submitted form.
            $submitAction = $validated['submit_action'] ?? 'save';
            $targetEmail = trim((string) ($validated['target_email'] ?? ''));

            // Stop the email flow when the customer email is not provided.
            if ($submitAction === 'send_email' && $targetEmail === '') {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Email address is required to send Proforma Invoice.');
            }

            // Update the PI record and its line items.
            $isUpdated = $this->proformaCrudService->updateProforma($proformaId, $validated);

            if (!$isUpdated) {
                return redirect()->back()
                    ->with('error', 'Proforma Invoice not found.');
            }

            // Load the updated PI when the next action needs the final record.
            if ($submitAction === 'download_pdf' || $submitAction === 'send_email') {
                $savedProforma = $this->proformaCrudService->getProformaForDocument($proformaId);

                if (! $savedProforma) {
                    return redirect()->route('admin.pi-quotation.edit', $proformaId)
                        ->with('error', 'Proforma Invoice was updated, but the document could not be prepared.');
                }

                // Return the generated PI PDF file.
                if ($submitAction === 'download_pdf') {
                    return $this->proformaCrudService->downloadProformaPdf($savedProforma);
                }

                // Send the PI PDF email to the customer.
                $this->proformaCrudService->sendProformaPdfEmail($savedProforma);

                return redirect()->route('admin.pi-quotation.edit', $proformaId)
                    ->with('success', 'Proforma Invoice updated and sent successfully.');
            }

            return redirect()->route('admin.pi-quotation.index')
                ->with('success', 'Proforma Invoice updated successfully.');
        } catch (Throwable $exception) {
            Log::error('Failed to update admin proforma invoice.', [
                'proforma_id' => $proformaId,
                'error' => $exception->getMessage(),
            ]);

            $errorMessage = 'Failed to update Proforma Invoice. Please try again.';

            if ($exception instanceof \RuntimeException) {
                $errorMessage = $exception->getMessage();
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $errorMessage);
        }
    }
}
