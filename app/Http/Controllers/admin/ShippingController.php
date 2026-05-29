<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingOrigin;
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
            'products'    => 'required|array|min:1',
        ]);

        // Busca CEP de origem dinamicamente do banco de dados
        $origem = ShippingOrigin::first();

        if (!$origem) {
            return response()->json([
                'error'   => true,
                'message' => 'Nenhuma origem de envio cadastrada. Acesse Admin > Origens de Envio e cadastre o CEP de origem.',
            ], 422);
        }

        // Sanitiza o CEP removendo hifens e espacos
        $cepOrigem  = preg_replace('/\D/', '', $origem->cep);
        $cepDestino = preg_replace('/\D/', '', $validated['cep_destino']);

        // Validar CEP de origem
        if (empty($cepOrigem) || strlen($cepOrigem) !== 8) {
            return response()->json([
                'error'   => true,
                'message' => 'CEP de origem inválido. Acesse Admin > Origens de Envio e verifique o CEP cadastrado (deve conter 8 dígitos).',
            ], 422);
        }

        // Validar CEP de destino
        if (empty($cepDestino) || strlen($cepDestino) !== 8) {
            return response()->json([
                'error'   => true,
                'message' => 'CEP de destino inválido. Por favor, informe um CEP com 8 dígitos.',
            ], 422);
        }

        $payload = [
            'from' => [
                'postal_code' => $cepOrigem,
            ],
            'to' => [
                'postal_code' => $cepDestino,
            ],
            'products' => array_map(function ($p) {
                return [
                    'id'              => $p['id'] ?? 'produto',
                    'width'           => max((float) ($p['width']  ?? 16), 1),
                    'height'          => max((float) ($p['height'] ?? 16), 1),
                    'length'          => max((float) ($p['length'] ?? 20), 1),
                    'weight'          => max((float) ($p['weight'] ?? 0.3), 0.1),
                    // Corrigido: max() garante valor nao-negativo para o seguro
                    'insurance_value' => max((float) ($p['insurance_value'] ?? 0), 0),
                    'quantity'        => max((int) ($p['quantity'] ?? 1), 1),
                ];
            }, $validated['products']),
            'options' => [
                'receipt'  => false,
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
