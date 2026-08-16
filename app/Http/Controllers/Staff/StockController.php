<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\StockRequest;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StockController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::with('category');

        if ($search = trim((string) $request->input('q'))) {
            $query->where('name', 'like', "%{$search}%");
        }

        $products = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('staff.stock.index', [
            'products' => $products,
            'search' => trim((string) $request->input('q')),
        ]);
    }

    public function update(StockRequest $request): RedirectResponse
    {
        $product = Product::findOrFail($request->product_id);
        $quantity = (int) $request->quantity;

        if ($request->action === 'tambah') {
            $product->increment('stock', $quantity);
            $label = 'Stok bertambah';
        } else {
            if ($product->stock < $quantity) {
                return back()->withErrors(['quantity' => 'Stok tersisa '.$product->stock.', tidak bisa dikurangi sebanyak '.$quantity.'.'])
                    ->withInput();
            }

            $product->decrement('stock', $quantity);
            $label = 'Stok berkurang';
        }

        return redirect()->route('staff.stock.index', ['q' => $request->input('q')])
            ->with('success', "{$label} untuk {$product->name} (sisa {$product->fresh()->stock}).");
    }
}
