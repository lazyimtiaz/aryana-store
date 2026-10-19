<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AddressController extends Controller
{
    /**
     * Display the user's saved addresses.
     */
    public function index(Request $request): View
    {
        $addresses = $request->user()
            ->addresses()
            ->latest()
            ->get();

        return view('profile.addresses', compact('addresses'));
    }

    /**
     * Show the form for creating an address.
     */
    public function create(): View
    {
        return view('profile.addresses.create');
    }

    /**
     * Store a new address.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'label' => ['nullable', 'string', 'max:50'],
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'address_line' => ['required', 'string', 'max:255'],
            'area' => ['nullable', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'district' => ['required', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'delivery_notes' => ['nullable', 'string', 'max:500'],
            'is_default' => ['nullable', 'boolean'],
        ]);

        $user = $request->user();

        $makeDefault = (bool) ($validated['is_default'] ?? false);

        /*
         * If this is the user's first address, automatically
         * make it the default address.
         */
        if (! $user->addresses()->exists()) {
            $makeDefault = true;
        }

        if ($makeDefault) {
            $user->addresses()->update([
                'is_default' => false,
            ]);
        }

        $validated['is_default'] = $makeDefault;

        $user->addresses()->create($validated);

        return redirect()
            ->route('account.addresses')
            ->with('success', 'Address added successfully.');
    }

    /**
     * Show the form for editing an address.
     */
    public function edit(Request $request, Address $address): View
    {
        $this->ensureOwnership($request, $address);

        return view('profile.addresses.edit', compact('address'));
    }

    /**
     * Update an existing address.
     */
    public function update(
        Request $request,
        Address $address
    ): RedirectResponse {
        $this->ensureOwnership($request, $address);

        $validated = $request->validate([
            'label' => ['nullable', 'string', 'max:50'],
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'address_line' => ['required', 'string', 'max:255'],
            'area' => ['nullable', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'district' => ['required', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'delivery_notes' => ['nullable', 'string', 'max:500'],
            'is_default' => ['nullable', 'boolean'],
        ]);

        $makeDefault = (bool) ($validated['is_default'] ?? false);

        if ($makeDefault) {
            $request->user()
                ->addresses()
                ->whereKeyNot($address->id)
                ->update([
                    'is_default' => false,
                ]);
        }

        $validated['is_default'] = $makeDefault;

        $address->update($validated);

        return redirect()
            ->route('account.addresses')
            ->with('success', 'Address updated successfully.');
    }

    /**
     * Delete an address.
     */
    public function destroy(
        Request $request,
        Address $address
    ): RedirectResponse {
        $this->ensureOwnership($request, $address);

        $wasDefault = $address->is_default;

        $address->delete();

        /*
         * If the deleted address was the default address,
         * promote the most recently created remaining address.
         */
        if ($wasDefault) {
            $replacement = $request->user()
                ->addresses()
                ->latest()
                ->first();

            if ($replacement) {
                $replacement->update([
                    'is_default' => true,
                ]);
            }
        }

        return redirect()
            ->route('account.addresses')
            ->with('success', 'Address removed.');
    }

    /**
     * Make an address the user's default address.
     */
    public function setDefault(
        Request $request,
        Address $address
    ): RedirectResponse {
        $this->ensureOwnership($request, $address);

        $request->user()
            ->addresses()
            ->update([
                'is_default' => false,
            ]);

        $address->update([
            'is_default' => true,
        ]);

        return redirect()
            ->route('account.addresses')
            ->with('success', 'Default address updated.');
    }

    /**
     * Ensure the address belongs to the authenticated user.
     */
    private function ensureOwnership(
        Request $request,
        Address $address
    ): void {
        abort_unless(
            $address->user_id === $request->user()->id,
            403
        );
    }
}
