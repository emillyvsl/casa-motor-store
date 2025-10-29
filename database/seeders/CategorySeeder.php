<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Motores Elétricos',
            'Bombas D’água',
            'Ferramentas Elétricas',
            'Peças e Acessórios',
            'Iluminação',
            'Materiais Elétricos',
        ];

        foreach ($categories as $name) {
            Category::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            );
        }

        // Exemplo de subcategoria
        $parent = Category::where('slug', 'motores-eletricos')->first();
        if ($parent) {
            Category::updateOrCreate(
                ['slug' => 'motores-monofasicos'],
                [
                    'name' => 'Motores Monofásicos',
                    'parent_id' => $parent->id,
                ]
            );
            Category::updateOrCreate(
                ['slug' => 'motores-trifasicos'],
                [
                    'name' => 'Motores Trifásicos',
                    'parent_id' => $parent->id,
                ]
            );
        }
    }
}