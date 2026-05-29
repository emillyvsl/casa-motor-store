<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\HomeBanner;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class HomeBannerController extends Controller
{
    public function index()
    {
        if (! $this->homeBannersTableReady()) {
            $banners = new LengthAwarePaginator([], 0, 12);
            $stats = [
                'total' => 0,
                'active' => 0,
                'inactive' => 0,
            ];
            $migrationMissing = true;

            return view('admin.home-banners.index', compact('banners', 'stats', 'migrationMissing'));
        }

        $banners = HomeBanner::query()
            ->orderBy('sort_order')
            ->orderByDesc('updated_at')
            ->paginate(12);

        $stats = [
            'total' => HomeBanner::count(),
            'active' => HomeBanner::where('is_active', true)->count(),
            'inactive' => HomeBanner::where('is_active', false)->count(),
        ];

        $migrationMissing = false;

        return view('admin.home-banners.index', compact('banners', 'stats', 'migrationMissing'));
    }

    public function create()
    {
        if (! $this->homeBannersTableReady()) {
            return $this->missingTableRedirect();
        }

        return view('admin.home-banners.create');
    }

    public function store(Request $request)
    {
        if (! $this->homeBannersTableReady()) {
            return $this->missingTableRedirect();
        }

        $validated = $this->validateBanner($request);
        $payload = $this->buildPayload($request, $validated);

        $payload['image_path'] = $request->file('image')->store('home-banners', 'public');

        HomeBanner::create($payload);

        return redirect()
            ->route('admin.home-banners.index')
            ->with('success', 'Banner criado com sucesso!');
    }

    public function edit(HomeBanner $banner)
    {
        if (! $this->homeBannersTableReady()) {
            return $this->missingTableRedirect();
        }

        return view('admin.home-banners.edit', compact('banner'));
    }

    public function update(Request $request, HomeBanner $banner)
    {
        if (! $this->homeBannersTableReady()) {
            return $this->missingTableRedirect();
        }

        $validated = $this->validateBanner($request, $banner);
        $payload = $this->buildPayload($request, $validated);

        if ($request->hasFile('image')) {
            if (filled($banner->image_path) && Storage::disk('public')->exists($banner->image_path)) {
                Storage::disk('public')->delete($banner->image_path);
            }

            $payload['image_path'] = $request->file('image')->store('home-banners', 'public');
        }

        $banner->update($payload);

        return redirect()
            ->route('admin.home-banners.index')
            ->with('success', 'Banner atualizado com sucesso!');
    }

    public function destroy(HomeBanner $banner)
    {
        if (! $this->homeBannersTableReady()) {
            return $this->missingTableRedirect();
        }

        if (filled($banner->image_path) && Storage::disk('public')->exists($banner->image_path)) {
            Storage::disk('public')->delete($banner->image_path);
        }

        $banner->delete();

        return redirect()
            ->route('admin.home-banners.index')
            ->with('success', 'Banner removido com sucesso!');
    }

    protected function validateBanner(Request $request, ?HomeBanner $banner = null): array
    {
        $imageRule = $banner ? ['nullable', 'image', 'max:10240'] : ['required', 'image', 'max:10240'];

        return $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'badge' => ['nullable', 'string', 'max:80'],
                'title' => ['required', 'string', 'max:255'],
                'subtitle' => ['nullable', 'string', 'max:255'],
                'description' => ['nullable', 'string', 'max:1200'],
                'primary_button_text' => ['nullable', 'string', 'max:60'],
                'primary_button_url' => ['nullable', 'string', 'max:255'],
                'secondary_button_text' => ['nullable', 'string', 'max:60'],
                'secondary_button_url' => ['nullable', 'string', 'max:255'],
                'image' => $imageRule,
                'image_alt' => ['nullable', 'string', 'max:255'],
                'sort_order' => ['nullable', 'integer', 'min:0'],
            ],
            [
                'image.max' => 'A imagem do banner deve ter no máximo 10 MB.',
            ],
        );
    }

    protected function buildPayload(Request $request, array $validated): array
    {
        return [
            'name' => trim($validated['name']),
            'badge' => $this->nullIfBlank($validated['badge'] ?? null),
            'title' => trim($validated['title']),
            'subtitle' => $this->nullIfBlank($validated['subtitle'] ?? null),
            'description' => $this->nullIfBlank($validated['description'] ?? null),
            'primary_button_text' => $this->nullIfBlank($validated['primary_button_text'] ?? null),
            'primary_button_url' => $this->nullIfBlank($validated['primary_button_url'] ?? null),
            'secondary_button_text' => $this->nullIfBlank($validated['secondary_button_text'] ?? null),
            'secondary_button_url' => $this->nullIfBlank($validated['secondary_button_url'] ?? null),
            'image_alt' => $this->nullIfBlank($validated['image_alt'] ?? null),
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active'),
        ];
    }

    protected function nullIfBlank(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $trimmed = trim($value);

        return $trimmed === '' ? null : $trimmed;
    }

    protected function homeBannersTableReady(): bool
    {
        return Schema::hasTable('home_banners');
    }

    protected function missingTableRedirect()
    {
        return redirect()
            ->route('admin.home-banners.index')
            ->with('error', 'Execute as migrations para habilitar o gerenciador de banners da home.');
    }
}
