<?php

namespace App\Http\Controllers;

use App\Models\SheepType;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SheepTypeController extends Controller
{
    public function index(): View
    {
        $types = SheepType::withCount('sheep')->latest()->paginate(15);
        return view('sheep_types.index', compact('types'));
    }

    public function create(): View
    {
        return view('sheep_types.form', [
            'type' => new SheepType(),
            'pageTitle' => 'Tambah Kategori Domba',
            'submitLabel' => 'Simpan',
            'action' => route('sheep-types.store'),
            'method' => 'POST',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:sheep_types,name'],
            'description' => ['nullable', 'string'],
        ]);

        SheepType::create($validated);
        return redirect()->route('sheep-types.index')->with('success', 'Kategori domba berhasil ditambahkan.');
    }

    public function show(SheepType $sheepType)
    {
        return redirect()->route('sheep-types.edit', $sheepType);
    }

    public function edit(SheepType $sheepType): View
    {
        return view('sheep_types.form', [
            'type' => $sheepType,
            'pageTitle' => 'Edit Kategori Domba',
            'submitLabel' => 'Perbarui',
            'action' => route('sheep-types.update', $sheepType),
            'method' => 'PUT',
        ]);
    }

    public function update(Request $request, SheepType $sheepType): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:sheep_types,name,'.$sheepType->id],
            'description' => ['nullable', 'string'],
        ]);

        $sheepType->update($validated);
        return redirect()->route('sheep-types.index')->with('success', 'Kategori domba berhasil diperbarui.');
    }

    public function destroy(SheepType $sheepType): RedirectResponse
    {
        if ($sheepType->sheep()->count() > 0) {
            return redirect()->route('sheep-types.index')->with('error', 'Gagal: Kategori ini sedang digunakan oleh data domba.');
        }

        $sheepType->delete();
        return redirect()->route('sheep-types.index')->with('success', 'Kategori domba berhasil dihapus.');
    }
}
