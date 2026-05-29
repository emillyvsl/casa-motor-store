<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Products;
use App\Models\ShippingProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Products::with(['category', 'shippingProfiles'])
            ->orderByDesc('created_at')
            ->paginate(15);

        $stats = [
            'total' => Products::count(),
            'active' => Products::where('is_active', true)->count(),
            'low_stock' => Products::where('stock', '>', 0)
                ->whereColumn('stock', '<=', 'stock_alert_threshold')
                ->count(),
            'backorder_enabled' => Products::where('allow_out_of_stock_sales', true)->count(),
        ];

        return view('admin.products.index', compact('products', 'stats'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $shippingProfiles = ShippingProfile::where('is_active', true)->get();

        return view('admin.products.create', compact('categories', 'shippingProfiles'));
    }

    public function store(Request $request)
    {
        $shippingProfiles = $this->extractShippingProfiles($request);
        $validated = $this->validateProduct($request);
        $payload = $this->buildPayload($request, $validated, $shippingProfiles);

        $product = Products::create($payload);
        $product->shippingProfiles()->sync($shippingProfiles);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $filename = time().'_'.Str::random(10).'.'.$file->getClientOriginalExtension();

                $storagePath = storage_path('app/public/products');
                if (! is_dir($storagePath)) {
                    mkdir($storagePath, 0755, true);
                }

                $file->move($storagePath, $filename);

                $relativePath = 'products/'.$filename;

                $product->images()->create([
                    'path' => $relativePath,
                    'is_main' => $index === 0,
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Produto criado com sucesso!');
    }

    public function edit(Products $product)
    {
        $categories = Category::orderBy('name')->get();
        $shippingProfiles = ShippingProfile::where('is_active', true)->get();

        $product->load(['images', 'shippingProfiles']);

        return view('admin.products.edit', compact('product', 'categories', 'shippingProfiles'));
    }

    public function update(Request $request, Products $product)
    {
        $shippingProfiles = $this->extractShippingProfiles($request);
        $validated = $this->validateProduct($request, $product);
        $payload = $this->buildPayload($request, $validated, $shippingProfiles, $product);

        $product->update($payload);
        $product->shippingProfiles()->sync($shippingProfiles);

        $removedIds = $request->input('removed_images', []);
        if (is_string($removedIds)) {
            $removedIds = array_filter(array_map('intval', explode(',', $removedIds)));
        }

        if (is_array($removedIds) && ! empty($removedIds)) {
            $imagesToRemove = $product->images()->whereIn('id', $removedIds)->get();

            foreach ($imagesToRemove as $img) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($img->path);

                $legacy = public_path($img->path);
                if (is_file($legacy)) {
                    @unlink($legacy);
                }

                $img->delete();
            }
        }

        if ($request->hasFile('images')) {
            $storagePath = storage_path('app/public/products');
            if (! is_dir($storagePath)) {
                @mkdir($storagePath, 0755, true);
            }

            $noImagesLeft = ! $product->images()->exists();

            foreach ($request->file('images') as $index => $file) {
                $filename = time().'_'.\Illuminate\Support\Str::random(10).'.'.$file->getClientOriginalExtension();
                $file->move($storagePath, $filename);

                $relativePath = 'products/'.$filename;

                $product->images()->create([
                    'path' => $relativePath,
                    'is_main' => $noImagesLeft && $index === 0,
                ]);
            }
        }

        $hasMain = $product->images()->where('is_main', true)->exists();
        if (! $hasMain) {
            $first = $product->images()->first();
            if ($first) {
                $first->update(['is_main' => true]);
            }
        }

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produto atualizado com sucesso!');
    }

    public function destroy(Products $product)
    {
        foreach ($product->images as $image) {
            if (Storage::disk('public')->exists($image->path)) {
                Storage::disk('public')->delete($image->path);
            }
        }

        $product->shippingProfiles()->detach();
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produto removido com sucesso!');
    }

    protected function validateProduct(Request $request, ?Products $product = null): array
    {
        $productId = $product?->id;

        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['nullable', 'string', 'max:100', 'unique:products,sku,'.$productId],
            'slug' => ['nullable', 'string', 'max:255', 'unique:products,slug,'.$productId],
            'category_id' => ['nullable', 'exists:categories,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'discount_price' => ['nullable', 'numeric', 'min:0', 'lte:price'],
            'stock' => ['nullable', 'integer', 'min:0'],
            'stock_alert_threshold' => ['nullable', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'attributes' => ['nullable'],
            'images.*' => ['nullable', 'image', 'max:2048'],
            'removed_images' => ['sometimes'],
            'weight' => ['nullable', 'numeric', 'min:0.1'],
            'width' => ['nullable', 'numeric', 'min:1'],
            'height' => ['nullable', 'numeric', 'min:1'],
            'length' => ['nullable', 'numeric', 'min:1'],
            'max_backorder' => ['nullable', 'integer', 'min:0'],
            'backorder_delivery_days' => ['nullable', 'integer', 'min:0'],
            'out_of_stock_message' => ['nullable', 'string', 'max:255'],
        ], [
            'discount_price.lte' => 'O preço promocional não pode ser maior que o preço de venda.',
        ]);
    }

    protected function buildPayload(Request $request, array $validated, array $shippingProfiles, ?Products $product = null): array
    {
        $allowOutOfStockSales = $request->boolean('allow_out_of_stock_sales');

        return [
            'name' => $validated['name'],
            'sku' => $validated['sku'] ?? $product?->sku ?? 'PRD-'.strtoupper(Str::random(8)),
            'slug' => $validated['slug'] ?? $product?->slug ?? Str::slug($validated['name']),
            'category_id' => $validated['category_id'] ?? null,
            'shipping_profile_id' => $shippingProfiles[0] ?? null,
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'discount_price' => $validated['discount_price'] ?? null,
            'stock' => $validated['stock'] ?? 0,
            'stock_alert_threshold' => $validated['stock_alert_threshold'] ?? 5,
            'attributes' => $this->parseAttributes($request->input('attributes')),
            'weight' => $validated['weight'] ?? $product?->weight ?? 0.3,
            'width' => $validated['width'] ?? $product?->width ?? 16,
            'height' => $validated['height'] ?? $product?->height ?? 16,
            'length' => $validated['length'] ?? $product?->length ?? 20,
            'is_active' => $request->boolean('is_active'),
            'is_featured' => $request->boolean('is_featured'),
            'allow_out_of_stock_sales' => $allowOutOfStockSales,
            'max_backorder' => $allowOutOfStockSales ? ($validated['max_backorder'] ?? null) : null,
            'backorder_delivery_days' => $allowOutOfStockSales ? ($validated['backorder_delivery_days'] ?? 0) : 0,
            'out_of_stock_message' => $allowOutOfStockSales ? ($validated['out_of_stock_message'] ?? null) : null,
        ];
    }

    protected function parseAttributes(mixed $attributes): array
    {
        if (blank($attributes)) {
            return [];
        }

        if (is_array($attributes)) {
            return $attributes;
        }

        $decoded = json_decode((string) $attributes, true);

        return is_array($decoded) ? $decoded : [];
    }

    protected function extractShippingProfiles(Request $request): array
    {
        $shippingProfiles = $request->input('shipping_profiles', []);

        if (is_string($shippingProfiles)) {
            $shippingProfiles = explode(',', $shippingProfiles);
        }

        $ids = collect($shippingProfiles)
            ->filter(fn ($id) => is_numeric($id))
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        return ShippingProfile::whereIn('id', $ids)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }
}
