<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingProfile;
use Illuminate\Http\Request;

class ShippingProfileController extends Controller
{
    public function index()
    {
        $profiles = ShippingProfile::orderBy('name')->paginate(10);
        return view('admin.shippingProfile.index', compact('profiles'));
    }

    public function create()
    {
        return view('admin.shippingProfile.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'delivery_time_in_stock' => 'required|integer|min:0',
            'delivery_time_backorder' => 'required|integer|min:0',
            'shipping_cost' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'type' => 'required|string|max:50',
            'is_active' => 'boolean',
        ]);

        ShippingProfile::create($validated);

        return redirect()
            ->route('admin.shipping-profiles.index')
            ->with('success', 'Perfil de frete criado com sucesso!');
    }

    public function edit(ShippingProfile $shippingProfile)
    {
        return view('admin.shippingProfile.edit', compact('shippingProfile'));
    }

    public function update(Request $request, ShippingProfile $shippingProfile)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'delivery_time_in_stock' => 'required|integer|min:0',
            'delivery_time_backorder' => 'required|integer|min:0',
            'shipping_cost' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'type' => 'required|string|max:50',
            'is_active' => 'boolean',
        ]);

        $shippingProfile->update($validated);

        return redirect()
            ->route('admin.shipping-profiles.index')
            ->with('success', 'Perfil de frete atualizado com sucesso!');
    }

    public function destroy(ShippingProfile $shippingProfile)
    {
        $shippingProfile->delete();
        return back()->with('success', 'Perfil de frete removido com sucesso!');
    }
}
