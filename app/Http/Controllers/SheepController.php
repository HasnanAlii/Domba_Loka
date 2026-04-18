<?php

namespace App\Http\Controllers;

use App\Models\Sheep;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class SheepController extends Controller
{
    public function index(): View
    {
        $sheep = Sheep::with('sheepType')->latest()->paginate(15);

        return view('sheep.index', compact('sheep'));
    }

    public function create(): View
    {
        return view('sheep.form', [
            'sheep' => new Sheep(),
            'pageTitle' => 'Tambah Domba',
            'submitLabel' => 'Simpan Data',
            'action' => route('sheep.store'),
            'method' => 'POST',
            'sheepTypes' => \App\Models\SheepType::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'type_id' => ['required', 'exists:sheep_types,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'weight' => ['required', 'numeric', 'min:0'],
            'condition' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255', 'unique:sheep,code'],
        ]);

        $sheep = Sheep::create($validated);

        $sheep->load('sheepType');

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Domba berhasil ditambahkan.',
                'sheep' => $sheep
            ]);
        }

        return redirect()->route('sheep.index')->with('success', 'Domba berhasil ditambahkan.');
    }

    public function show(Sheep $sheep): View
    {
        $sheep->load(['growths' => fn ($q) => $q->latest('date'), 'transactions']);

        return view('sheep.show', compact('sheep'));
    }

    public function edit(Sheep $sheep): View
    {
        return view('sheep.form', [
            'sheep' => $sheep,
            'pageTitle' => 'Edit Domba',
            'submitLabel' => 'Perbarui Data',
            'action' => route('sheep.update', $sheep),
            'method' => 'PUT',
            'sheepTypes' => \App\Models\SheepType::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Sheep $sheep): RedirectResponse
    {
        $validated = $request->validate([
            'type_id' => ['required', 'exists:sheep_types,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'weight' => ['required', 'numeric', 'min:0'],
            'condition' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255', 'unique:sheep,code,'.$sheep->id],
        ]);

        $sheep->update($validated);

        return redirect()->route('sheep.index')->with('success', 'Data domba berhasil diperbarui.');
    }

    public function destroy(Sheep $sheep): RedirectResponse
    {
        $sheep->delete();

        return redirect()->route('sheep.index')->with('success', 'Domba berhasil dihapus.');
    }
}
