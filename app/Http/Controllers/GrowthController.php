<?php

namespace App\Http\Controllers;

use App\Models\Growth;
use App\Models\Sheep;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Symfony\Component\HttpFoundation\StreamedResponse;

class GrowthController extends Controller
{
    public function index(Request $request): View|Response|StreamedResponse
    {
        $filters = [
            'date_range' => $request->query('date_range'),
            'sheep_id'   => $request->query('sheep_id'),
            'type_id'    => $request->query('type_id'),
            'status'     => $request->query('status'),
        ];

        $query = Growth::with('sheep.sheepType');

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

        // Filter: Type ID
        $query->when($filters['type_id'], function ($q, $typeId) {
            $q->whereHas('sheep', fn($s) => $s->where('type_id', $typeId));
        });

        // Filter: Status
        $query->when($filters['status'], function ($q, $status) {
            if ($status === 'reached') {
                $q->whereColumn('weight', '>=', 'target');
            } elseif ($status === 'not_reached') {
                $q->whereColumn('weight', '<', 'target');
            }
        });

        // Export PDF
        if ($request->query('export') === 'pdf') {
            $growths = $query->latest('date')->get();
            $pdf = Pdf::setOptions(['isRemoteEnabled' => true, 'isHtml5ParserEnabled' => true])
                ->loadView('growths.export.pdf', compact('growths', 'filters'))
                ->setPaper('a4', 'landscape');
            return $pdf->download('laporan-pertumbuhan-' . now()->format('Y-m-d') . '.pdf');
        }

        // Export Excel
        if ($request->query('export') === 'excel') {
            $growths = $query->latest('date')->get();
            return $this->exportExcel($growths, $filters);
        }

        $growths     = $query->latest('date')->paginate(15)->withQueryString();
        $sheepOptions = Sheep::with('sheepType')->orderBy('code')->get();
        $sheepTypes   = \App\Models\SheepType::orderBy('name')->get();

        return view('growths.index', compact('growths', 'filters', 'sheepOptions', 'sheepTypes'));
    }

    private function exportExcel(Collection $growths, array $filters): StreamedResponse
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Pertumbuhan Domba');

        // ── Branding Row 1: Company name ──────────────────────────────────────
        $sheet->mergeCells('A1:I1');
        $sheet->setCellValue('A1', 'DOMBA LOKA — Sistem Manajemen Ternak');
        $sheet->getStyle('A1')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 16, 'color' => ['argb' => 'FF1E40AF']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(28);

        // ── Row 2: Report title ───────────────────────────────────────────────
        $sheet->mergeCells('A2:I2');
        $sheet->setCellValue('A2', 'LAPORAN PERTUMBUHAN DOMBA');
        $sheet->getStyle('A2')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 13, 'color' => ['argb' => 'FF0F172A']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(2)->setRowHeight(22);

        // ── Row 3: Subtitle ───────────────────────────────────────────────────
        $sheet->mergeCells('A3:I3');
        $sheet->setCellValue('A3', 'Daftar Pemantauan Berat & Perkembangan Ternak');
        $sheet->getStyle('A3')->applyFromArray([
            'font'      => ['size' => 10, 'color' => ['argb' => 'FF64748B']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
        ]);

        // ── Row 4: Meta info ──────────────────────────────────────────────────
        $sheet->mergeCells('A4:I4');
        $meta = 'Tanggal Cetak: ' . now()->format('d/m/Y H:i') . '   |   Total Data: ' . $growths->count() . ' record';
        if (!empty($filters['date_range'])) {
            $meta .= '   |   Periode: ' . $filters['date_range'];
        }
        $sheet->setCellValue('A4', $meta);
        $sheet->getStyle('A4')->applyFromArray([
            'font'      => ['size' => 10, 'color' => ['argb' => 'FF475569']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
        ]);
        $sheet->getRowDimension(4)->setRowHeight(18);

        // ── Row 5: Spacer ─────────────────────────────────────────────────────
        $sheet->getRowDimension(5)->setRowHeight(8);

        // ── Row 6: Table headers ──────────────────────────────────────────────
        $headers = ['No', 'Tanggal', 'Kode Domba', 'Jenis Domba',
                    'Berat Awal (kg)', 'Berat Aktual (kg)', 'Kenaikan (kg)', 'Target (kg)', 'Status'];
        $cols    = range('A', 'I');
        foreach ($headers as $i => $header) {
            $sheet->setCellValue($cols[$i] . '6', $header);
        }
        $sheet->getStyle('A6:I6')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 11, 'color' => ['argb' => 'FFFFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1E3A8A']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FF1E3A8A']]],
        ]);
        $sheet->getRowDimension(6)->setRowHeight(25);

