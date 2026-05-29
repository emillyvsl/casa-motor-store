<?php

namespace App\Http\Controllers\site;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\HomeBanner;
use App\Models\Products;
use Illuminate\Support\Facades\Schema;

class PageController extends Controller
{
    public function home()
    {
        $featuredProducts = Products::with(['category', 'images'])
            ->where('is_active', true)
            ->orderByDesc('is_featured')
            ->orderByDesc('created_at')
            ->take(4)
            ->get();

        $topCategories = Category::query()
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->withCount(['products' => function ($query) {
                $query->where('is_active', true);
            }])
            ->orderBy('name')
            ->take(8)
            ->get();

        $serviceHighlights = [
            [
                'icon' => 'fa-screwdriver-wrench',
                'title' => 'Assistência Técnica',
                'description' => 'Diagnóstico, manutenção e reparo especializado para motores, bombas e equipamentos.',
            ],
            [
                'icon' => 'fa-gears',
                'title' => 'Peças e Acessórios',
                'description' => 'Linha de peças, componentes e acessórios para manter sua operação em movimento.',
            ],
            [
                'icon' => 'fa-store',
                'title' => 'Loja Completa',
                'description' => 'Equipamentos para agro, jardim e uso profissional com atendimento próximo e técnico.',
            ],
        ];

        $homeBanners = Schema::hasTable('home_banners')
            ? HomeBanner::query()
                ->active()
                ->orderBy('sort_order')
                ->orderByDesc('updated_at')
                ->take(5)
                ->get()
            : collect();

        return view('site.home', compact('featuredProducts', 'serviceHighlights', 'topCategories', 'homeBanners'));
    }

    public function services()
    {
        return view('site.services');
    }

    public function about()
    {
        return view('site.about');
    }

    public function contact()
    {
        return view('site.contact');
    }
}
