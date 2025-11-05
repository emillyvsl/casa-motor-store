<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with(['parent', 'products'])->orderBy('name')->paginate(15);

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $categories = Category::whereNull('parent_id')->where('is_active', true)->orderBy('name')->get();
        return view('admin.categories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'name' => 'required|string|max:255',
                'parent_id' => 'nullable|exists:categories,id',
            ],
            [
                'name.required' => 'O campo nome é obrigatório.',
                'name.max' => 'O campo nome não pode exceder 255 caracteres.',
                'slug.required' => 'O campo slug é obrigatório.',
                'slug.max' => 'O campo slug não pode exceder 255 caracteres.',
                'slug.unique' => 'O slug informado já está em uso. Por favor, escolha outro.',
                'parent_id.exists' => 'A categoria pai selecionada é inválida.',
                'is_active.boolean' => 'O campo ativo deve ser verdadeiro ou falso.',
            ]
        );

        $validated['slug'] = $validated['slug'] ?? \Str::slug($validated['name']);

        $validated['is_active'] = $validated['is_active'] ?? true;

        Category::create([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'parent_id' => $validated['parent_id'] ?? null,
            'is_active' => $validated['is_active'],
        ]);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Categoria criada com sucesso!');
    }

    public function edit(Category $category)
    {
        $categories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->where('id', '!=', $category->id)
            ->orderBy('name')
            ->get();

        return view('admin.categories.edit', compact('category', 'categories'));
    }


    public function update(Request $request, Category $category)
    {
        $validated = $request->validate(
            [
                'name' => 'required|string|max:255',
                'parent_id' => 'nullable|exists:categories,id',
                'is_active' => 'sometimes|boolean',
            ],
            [
                'name.required' => 'O campo nome é obrigatório.',
                'name.max' => 'O campo nome não pode exceder 255 caracteres.',
                'parent_id.exists' => 'A categoria pai selecionada é inválida.',
                'is_active.boolean' => 'O campo ativo deve ser verdadeiro ou falso.',
            ]
        );
        $validated['slug'] = $validated['slug'] ?? \Str::slug($validated['name']);


        $category->update([
            'name' => $validated['name'],
            'parent_id' => $validated['parent_id'] ?? null,
            'is_active' => $validated['is_active'] ?? $category->is_active,
            'slug' => $validated['slug'],
        ]);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Categoria atualizada com sucesso!');
    }


    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Categoria excluída com sucesso!');
    }
}
