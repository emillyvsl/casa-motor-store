<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CorreiosShippingService
{
    /**
     * Calcula o frete via API dos Correios
     *
     * @param string $cepOrigem
     * @param string $cepDestino
     * @param float $peso Peso em kg
     * @param array $dimensoes ['altura' => cm, 'largura' => cm, 'comprimento' => cm]
     * @return array
     */
    public function calcular(string $cepOrigem, string $cepDestino, float $peso = 1.0, array $dimensoes = [])
    {
        $url = 'http://ws.correios.com.br/calculador/CalcPrecoPrazo.asmx/CalcPrecoPrazo';

        $params = [
            'nCdEmpresa' => '', 
            'sDsSenha' => '',   
            'nCdServico' => '04510,04014', 
            'sCepOrigem' => preg_replace('/\D/', '', $cepOrigem),
            'sCepDestino' => preg_replace('/\D/', '', $cepDestino),
            'nVlPeso' => $peso ?: 1,
            'nCdFormato' => 1, 
            'nVlComprimento' => $dimensoes['comprimento'] ?? 20,
            'nVlAltura' => $dimensoes['altura'] ?? 10,
            'nVlLargura' => $dimensoes['largura'] ?? 15,
            'nVlDiametro' => 0,
            'sCdMaoPropria' => 'N',
            'nVlValorDeclarado' => 0,
            'sCdAvisoRecebimento' => 'N',
            'StrRetorno' => 'xml',
            'nIndicaCalculo' => 3,
        ];

        $response = Http::get($url, $params);

        if (!$response->ok()) {
            throw new \Exception('Erro ao consultar os Correios');
        }

        $xml = simplexml_load_string($response->body());
        $servicos = [];

        foreach ($xml->Servicos->cServico as $servico) {
            $servicos[] = [
                'codigo' => (string) $servico->Codigo,
                'label' => $this->getLabel((string) $servico->Codigo),
                'eta_days' => (int) $servico->PrazoEntrega,
                'cost' => (float) str_replace(',', '.', (string) $servico->Valor),
            ];
        }

        return $servicos;
    }

    private function getLabel($codigo)
    {
        return match ($codigo) {
            '04014' => 'SEDEX',
            '04510' => 'PAC',
            default => 'Correios'
        };
    }
}
