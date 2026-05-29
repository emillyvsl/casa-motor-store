<?php

namespace App\Http\Controllers\site;

use App\Http\Controllers\Controller;
use App\Models\Addresses;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function store(Request $request)
    {
        $customer = auth()->guard('customer')->user();

        $validated = $request->validate([
            'cep' => ['required', 'string'],
            'street' => ['required', 'string', 'max:255'],
            'number' => ['required', 'string', 'max:20'],
            'complement' => ['nullable', 'string', 'max:100'],
            'neighborhood' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'size:2'],
        ]);

        Addresses::create([
            'customer_id' => $customer->id,
            'cep' => preg_replace('/\D/', '', $validated['cep']),
            'street' => $validated['street'],
            'number' => $validated['number'],
            'complement' => $validated['complement'],
            'neighborhood' => $validated['neighborhood'],
            'city' => $validated['city'],
            'state' => strtoupper($validated['state']),
            'is_default' => $customer->addresses()->count() === 0,
        ]);

        return redirect()->route('customer.profile', ['tab' => 'addresses'])
            ->with('success', 'Endereço adicionado com sucesso!');
    }

    public function delete(Addresses $address)
    {
        $customer = auth()->guard('customer')->user();

        if ($address->customer_id !== $customer->id) {
            abort(403);
        }

        $address->delete();

        return redirect()->route('customer.profile', ['tab' => 'addresses'])
            ->with('success', 'Endereço removido com sucesso!');
    }
}
