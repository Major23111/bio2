<?php

namespace App\Http\Controllers\AdminPanel\Proforma;

use App\Http\Controllers\Controller;
use App\Services\AdminPanel\Proforma\ProformaCrudService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
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
            return view('admin.pi-quotation-create', []);
        } catch (Throwable $exception) {
            return view('admin.pi-quotation-create', []);
        }
    }

    // Save a new PI from the create form.
    public function store(Request $request): RedirectResponse
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
            ]);

            // Create the PI and its line items.
            $this->proformaCrudService->createProforma($validated);

            return redirect()->route('admin.pi-quotation.index')
                ->with('success', 'Proforma Invoice created successfully.');
        } catch (Throwable $exception) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create Proforma Invoice. Please try again.');
        }
    }

    // Show the edit form for an existing PI (reuses the create view with prefilled data).
    public function show(int $proformaId): View
    {
        try {
            // Fetch the PI details for prefilling the form.
            $proforma = $this->proformaCrudService->getProformaForView($proformaId);

            if (!$proforma) {
                abort(404);
            }

            // Reuse the create view; presence of $proforma signals edit mode.
            return view('admin.pi-quotation-create', [
                'proforma' => $proforma,
            ]);
        } catch (Throwable $exception) {
            abort(500);
        }
    }

    // Save updates to an existing PI from the edit form.
    public function update(Request $request, int $proformaId): RedirectResponse
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
            ]);

            // Update the PI record and its line items.
            $isUpdated = $this->proformaCrudService->updateProforma($proformaId, $validated);

            if (!$isUpdated) {
                return redirect()->back()
                    ->with('error', 'Proforma Invoice not found.');
            }

            return redirect()->route('admin.pi-quotation.index')
                ->with('success', 'Proforma Invoice updated successfully.');
        } catch (Throwable $exception) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update Proforma Invoice. Please try again.');
        }
    }
}
