<?php

namespace App\Services;

use App\Models\ShippingOrigin;
use App\Models\Product;

class ShippingCalculatorService
{
    public function calcular($cepDestino, array $itens)
    {
        $origem = ShippingOrigin::first(); // Por enquanto 1 origem

        $useMelhorEnvio = config('shipping.use_melhor_envio', false);

        if ($useMelhorEnvio) {
            return $this->calcularViaMelhorEnvio($origem->cep, $cepDestino, $itens);
        }

        return $this->calcularInterno($origem, $itens);
    }

    protected function calcularInterno($origem, $itens)
    {
        $totalPeso = 0;
        foreach ($itens as $item) {
            $totalPeso += $item['product']->weight_kg ?? 1 * $item['qty'];
        }

        return [[
            'label' => 'Entrega Padrão',
            'cost' => 25 + $totalPeso * 2,
            'eta_days' => 7,
            'source' => 'interno',
        ]];
    }

    protected function calcularViaMelhorEnvio($cepOrigem, $cepDestino, $itens)
    {
        // Aqui depois vamos integrar a API do Melhor Envio
        // simulando retorno:
        return [
            ['label' => 'PAC - Correios', 'cost' => 28.50, 'eta_days' => 9, 'source' => 'melhor_envio'],
            ['label' => 'SEDEX', 'cost' => 48.70, 'eta_days' => 4, 'source' => 'melhor_envio'],
        ];
    }
}
