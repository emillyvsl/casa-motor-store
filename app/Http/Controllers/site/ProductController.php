<?php

namespace App\Http\Controllers\site;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Products;
use App\Models\ShippingProfile;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Carregar categorias com contagem de produtos ativos
        $categories = Category::withCount(['products' => function($query) {
            $query->where('is_active', true);
        }])->orderBy('name')->get();

        $shippingProfiles = ShippingProfile::where('is_active', true)->get();

        $products = Products::with(['category', 'shippingProfiles', 'images'])
            ->where('is_active', true);

        // Filtro por categoria
        if ($request->has('category') && $request->category != '') {
            $products->where('category_id', $request->category);
        }

        // Filtro por tipo de entrega
        if ($request->has('shipping') && $request->shipping != '') {
            $products->whereHas('shippingProfiles', function($query) use ($request) {
                $query->where('shipping_profiles.id', $request->shipping);
            });
        }

        // Filtro por busca (case-insensitive)
        if ($request->has('search') && $request->search != '') {
            $searchTerm = strtolower($request->search);
            $products->where(function($query) use ($searchTerm) {
                $query->whereRaw('LOWER(name) LIKE ?', ["%{$searchTerm}%"])
                      ->orWhereRaw('LOWER(description) LIKE ?', ["%{$searchTerm}%"]);
            });
        }

        // Ordenação
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'price_low':
                $products->orderBy('price');
                break;
            case 'price_high':
                $products->orderBy('price', 'desc');
                break;
            case 'name':
                $products->orderBy('name');
                break;
            case 'featured':
                $products->orderBy('is_featured', 'desc')->orderBy('created_at', 'desc');
                break;
            default: // newest
                $products->orderBy('created_at', 'desc');
                break;
        }

        $products = $products->paginate(12);

        return view('site.product.index', compact('products', 'categories', 'shippingProfiles'));
    }

    public function show($slug)
{
    $product = Products::with(['category', 'shippingProfiles', 'images', 'reviews'])
        ->where('slug', $slug)
        ->where('is_active', true)
        ->firstOrFail();

    // Total e média de avaliações
    $totalReviews = $product->reviews->count();
    $average = $totalReviews > 0 ? round($product->reviews->avg('rating'), 1) : 0;

    // Distribuição de avaliações (1 a 5)
    $distribution = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
    if ($totalReviews > 0) {
        $counts = $product->reviews
            ->groupBy('rating')
            ->map(fn($r) => $r->count())
            ->toArray();

        foreach ($distribution as $stars => $count) {
            $distribution[$stars] = $counts[$stars] ?? 0;
        }
    }

    $ratingStats = [
        'average' => $average,
        'total' => $totalReviews,
        'distribution' => $distribution,
    ];

    // 🧩 Produtos relacionados (mesma categoria, ativos, excluindo o atual)
    $relatedProducts = Products::with(['images'])
        ->where('is_active', true)
        ->where('id', '!=', $product->id)
        ->when($product->category_id, fn($q) => $q->where('category_id', $product->category_id))
        ->inRandomOrder()
        ->take(4)
        ->get();

    return view('site.product.show', compact('product', 'ratingStats', 'relatedProducts'));
}



}