<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Products;
use App\Models\ShippingProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Products::with(['category', 'shippingProfiles'])
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $shippingProfiles = ShippingProfile::where('is_active', true)->get();

        return view('admin.products.create', compact('categories', 'shippingProfiles'));
    }

    public function store(Request $request)
    {
        $shippingProfiles = collect(explode(',', $request->input('shipping_profiles', '')))
            ->filter(fn($id) => is_numeric($id))
            ->map(fn($id) => (int) $id)
            ->toArray();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|unique:products,sku',
            'category_id' => 'nullable|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'stock' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'attributes' => 'nullable|string',
            'images.*' => 'nullable|image|max:2048',
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['name']);
        $validated['sku'] = $validated['sku'] ?? 'PRD-' . strtoupper(Str::random(8));
        $validated['attributes'] = !empty($validated['attributes'])
            ? json_decode($validated['attributes'], true)
            : [];
        $validated['is_active'] = $request->has('is_active');
        $validated['is_featured'] = $request->has('is_featured');
        $validated['allow_out_of_stock_sales'] = $request->has('allow_out_of_stock_sales');
        $validated['max_backorder'] = $request->input('max_backorder');
        $validated['backorder_delivery_days'] = $request->input('backorder_delivery_days');
        $validated['out_of_stock_message'] = $request->input('out_of_stock_message');

        $product = Products::create($validated);

        if (!empty($shippingProfiles)) {
            $product->shippingProfiles()->sync($shippingProfiles);
        }

        // 📸 Salvar imagens diretamente em public/products/
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('products'), $filename);

                $product->images()->create([
                    'path' => 'products/' . $filename,
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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:products,slug,' . $product->id,
            'category_id' => 'nullable|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'images.*' => 'nullable|image|max:2048',
            'shipping_profiles' => 'array',
            'shipping_profiles.*' => 'exists:shipping_profiles,id',
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');
        $validated['is_featured'] = $request->has('is_featured');

        $product->update($validated);
        $product->shippingProfiles()->sync($request->shipping_profiles ?? []);

        // 📸 Atualizar imagens
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('products'), $filename);

                $product->images()->create([
                    'path' => 'products/' . $filename,
                    'is_main' => false,
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Produto atualizado com sucesso!');
    }

    public function destroy(Products $product)
    {
        foreach ($product->images as $image) {
            $imagePath = public_path($image->path);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $product->shippingProfiles()->detach();
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produto removido com sucesso!');
    }
}
