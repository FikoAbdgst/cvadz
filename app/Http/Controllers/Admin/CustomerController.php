<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(): RedirectResponse
    {
        return redirect()->route('admin.sales.index', ['tab' => 'pelanggan']);
    }

    public function create(): View
    {
        return view('admin.customers.create');
    }

    public function store(CustomerRequest $request): RedirectResponse
    {
        Customer::create($request->validated());

        return redirect()->route('admin.sales.index', ['tab' => 'pelanggan'])
            ->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    public function edit(Customer $pelanggan): View
    {
        return view('admin.customers.edit', ['customer' => $pelanggan]);
    }

    public function update(CustomerRequest $request, Customer $pelanggan): RedirectResponse
    {
        $pelanggan->update($request->validated());

        return redirect()->route('admin.sales.index', ['tab' => 'pelanggan'])
            ->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    public function destroy(Customer $pelanggan): RedirectResponse
    {
        $pelanggan->delete();

        return redirect()->route('admin.sales.index', ['tab' => 'pelanggan'])
            ->with('success', 'Pelanggan berhasil dihapus.');
    }
}
