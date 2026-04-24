<?php

namespace App\Services\Order;

use App\Models\Authorization\User;
use App\Models\Authorization\UserAddress;
use App\Models\Order\Order;
use Illuminate\Validation\ValidationException;

class OrderAddressService
{
    // Resolve the checkout address from either saved or new address input.
    public function resolveCheckoutAddress(User $user, array $validatedCheckout): array
    {
        $selectedAddressSource = (string) ($validatedCheckout['selected_address_source'] ?? 'existing');

        // Use an existing saved address.
        if ($selectedAddressSource === 'existing') {
            return $this->loadExistingSavedAddress($user, $validatedCheckout);
        }

        // Use a new entered address.
        return $this->validateAndSaveNewAddress($user, $validatedCheckout);
    }

    // Load and validate an existing saved address.
    protected function loadExistingSavedAddress(User $user, array $validatedCheckout): array
    {
        $selectedUserAddressId = (int) ($validatedCheckout['selected_user_address_id'] ?? 0);

        if ($selectedUserAddressId === 0) {
            throw ValidationException::withMessages([
                'selected_user_address_id' => 'Please select one saved address for checkout.',
            ]);
        }

        $selectedUserAddress = UserAddress::query()
            ->where('user_id', $user->id)
            ->whereKey($selectedUserAddressId)
            ->first();

        if (! $selectedUserAddress) {
            throw ValidationException::withMessages([
                'selected_user_address_id' => 'Please select one saved address for checkout.',
            ]);
        }

        return [
            'address_line1' => $selectedUserAddress->line1,
            'address_line2' => $selectedUserAddress->line2,
            'city' => $selectedUserAddress->city,
            'state' => $selectedUserAddress->state,
            'postal_code' => $selectedUserAddress->postal_code,
            'country' => $selectedUserAddress->country ?: 'India',
            'contact_phone' => $user->phone,
            'address_label' => $selectedUserAddress->line2,
        ];
    }

    // Validate and save a new checkout address.
    protected function validateAndSaveNewAddress(User $user, array $validatedCheckout): array
    {
        // Validate all required address fields.
        if (! filled($validatedCheckout['new_address_label'] ?? null)) {
            throw ValidationException::withMessages([
                'new_address_label' => 'Please enter an address label.',
            ]);
        }

        if (! filled($validatedCheckout['new_address_line1'] ?? null)) {
            throw ValidationException::withMessages([
                'new_address_line1' => 'Please enter a street address.',
            ]);
        }

        if (! filled($validatedCheckout['new_address_city'] ?? null)) {
            throw ValidationException::withMessages([
                'new_address_city' => 'Please enter a city.',
            ]);
        }

        if (! filled($validatedCheckout['new_address_state'] ?? null)) {
            throw ValidationException::withMessages([
                'new_address_state' => 'Please enter a state.',
            ]);
        }

        if (! filled($validatedCheckout['new_address_postal_code'] ?? null)) {
            throw ValidationException::withMessages([
                'new_address_postal_code' => 'Please enter a postal code.',
            ]);
        }

        // Save the new address.
        $createdUserAddress = $this->saveNewAddress($user, $validatedCheckout);

        return [
            'address_line1' => $createdUserAddress->line1,
            'address_line2' => $createdUserAddress->line2,
            'city' => $createdUserAddress->city,
            'state' => $createdUserAddress->state,
            'postal_code' => $createdUserAddress->postal_code,
            'country' => $createdUserAddress->country ?: 'India',
            'contact_phone' => $validatedCheckout['new_address_phone'] ?? $user->phone,
            'address_label' => $validatedCheckout['new_address_label'] ?? null,
        ];
    }

    // Save a new address to the user's address book.
    protected function saveNewAddress(User $user, array $validatedCheckout): UserAddress
    {
        $userHasSavedAddresses = $user->addresses()->exists();

        return $user->addresses()->create([
            'line1' => trim((string) ($validatedCheckout['new_address_line1'] ?? '')),
            'line2' => filled($validatedCheckout['new_address_label'] ?? null) ? trim((string) $validatedCheckout['new_address_label']) : null,
            'city' => trim((string) ($validatedCheckout['new_address_city'] ?? '')),
            'state' => trim((string) ($validatedCheckout['new_address_state'] ?? '')),
            'postal_code' => trim((string) ($validatedCheckout['new_address_postal_code'] ?? '')),
            'country' => filled($validatedCheckout['new_address_country'] ?? null) ? trim((string) $validatedCheckout['new_address_country']) : 'India',
            'is_default_shipping' => ! $userHasSavedAddresses,
            'is_default_billing' => ! $userHasSavedAddresses,
        ]);
    }

    // Build an address payload for the order record.
    public function buildOrderAddressPayload(Order $order, string $addressType, array $checkoutAddressData, User $user, array $validatedCheckout): array
    {
        $companyName = null;

        if ($user->isB2b()) {
            $companyName = $validatedCheckout['registered_business_name'] ?? null;

            if (! filled($companyName)) {
                $companyName = $user->company?->legal_name ?: $user->company?->name;
            }
        }

        $gstin = null;

        if ($addressType === 'billing' && $user->isB2b()) {
            $gstin = $validatedCheckout['gstin'] ?? null;

            if (! filled($gstin)) {
                $gstin = $user->company?->gst_number;
            }
        }

        return [
            'order_id' => $order->id,
            'address_type' => $addressType,
            'contact_name' => $user->name,
            'company_name' => $companyName,
            'email' => $user->email,
            'phone' => filled($checkoutAddressData['contact_phone'] ?? null) ? trim((string) $checkoutAddressData['contact_phone']) : $user->phone,
            'gstin' => filled($gstin) ? trim((string) $gstin) : null,
            'line1' => trim((string) ($checkoutAddressData['address_line1'] ?? '')),
            'line2' => filled($checkoutAddressData['address_line2'] ?? null) ? trim((string) $checkoutAddressData['address_line2']) : null,
            'landmark' => filled($checkoutAddressData['address_label'] ?? null) ? trim((string) $checkoutAddressData['address_label']) : null,
            'city' => trim((string) ($checkoutAddressData['city'] ?? '')),
            'state' => trim((string) ($checkoutAddressData['state'] ?? '')),
            'postal_code' => trim((string) ($checkoutAddressData['postal_code'] ?? '')),
            'country_code' => $this->normalizeCountryCode($checkoutAddressData['country'] ?? 'India'),
        ];
    }

    // Convert country name to country code.
    protected function normalizeCountryCode(?string $country): string
    {
        $normalizedCountry = strtoupper(trim((string) $country));

        return match ($normalizedCountry) {
            '', 'INDIA', 'IN' => 'IN',
            default => substr($normalizedCountry, 0, 2),
        };
    }
}
