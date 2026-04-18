<?php

namespace App\Http\Controllers;

use App\Models\Growth;
use App\Models\Sheep;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class GrowthController extends Controller
{
    public function index(): View
    {
        $growths = Growth::with('sheep')->latest('date')->paginate(15);

        return view('growths.index', compact('growths'));
    }

    public function create(): View
    {
        return $this->formView(
            growth: new Growth(),
            pageTitle: 'Tambah Catatan Pertumbuhan',
            submitLabel: 'Simpan Data',
            action: route('growths.store'),
        );
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'sheep_id' => ['required', 'exists:sheep,id'],
            'weight' => ['required', 'numeric', 'min:0'],
            'target' => ['required', 'numeric', 'min:0'],
            'date' => ['required', 'date'],
        ]);

        Growth::create($validated);

        return redirect()->route('growths.index')->with('success', 'Data pertumbuhan berhasil ditambahkan.');
    }

    public function show(Growth $growth): View
    {
        $growth->load('sheep');

        return view('growths.show', compact('growth'));
    }

    public function edit(Growth $growth): View
    {
        return $this->formView(
            growth: $growth,
            pageTitle: 'Edit Catatan Pertumbuhan',
            submitLabel: 'Perbarui Data',
            action: route('growths.update', $growth),
            method: 'PUT',
        );
    }

    public function update(Request $request, Growth $growth): RedirectResponse
    {
        $validated = $request->validate([
            'sheep_id' => ['required', 'exists:sheep,id'],
            'weight' => ['required', 'numeric', 'min:0'],
            'target' => ['required', 'numeric', 'min:0'],
            'date' => ['required', 'date'],
        ]);

        $growth->update($validated);

        return redirect()->route('growths.index')->with('success', 'Data pertumbuhan berhasil diperbarui.');
    }

    public function destroy(Growth $growth): RedirectResponse
    {
        $growth->delete();

        return redirect()->route('growths.index')->with('success', 'Data pertumbuhan berhasil dihapus.');
    }

    private function formView(
        Growth $growth,
        string $pageTitle,
        string $submitLabel,
        string $action,
        string $method = 'POST',
    ): View {
        return view('growths.form', [
            'growth' => $growth,
            'pageTitle' => $pageTitle,
            'submitLabel' => $submitLabel,
            'action' => $action,
            'method' => $method,
            'sheep' => $this->sheep(),
        ]);
    }

    private function sheep(): Collection
    {
        return Sheep::orderBy('code')->get();
    }
}
