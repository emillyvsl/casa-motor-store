<?php

namespace App\Http\Controllers\admin;


use App\Http\Controllers\Controller;
use App\Models\ShippingOrigin;
use Illuminate\Http\Request;

class ShippingOriginController extends Controller
{
    public function index()
    {
        $origins = ShippingOrigin::orderBy('name')->paginate(15);
        return view('admin.shipping_origins.index', compact('origins'));
    }

    public function create()
    {
        return view('admin.shipping_origins.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'cep' => ['required','string'],
            'address' => ['required','string','max:255'],
            'city' => ['required','string','max:120'],
            'state' => ['required','string','max:2'],
        ]);

        // Sanitizar CEP: remover tudo que não é dígito
        $cepLimpo = preg_replace('/\D/', '', $data['cep']);

        // Validar que tem exatamente 8 dígitos
        if (strlen($cepLimpo) !== 8) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['cep' => 'O CEP deve conter exatamente 8 dígitos.']);
        }

        $data['cep'] = $cepLimpo;
        ShippingOrigin::create($data);
        return redirect()->route('admin.shipping-origins.index')->with('success','Origem criada!');
    }

    public function edit(ShippingOrigin $shipping_origin)
    {
        return view('admin.shipping_origins.edit', ['origin' => $shipping_origin]);
    }

    public function update(Request $request, ShippingOrigin $shipping_origin)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'cep' => ['required','string'],
            'address' => ['required','string','max:255'],
            'city' => ['required','string','max:120'],
            'state' => ['required','string','max:2'],
        ]);

        // Sanitizar CEP: remover tudo que não é dígito
        $cepLimpo = preg_replace('/\D/', '', $data['cep']);

        // Validar que tem exatamente 8 dígitos
        if (strlen($cepLimpo) !== 8) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['cep' => 'O CEP deve conter exatamente 8 dígitos.']);
        }

        $data['cep'] = $cepLimpo;
        $shipping_origin->update($data);
        return redirect()->route('admin.shipping-origins.index')->with('success','Origem atualizada!');
    }

    public function destroy(ShippingOrigin $shipping_origin)
    {
        $shipping_origin->delete();
        return back()->with('success','Origem excluída!');
    }
}
