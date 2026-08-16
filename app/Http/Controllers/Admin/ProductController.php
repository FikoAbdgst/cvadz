<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::with('category');

        if ($search = $request->input('q')) {
            $query->where('name', 'like', "%{$search}%");
        }

        $products = $query->latest()->paginate(10)->withQueryString();

        return view('admin.products.index', ['products' => $products, 'search' => $search]);
    }

    public function create(): View
    {
        return view('admin.products.create', ['categories' => Category::orderBy('name')->get()]);
    }

    public function store(ProductRequest $request): RedirectResponse
    {
        $product = Product::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => $request->slug ?: Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->filled('price') ? $request->price : null,
            'is_featured' => $request->boolean('is_featured'),
        ]);

        $this->syncSpecifications($product, $request->input('specifications', []));
        $this->syncVideos($product, $request->input('videos', []));
        $this->storeImages($product, $request->file('images', []));

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $produk): View
    {
        $produk->load(['images', 'specifications', 'videos']);

        return view('admin.products.edit', [
            'product' => $produk,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function update(ProductRequest $request, Product $produk): RedirectResponse
    {
        $produk->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => $request->slug ?: Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->filled('price') ? $request->price : null,
            'is_featured' => $request->boolean('is_featured'),
        ]);

        $this->syncSpecifications($produk, $request->input('specifications', []));
        $this->syncVideos($produk, $request->input('videos', []));

        if ($request->filled('primary_image')) {
            $produk->images()->update(['is_primary' => false]);
            $produk->images()->whereKey($request->primary_image)->update(['is_primary' => true]);
        }

        foreach ($request->input('delete_images', []) as $imageId) {
            $image = $produk->images()->find($imageId);

            if ($image) {
                Storage::disk('public')->delete($image->image_path);
                $image->delete();
            }
        }

        $this->storeImages($produk, $request->file('images', []));

        return redirect()->route('admin.products.edit', $produk)
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $produk): RedirectResponse
    {
        foreach ($produk->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $produk->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    private function syncSpecifications(Product $product, array $specifications): void
    {
        $product->specifications()->delete();

        $rows = collect($specifications)
            ->filter(fn ($spec) => filled($spec['spec_key'] ?? null))
            ->map(fn ($spec) => [
                'spec_key' => $spec['spec_key'],
                'spec_value' => $spec['spec_value'] ?? '',
            ])
            ->values()
            ->all();

        if ($rows) {
            $product->specifications()->createMany($rows);
        }
    }

    private function syncVideos(Product $product, array $videos): void
    {
        $product->videos()->delete();

        $rows = collect($videos)
            ->filter(fn ($video) => filled($video['video_url'] ?? null))
            ->map(fn ($video) => [
                'video_url' => $video['video_url'],
                'caption' => $video['caption'] ?? null,
            ])
            ->values()
            ->all();

        if ($rows) {
            $product->videos()->createMany($rows);
        }
    }

    private function storeImages(Product $product, array $files): void
    {
        $hasPrimary = $product->images()->where('is_primary', true)->exists();

        foreach ($files as $index => $file) {
            $path = $file->store('products', 'public');

            $product->images()->create([
                'image_path' => $path,
                'is_primary' => ! $hasPrimary && $index === 0,
            ]);
        }
    }
}