        // ── Data rows ─────────────────────────────────────────────────────────
        foreach ($growths as $i => $g) {
            $row        = $i + 7;
            $isReached  = $g->weight >= $g->target;
            $statusLabel = $isReached ? 'Mencapai Target' : 'Belum Mencapai Target';
            $bgEven     = 'FFF8FAFC';
            $bgOdd      = 'FFFFFFFF';
            $rowBg      = ($i % 2 === 0) ? $bgOdd : $bgEven;

            $rowData = [
                'A' => $i + 1,
                'B' => \Carbon\Carbon::parse($g->date)->format('d/m/Y'),
                'C' => $g->sheep->code ?? '-',
                'D' => $g->sheep->sheepType->name ?? '-',
                'E' => number_format($g->previous_weight, 1),
                'F' => number_format($g->actual_weight, 1),
                'G' => ($g->weight >= 0 ? '+' : '') . number_format($g->weight, 1),
                'H' => number_format($g->target, 1),
                'I' => $statusLabel,
            ];

            foreach ($rowData as $col => $val) {
                $sheet->setCellValue($col . $row, $val);
            }

            // Row base style
            $sheet->getStyle("A{$row}:I{$row}")->applyFromArray([
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF' . ltrim($rowBg, '#')]],
                'font'      => ['size' => 11],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFCBD5E1']]],
            ]);

            // Kode domba — bold blue
            $sheet->getStyle("C{$row}")->applyFromArray([
                'font' => ['bold' => true, 'color' => ['argb' => 'FF1E40AF']],
            ]);
            // Berat aktual — bold
            $sheet->getStyle("F{$row}")->applyFromArray([
                'font' => ['bold' => true],
            ]);
            // Kenaikan — green / red
            $sheet->getStyle("G{$row}")->applyFromArray([
                'font' => ['bold' => true, 'color' => ['argb' => $g->weight >= 0 ? 'FF15803D' : 'FFDC2626']],
            ]);
            // Status badge colour
            $sheet->getStyle("I{$row}")->applyFromArray([
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => $isReached ? 'FFDCFCE7' : 'FFFEE2E2']],
                'font' => ['bold' => true, 'color' => ['argb' => $isReached ? 'FF166534' : 'FF991B1B']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ]);

            // Right-align numeric columns
            foreach (['E', 'F', 'G', 'H'] as $numCol) {
                $sheet->getStyle("{$numCol}{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            }
            // Center No & Tanggal
            $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("B{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $sheet->getRowDimension($row)->setRowHeight(20);
        }

        // ── Summary row ───────────────────────────────────────────────────────
        $lastRow = $growths->count() + 7;
        $reached  = $growths->filter(fn($g) => $g->weight >= $g->target)->count();
        $avgGain  = $growths->count() > 0 ? $growths->avg('weight') : 0;

        $sheet->mergeCells("A{$lastRow}:I{$lastRow}");
        $sheet->setCellValue("A{$lastRow}", "Ringkasan: {$reached} dari {$growths->count()} domba mencapai target | Rata-rata kenaikan: " . number_format($avgGain, 1) . ' kg');
        $sheet->getStyle("A{$lastRow}:I{$lastRow}")->applyFromArray([
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFF1F5F9']],
            'font'      => ['bold' => true, 'size' => 11, 'color' => ['argb' => 'FF334155']],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            'borders'   => ['outline' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['argb' => 'FF94A3B8']]],
        ]);
        $sheet->getRowDimension($lastRow)->setRowHeight(25);

        // ── Column widths ─────────────────────────────────────────────────────
        $widths = [
            'A' => 10,  // No
            'B' => 18,  // Tanggal
            'C' => 22,  // Kode Domba
            'D' => 25,  // Jenis Domba
            'E' => 22,  // Berat Awal (kg)
            'F' => 22,  // Berat Aktual (kg)
            'G' => 20,  // Kenaikan (kg)
            'H' => 18,  // Target (kg)
            'I' => 28,  // Status
        ];
        foreach ($widths as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }

        // ── Freeze panes & auto-filter ────────────────────────────────────────
        $sheet->freezePane('A7');
        $sheet->setAutoFilter('A6:I6');

        // ── Page setup ───────────────────────────────────────────────────────
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
        $sheet->getPageSetup()->setFitToWidth(1);
        $sheet->getPageSetup()->setFitToHeight(0);

        // ── Stream output ─────────────────────────────────────────────────────
        $filename = 'laporan-pertumbuhan-' . now()->format('Y-m-d') . '.xlsx';
        $writer   = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control'       => 'max-age=0',
        ]);
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
            'sheep_id'     => ['required', 'exists:sheep,id'],
            'actual_weight'=> ['required', 'numeric', 'min:0'],
            'target'       => ['required', 'numeric', 'min:0'],
            'date'         => ['required', 'date'],
        ]);

        $sheep = Sheep::findOrFail($validated['sheep_id']);
        $previousWeight  = (float) $sheep->weight;
        $actualWeight    = (float) $validated['actual_weight'];
        $weightGain      = $actualWeight - $previousWeight;

        Growth::create([
            'sheep_id'        => $sheep->id,
            'actual_weight'   => $actualWeight,
            'previous_weight' => $previousWeight,
            'weight'          => $weightGain,  // selisih kenaikan berat
            'target'          => $validated['target'],
            'date'            => $validated['date'],
        ]);

        // Update berat domba ke berat aktual terbaru
        $sheep->update(['weight' => $actualWeight]);

        // Tambah umur domba +1 bulan setiap pencatatan pertumbuhan
        $sheep->increment('age');

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
            'sheep_id'     => ['required', 'exists:sheep,id'],
            'actual_weight'=> ['required', 'numeric', 'min:0'],
            'target'       => ['required', 'numeric', 'min:0'],
            'date'         => ['required', 'date'],
        ]);

        $sheep       = Sheep::findOrFail($validated['sheep_id']);
        $newActual   = (float) $validated['actual_weight'];

        // Kembalikan berat domba ke sebelum record lama, lalu terapkan berat aktual baru
        $restoredWeight = (float) $growth->previous_weight;
        $weightGain     = $newActual - $restoredWeight;

        $growth->update([
            'sheep_id'        => $sheep->id,
            'actual_weight'   => $newActual,
            'previous_weight' => $restoredWeight,
            'weight'          => $weightGain,
            'target'          => $validated['target'],
            'date'            => $validated['date'],
        ]);

        // Update berat domba ke berat aktual terbaru
        $sheep->update(['weight' => $newActual]);

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
