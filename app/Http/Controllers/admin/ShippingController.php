<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Services\MelhorEnvioService;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    protected MelhorEnvioService $melhorEnvio;

    public function __construct(MelhorEnvioService $melhorEnvio)
    {
        $this->melhorEnvio = $melhorEnvio;
    }

    public function quote(Request $request)
    {
        $validated = $request->validate([
            'cep_destino' => 'required|string',
            'products' => 'required|array|min:1',
        ]);

        $payload = [
            'from' => [
                'postal_code' => '69921-070', // origem da empresa
            ],
            'to' => [
                'postal_code' => $validated['cep_destino'],
            ],
            'products' => array_map(function ($p) {
                return [
                    'id' => $p['id'] ?? 'produto',
                    'width' => $p['width'] ?? 20,
                    'height' => $p['height'] ?? 20,
                    'length' => $p['length'] ?? 30,
                    'weight' => $p['weight'] ?? 1,
                    'insurance_value' => min(($p['insurance_value'] ?? 0), 0),
                    'quantity' => $p['quantity'] ?? 1,
                ];
            }, $validated['products']),
            'options' => [
                'receipt' => false,
                'own_hand' => false,
            ],
            'services' => '1,2,3,4,7,11',
        ];

        $result = $this->melhorEnvio->calcularFrete($payload);

        if (!$result['success']) {
            return response()->json(['error' => true, 'message' => $result['error']], 500);
        }

        return response()->json($result['data']);
    }
}
