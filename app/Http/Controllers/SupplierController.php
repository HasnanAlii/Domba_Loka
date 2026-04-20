<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupplierController extends Controller
{
    public function index(): View
    {
        $suppliers = Supplier::latest()->paginate(15);

        return view('suppliers.index', compact('suppliers'));
    }

    public function create(): View
    {
        return view('suppliers.form', [
            'supplier' => new Supplier(),
            'pageTitle' => 'Tambah Supplier',
            'submitLabel' => 'Simpan Data',
            'action' => route('suppliers.store'),
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

        $supplier = Supplier::create($validated);
        
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Supplier berhasil ditambahkan.',
                'supplier' => $supplier,
                'redirect' => route('suppliers.index'),
            ]);
        }

        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function show(Supplier $supplier): View
    {
        $supplier->load('transactions');

        return view('suppliers.show', compact('supplier'));
    }

    public function edit(Supplier $supplier): View
    {
        return view('suppliers.form', [
            'supplier' => $supplier,
            'pageTitle' => 'Edit Supplier',
            'submitLabel' => 'Perbarui Data',
            'action' => route('suppliers.update', $supplier),
            'method' => 'PUT',
        ]);
    }

    public function update(Request $request, Supplier $supplier): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string'],
        ]);

        $supplier->update($validated);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Data supplier berhasil diperbarui.', 'redirect' => route('suppliers.index')]);
        }

        return redirect()->route('suppliers.index')->with('success', 'Data supplier berhasil diperbarui.');
    }

    public function destroy(Supplier $supplier): RedirectResponse|JsonResponse
    {
        if ($supplier->transactions()->exists()) {
            if (request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Supplier ini terkait dengan data transaksi.']);
            }
            return redirect()->route('suppliers.index')->with('error', 'Supplier ini terkait dengan data transaksi.');
        }

        $supplier->delete();

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Supplier berhasil dihapus.', 'redirect' => route('suppliers.index')]);
        }

        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil dihapus.');
    }
}
