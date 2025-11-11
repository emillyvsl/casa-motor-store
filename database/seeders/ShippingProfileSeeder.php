<?php

namespace Database\Seeders;

use App\Models\ShippingOrigin;
use Illuminate\Database\Seeder;
use App\Models\ShippingProfile;

class ShippingProfileSeeder extends Seeder
{
    public function run(): void
    {
        ShippingOrigin::firstOrCreate(
            ['name' => 'Matriz Rio Branco'],
            [
                'cep' => '69900-000',
                'address' => 'Rua Exemplo, 123',
                'city' => 'Rio Branco',
                'state' => 'AC',
            ]
        );
        $profiles = [
            [
                'name' => 'Entrega Padrão Nacional',
                'description' => 'Frete comum via transportadora com prazo padrão.',
                'delivery_time_in_stock' => 5,
                'delivery_time_backorder' => 10,
                'shipping_cost' => 25.00,
                'type' => 'default',
                'is_active' => true,
            ],
            [
                'name' => 'Retirada na Loja',
                'description' => 'O cliente retira o produto diretamente na loja física.',
                'delivery_time_in_stock' => 0,
                'delivery_time_backorder' => 7,
                'shipping_cost' => 0.00,
                'type' => 'pickup',
                'is_active' => true,
            ],
            [
                'name' => 'Entrega Expressa',
                'description' => 'Entrega rápida com prazo reduzido e custo adicional.',
                'delivery_time_in_stock' => 2,
                'delivery_time_backorder' => 6,
                'shipping_cost' => 49.90,
                'type' => 'express',
                'is_active' => true,
            ],
            [
                'name' => 'Sob Encomenda',
                'description' => 'Produto encomendado diretamente ao fornecedor.',
                'delivery_time_in_stock' => 10,
                'delivery_time_backorder' => 20,
                'shipping_cost' => 35.00,
                'type' => 'backorder',
                'is_active' => true,
            ],
        ];

        foreach ($profiles as $data) {
            ShippingProfile::updateOrCreate(
                ['name' => $data['name']],
                $data
            );
        }
    }
}
