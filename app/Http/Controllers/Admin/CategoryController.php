<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(Request $request): View
    {
        $type = $request->input('type', 'produk');

        $categories = Category::when($type === 'layanan', function ($query) {
            return $query->withCount('services');
        }, function ($query) {
            return $query->withCount('products');
        })
            ->where('type', $type)
            ->orderBy('name')
            ->paginate(15);

        return view('admin.categories.index', [
            'categories' => $categories,
            'type' => $type,
        ]);
    }

    public function create(): View
    {
        return view('admin.categories.create');
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
        Category::create([
            'name' => $request->name,
            'slug' => $request->slug ?: Str::slug($request->name),
            'type' => $request->input('type', 'produk'),
        ]);

        return redirect()->route('admin.categories.index', ['type' => $request->input('type', 'produk')])
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $kategori): View
    {
        return view('admin.categories.edit', ['category' => $kategori]);
    }

    public function update(CategoryRequest $request, Category $kategori): RedirectResponse
    {
        $kategori->update([
            'name' => $request->name,
            'slug' => $request->slug ?: Str::slug($request->name),
            'type' => $request->input('type', 'produk'),
        ]);

        return redirect()->route('admin.categories.index', ['type' => $kategori->type])
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $kategori): RedirectResponse
    {
        $count = $kategori->type === 'layanan' ? $kategori->services()->count() : $kategori->products()->count();

        if ($count > 0) {
            $label = $kategori->type === 'layanan' ? 'layanan' : 'produk';

            return back()->with('error', "Kategori tidak dapat dihapus karena masih memiliki $label.");
        }

        $type = $kategori->type;
        $kategori->delete();

        return redirect()->route('admin.categories.index', ['type' => $type])
            ->with('success', 'Kategori berhasil dihapus.');
    }
}
