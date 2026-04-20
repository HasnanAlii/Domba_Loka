<?php

namespace App\Http\Controllers;

use App\Models\Sheep;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class SheepController extends Controller
{
    public function catalog(Request $request): View
    {
        $filters = [
            'search' => $request->get('search', ''),
            'type_id' => $request->get('type_id', null),
            'sort' => $request->get('sort', null),
        ];

        $query = Sheep::with('sheepType')
            ->where('status', 'tersedia')
            ->whereIn('condition', ['Sangat Baik', 'Baik', 'Cukup','sehat'])
            ->when($filters['search'], function ($q, $search) {
                return $q->where('code', 'like', "%{$search}%");
            })
            ->when($filters['type_id'], function ($q, $typeId) {
                return $q->where('type_id', $typeId);
            });

        if ($filters['sort'] === 'price_high') {
            $query->orderBy('price', 'desc');
        } elseif ($filters['sort'] === 'price_low') {
            $query->orderBy('price', 'asc');
        } else {
            $query->latest();
        }

        $sheep = $query->paginate(9)->withQueryString();
        $sheepTypes = \App\Models\SheepType::orderBy('name')->get();

        return view('public.catalog.index', compact('sheep', 'sheepTypes', 'filters'));
    }

    public function catalogDetail(Sheep $sheep): View
    {
        $sheep->load(['sheepType', 'photos']);
        
        // Simulasikan rekomendasi domba serupa
        $recommendations = Sheep::with('sheepType')
            ->where('id', '!=', $sheep->id)
            ->where('type_id', $sheep->type_id)
            ->where('status', 'tersedia')
            ->take(4)
            ->get();

        return view('public.catalog.show', compact('sheep', 'recommendations'));
    }

    public function index(Request $request): View
    {
        $filters = [
            'search' => $request->get('search', ''),
            'type_id' => $request->get('type_id', ''),
            'status' => $request->get('status', ''),
            'condition' => $request->get('condition', ''),
        ];

        $query = Sheep::with('sheepType')
            ->when($filters['search'], function ($q, $search) {
                return $q->where('code', 'like', "%{$search}%");
            })
            ->when($filters['type_id'], function ($q, $typeId) {
                return $q->where('type_id', $typeId);
            })
            ->when($filters['status'], function ($q, $status) {
                return $q->where('status', $status);
            })
            ->when($filters['condition'], function ($q, $condition) {
                return $q->where('condition', $condition);
            })
            // Default condition: show only 'tersedia' ONLY on initial load (no query params)
            ->when(!$request->hasAny(['search', 'type_id', 'status', 'condition']), function ($q) {
                return $q->where('status', 'tersedia');
            })
            ->latest();

        $sheep = $query->paginate(15)->withQueryString();
        $sheepTypes = \App\Models\SheepType::orderBy('name')->get();

        return view('sheep.index', compact('sheep', 'sheepTypes', 'filters'));
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
            'status' => ['required', 'string', 'in:tersedia,terjual,sakit,mati,hilang'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'additional_photos' => ['nullable', 'array'],
            'additional_photos.*' => ['image', 'max:4096'],
            'age' => ['required', 'numeric', 'min:0'],
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('sheep', 'public');
        }

        $sheep = Sheep::create($validated);

        if ($request->hasFile('additional_photos')) {
            foreach ($request->file('additional_photos') as $photoFile) {
                $path = $photoFile->store('sheep', 'public');
                $sheep->photos()->create(['path' => $path]);
            }
        }

        $sheep->load('sheepType');

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Domba berhasil ditambahkan.',
                'sheep' => $sheep,
                'redirect' => route('sheep.index'),
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

    public function update(Request $request, Sheep $sheep): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'type_id' => ['required', 'exists:sheep_types,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'weight' => ['required', 'numeric', 'min:0'],
            'condition' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255', 'unique:sheep,code,'.$sheep->id],
            'status' => ['required', 'string', 'in:tersedia,terjual,sakit,mati,hilang'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'additional_photos' => ['nullable', 'array'],
            'additional_photos.*' => ['image', 'max:4096'],
            'age' => ['required', 'numeric', 'min:0'],
        ]);

        if ($request->hasFile('photo')) {
            if ($sheep->photo) {
                Storage::disk('public')->delete($sheep->photo);
            }
            $validated['photo'] = $request->file('photo')->store('sheep', 'public');
        }

        $sheep->update($validated);

        if ($request->hasFile('additional_photos')) {
            foreach ($request->file('additional_photos') as $photoFile) {
                $path = $photoFile->store('sheep', 'public');
                $sheep->photos()->create(['path' => $path]);
            }
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Data domba berhasil diperbarui.', 'redirect' => route('sheep.index')]);
        }

        return redirect()->route('sheep.index')->with('success', 'Data domba berhasil diperbarui.');
    }

    public function destroy(Sheep $sheep): RedirectResponse|JsonResponse
    {
        if ($sheep->transactions()->exists() || $sheep->growths()->exists()) {
            if (request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Domba ini terikat dengan transaksi atau data perkembangan.']);
            }
            return redirect()->route('sheep.index')->with('error', 'Domba ini terikat dengan transaksi atau data perkembangan.');
        }

        // Hapus foto-foto di galeri
        foreach ($sheep->photos as $photo) {
            Storage::disk('public')->delete($photo->path);
        }

        // Hapus foto utama
        if ($sheep->photo) {
            Storage::disk('public')->delete($sheep->photo);
        }

        $sheep->delete();

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Domba berhasil dihapus.', 'redirect' => route('sheep.index')]);
        }

        return redirect()->route('sheep.index')->with('success', 'Domba berhasil dihapus.');
    }
}
