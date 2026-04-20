<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(): View
    {
        $customers = Customer::latest()->paginate(15);

        return view('customers.index', compact('customers'));
    }

    public function create(): View
    {
        return view('customers.form', [
            'customer' => new Customer(),
            'pageTitle' => 'Tambah Pelanggan',
            'submitLabel' => 'Simpan Data',
            'action' => route('customers.store'),
            'method' => 'POST',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string'],
        ]);

        $customer = Customer::create($validated);
        
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pelanggan berhasil ditambahkan.',
                'customer' => $customer,
                'redirect' => route('customers.index'),
            ]);
        }

        return redirect()->route('customers.index')->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    public function show(Customer $customer): View
    {
        $customer->load('transactions');

        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer): View
    {
        return view('customers.form', [
            'customer' => $customer,
            'pageTitle' => 'Edit Pelanggan',
            'submitLabel' => 'Perbarui Data',
            'action' => route('customers.update', $customer),
            'method' => 'PUT',
        ]);
    }

    public function update(Request $request, Customer $customer): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string'],
        ]);

        $customer->update($validated);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Data pelanggan berhasil diperbarui.', 'redirect' => route('customers.index')]);
        }

        return redirect()->route('customers.index')->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    public function destroy(Customer $customer): RedirectResponse|JsonResponse
    {
        if ($customer->transactions()->exists()) {
            if (request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Pelanggan ini terkait dengan data transaksi.']);
            }
            return redirect()->route('customers.index')->with('error', 'Pelanggan ini terkait dengan data transaksi.');
        }

        $customer->delete();

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Pelanggan berhasil dihapus.', 'redirect' => route('customers.index')]);
        }

        return redirect()->route('customers.index')->with('success', 'Pelanggan berhasil dihapus.');
    }
}
