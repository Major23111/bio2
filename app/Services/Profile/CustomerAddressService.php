<?php

namespace App\Services\Profile;

use App\Models\Authorization\User;
use App\Models\Authorization\UserAddress;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class CustomerAddressService
{
    // This prepares the live address-book page data for the signed-in customer.
    public function buildAddressPageData(User $user): array
    {
        return [
            'portal' => $user->isB2b() ? 'b2b' : 'b2c',
            'savedAddresses' => $this->savedAddressesForUser($user),
        ];
    }

    // This returns the validation rules shared by both add and update address forms.
    public function addressValidationRules(): array
    {
        return [
            'line1' => ['required', 'string', 'max:255'],
            'line2' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:128'],
            'state' => ['required', 'string', 'max:128'],
            'postal_code' => ['required', 'string', 'max:20'],
            'country' => ['required', 'string', 'max:128'],
            'is_default_shipping' => ['nullable', 'boolean'],
            'is_default_billing' => ['nullable', 'boolean'],
        ];
    }

    // This saves a brand-new address into the signed-in customer's address book.
    public function createAddressForUser(User $user, array $validated): void
    {
        DB::transaction(function () use ($user, $validated): void {
            $hasExistingAddresses = $user->addresses()->exists();
            $address = new UserAddress();

            // Step 1: copy the submitted address fields onto the new address record.
            $this->fillAddressFields($address, $validated);

            // Step 2: make the first saved address the default for both shipping and billing so checkout always has a stable address.
            $address->user_id = $user->id;
            $address->is_default_shipping = $hasExistingAddresses
                ? (bool) ($validated['is_default_shipping'] ?? false)
                : true;
            $address->is_default_billing = $hasExistingAddresses
                ? (bool) ($validated['is_default_billing'] ?? false)
                : true;
            $address->save();

            // Step 3: keep the default flags consistent across the customer's saved address book.
            $this->syncDefaultAddressFlags($user, $address);
        });
    }

    // This updates one existing saved address that belongs to the signed-in customer.
    public function updateAddressForUser(User $user, int $addressId, array $validated): void
    {
        DB::transaction(function () use ($user, $addressId, $validated): void {
            // Step 1: load only the current customer's own address row so cross-account updates are never allowed.
            $address = $this->findUserAddressOrFail($user, $addressId);

            // Step 2: apply the submitted edits to the selected address row.
            $this->fillAddressFields($address, $validated);
            $address->is_default_shipping = (bool) ($validated['is_default_shipping'] ?? false);
            $address->is_default_billing = (bool) ($validated['is_default_billing'] ?? false);
            $address->save();

            // Step 3: keep one clean default set across the full address book after the update is saved.
            $this->syncDefaultAddressFlags($user, $address);
        });
    }

    // This returns the customer's saved addresses in the same business order used across customer flows.
    protected function savedAddressesForUser(User $user): Collection
    {
        return $user->addresses()
            ->orderByDesc('is_default_shipping')
            ->orderByDesc('is_default_billing')
            ->orderByDesc('id')
            ->get();
    }

    // This loads one saved address that belongs to the signed-in customer.
    protected function findUserAddressOrFail(User $user, int $addressId): UserAddress
    {
        return UserAddress::query()
            ->where('user_id', $user->id)
            ->findOrFail($addressId);
    }

    // This copies the submitted text fields onto a user address model in one small reusable step.
    protected function fillAddressFields(UserAddress $address, array $validated): void
    {
        $address->line1 = trim((string) ($validated['line1'] ?? ''));
        $address->line2 = filled($validated['line2'] ?? null) ? trim((string) $validated['line2']) : null;
        $address->city = trim((string) ($validated['city'] ?? ''));
        $address->state = trim((string) ($validated['state'] ?? ''));
        $address->postal_code = trim((string) ($validated['postal_code'] ?? ''));
        $address->country = trim((string) ($validated['country'] ?? 'India'));
    }

    // This keeps the customer's default shipping and billing address flags consistent after a save.
    protected function syncDefaultAddressFlags(User $user, UserAddress $savedAddress): void
    {
        // Step 1: when the current address is selected as default, remove the same default type from all other addresses.
        if ($savedAddress->is_default_shipping) {
            UserAddress::query()
                ->where('user_id', $user->id)
                ->whereKeyNot($savedAddress->id)
                ->update(['is_default_shipping' => false]);
        }

        if ($savedAddress->is_default_billing) {
            UserAddress::query()
                ->where('user_id', $user->id)
                ->whereKeyNot($savedAddress->id)
                ->update(['is_default_billing' => false]);
        }

        // Step 2: guarantee at least one default address remains available for shipping and billing flows.
        $mustSaveAddressAgain = false;

        if (! $user->addresses()->where('is_default_shipping', true)->exists()) {
            $savedAddress->is_default_shipping = true;
            $mustSaveAddressAgain = true;
        }

        if (! $user->addresses()->where('is_default_billing', true)->exists()) {
            $savedAddress->is_default_billing = true;
            $mustSaveAddressAgain = true;
        }

        if ($mustSaveAddressAgain) {
            $savedAddress->save();
        }
    }
}
