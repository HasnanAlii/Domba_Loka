<?php

namespace App\Http\Controllers;

use App\Models\Growth;
use App\Models\Sheep;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class GrowthController extends Controller
{
    public function index(Request $request): View
    {
        $filters = [
            'date_range' => $request->query('date_range'),
            'sheep_id' => $request->query('sheep_id'),
            'status' => $request->query('status'), // 'reached' or 'not_reached'
        ];

        $query = Growth::with('sheep');

        // Filter: Date Range
        if ($filters['date_range']) {
            $dates = explode(' to ', $filters['date_range']);
            if (count($dates) === 2) {
                $query->whereBetween('date', [$dates[0], $dates[1]]);
            } else {
                $query->whereDate('date', $dates[0]);
            }
        }

        // Filter: Sheep ID
        $query->when($filters['sheep_id'], function ($q, $sheepId) {
            $q->where('sheep_id', $sheepId);
        });

        // Filter: Status
        $query->when($filters['status'], function ($q, $status) {
            if ($status === 'reached') {
                $q->whereColumn('weight', '>=', 'target');
            } elseif ($status === 'not_reached') {
                $q->whereColumn('weight', '<', 'target');
            }
        });

        $growths = $query->latest('date')->paginate(15)->withQueryString();
        $sheepOptions = Sheep::orderBy('code')->get();

        return view('growths.index', compact('growths', 'filters', 'sheepOptions'));
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

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'sheep_id' => ['required', 'exists:sheep,id'],
            'weight' => ['required', 'numeric', 'min:0'],
            'target' => ['required', 'numeric', 'min:0'],
            'date' => ['required', 'date'],
        ]);

        Growth::create($validated);

        // Tambah umur domba +1 bulan setiap pencatatan pertumbuhan
        Sheep::where('id', $validated['sheep_id'])->increment('age');

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Data pertumbuhan berhasil ditambahkan.', 'redirect' => route('growths.index')]);
        }

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

    public function update(Request $request, Growth $growth): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'sheep_id' => ['required', 'exists:sheep,id'],
            'weight' => ['required', 'numeric', 'min:0'],
            'target' => ['required', 'numeric', 'min:0'],
            'date' => ['required', 'date'],
        ]);

        $growth->update($validated);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Data pertumbuhan berhasil diperbarui.', 'redirect' => route('growths.index')]);
        }

        return redirect()->route('growths.index')->with('success', 'Data pertumbuhan berhasil diperbarui.');
    }

    public function destroy(Growth $growth): RedirectResponse|JsonResponse
    {
        $growth->delete();

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Data pertumbuhan berhasil dihapus.', 'redirect' => route('growths.index')]);
        }

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
