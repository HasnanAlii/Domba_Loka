<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BankAccountController extends Controller
{
    public function index(): View
    {
        $bankAccounts = BankAccount::latest()->paginate(15);

        return view('bank-accounts.index', compact('bankAccounts'));
    }

    public function create(): View
    {
        return view('bank-accounts.form', [
            'bankAccount' => new BankAccount(),
            'pageTitle' => 'Tambah Rekening Bank',
            'submitLabel' => 'Simpan Data',
            'action' => route('bank-accounts.store'),
            'method' => 'POST',
        ]);
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'account_name' => ['required', 'string', 'max:255'],
            'account_number' => ['required', 'string', 'max:50'],
            'bank_name' => ['required', 'string', 'max:255'],
            'saldo' => ['required', 'numeric', 'min:0'],
        ]);

        $bankAccount = BankAccount::create($validated);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Rekening bank berhasil ditambahkan.', 'redirect' => route('bank-accounts.index')]);
        }

        return redirect()->route('bank-accounts.index')->with('success', 'Rekening bank berhasil ditambahkan.');
    }

    public function show(Request $request, BankAccount $bankAccount): View|HttpResponse|StreamedResponse
    {
        $bankAccount->load([
            'transactions' => function ($query) {
                $query->with(['customer:id,name', 'supplier:id,name'])
                    ->latest('transaction_date');
            },
            'finances' => function ($query) {
                $query->whereNull('transaction_id')->latest();
            },
        ]);

        $cashAccount = BankAccount::query()
            ->where('account_number', 'CASH-001')
            ->first();

        // Gabungkan data transaksi dan finansial (migrasi)
        $history = collect();

        foreach ($bankAccount->transactions as $trans) {
            $history->push((object)[
                'date' => $trans->transaction_date,
                'ref' => $trans->reference_number,
                'type' => $trans->type === 'penjualan' ? 'masuk' : 'keluar',
                'party' => $trans->type === 'penjualan' ? ($trans->customer?->name ?? 'Pelanggan') : ($trans->supplier?->name ?? 'Pemasok'),
                'amount' => $trans->total_price,
                'original_type' => 'transaction'
            ]);
        }

        foreach ($bankAccount->finances as $fine) {
            $history->push((object)[
                'date' => $fine->created_at,
                'ref' => 'FIN-' . str_pad($fine->id, 5, '0', STR_PAD_LEFT),
                'type' => $fine->type === 'income' ? 'masuk' : 'keluar',
                'party' => $fine->description,
                'amount' => $fine->amount,
                'original_type' => 'finance'
            ]);
        }

        $history = $history->sortByDesc('date');

        $allAccounts = BankAccount::where('id', '!=', $bankAccount->id)->get();

        // ── Export handler ─────────────────────────────────────────────────
        if ($request->query('export') === 'pdf') {
            return $this->exportPdf($bankAccount, $history);
        }

        if ($request->query('export') === 'excel') {
            return $this->exportExcel($bankAccount, $history);
        }

        return view('bank-accounts.show', compact('bankAccount', 'cashAccount', 'history', 'allAccounts'));
    }

    private function exportPdf(BankAccount $bankAccount, Collection $history): HttpResponse
    {
        $pdf = Pdf::loadView('bank-accounts.export.pdf', compact('bankAccount', 'history'))
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'isRemoteEnabled'      => true,
                'isHtml5ParserEnabled' => true,
                'defaultFont'          => 'Poppins',
            ]);

        $slug = str($bankAccount->bank_name)->slug('-');

        return $pdf->download("laporan-rekening-{$slug}-" . now()->format('Y-m-d') . '.pdf');
    }

    private function exportExcel(BankAccount $bankAccount, Collection $history): StreamedResponse
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Riwayat Rekening');

        // ── Branding rows ─────────────────────────────────────────────────────
        $sheet->mergeCells('A1:F1');
        $sheet->setCellValue('A1', 'DOMBA LOKA — Sistem Manajemen Ternak');
        $sheet->getStyle('A1')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 16, 'color' => ['argb' => 'FF1E40AF']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(28);

        $sheet->mergeCells('A2:F2');
        $sheet->setCellValue('A2', 'LAPORAN REKENING BANK: ' . strtoupper($bankAccount->bank_name));
        $sheet->getStyle('A2')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 13, 'color' => ['argb' => 'FF0F172A']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(2)->setRowHeight(22);

        $sheet->mergeCells('A3:F3');
        $sheet->setCellValue('A3',
            'No. Rekening: ' . $bankAccount->account_number .
            '   |   Pemilik: ' . $bankAccount->account_name .
            '   |   Saldo: Rp ' . number_format($bankAccount->saldo, 0, ',', '.')
        );
        $sheet->getStyle('A3')->applyFromArray([
            'font'      => ['size' => 10, 'color' => ['argb' => 'FF64748B']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
        ]);

        $sheet->mergeCells('A4:F4');
        $sheet->setCellValue('A4',
            'Tanggal Cetak: ' . now()->format('d/m/Y H:i') .
            '   |   Total Data: ' . $history->count() . ' record'
        );
        $sheet->getStyle('A4')->applyFromArray([
            'font'      => ['size' => 10, 'color' => ['argb' => 'FF475569']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
        ]);
        $sheet->getRowDimension(4)->setRowHeight(18);
        $sheet->getRowDimension(5)->setRowHeight(8);

        // ── Table headers ─────────────────────────────────────────────────────
        $headers = ['No', 'Tanggal', 'No. Referensi', 'Pihak / Deskripsi', 'Tipe', 'Jumlah (Rp)'];
        $cols    = range('A', 'F');
        foreach ($headers as $i => $header) {
            $sheet->setCellValue($cols[$i] . '6', $header);
        }
        $sheet->getStyle('A6:F6')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 11, 'color' => ['argb' => 'FFFFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1E3A8A']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FF1E3A8A']]],
        ]);
        $sheet->getRowDimension(6)->setRowHeight(25);

        // ── Data rows ─────────────────────────────────────────────────────────
        foreach ($history->values() as $i => $item) {
            $row      = $i + 7;
            $isMasuk  = $item->type === 'masuk';
            $rowBg    = ($i % 2 === 0) ? 'FFFFFFFF' : 'FFF8FAFC';

            $rowData = [
                'A' => $i + 1,
                'B' => \Carbon\Carbon::parse($item->date)->format('d/m/Y'),
                'C' => $item->ref,
                'D' => $item->party ?? '-',
                'E' => $isMasuk ? 'Masuk' : 'Keluar',
                'F' => ($isMasuk ? '+' : '-') . number_format($item->amount, 0, ',', '.'),
            ];

            foreach ($rowData as $col => $val) {
                $sheet->setCellValue($col . $row, $val);
            }

            $sheet->getStyle("A{$row}:F{$row}")->applyFromArray([
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => $rowBg]],
                'font'      => ['size' => 11],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFCBD5E1']]],
            ]);

            // No. Ref bold blue
            $sheet->getStyle("C{$row}")->applyFromArray([
                'font' => ['bold' => true, 'color' => ['argb' => 'FF1E40AF']],
            ]);

            // Tipe badge
            $sheet->getStyle("E{$row}")->applyFromArray([
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => $isMasuk ? 'FFDCFCE7' : 'FFFEE2E2']],
                'font'      => ['bold' => true, 'color' => ['argb' => $isMasuk ? 'FF166534' : 'FF991B1B']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ]);

            // Jumlah colour
            $sheet->getStyle("F{$row}")->applyFromArray([
                'font'      => ['bold' => true, 'color' => ['argb' => $isMasuk ? 'FF15803D' : 'FFDC2626']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
            ]);

            $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("B{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getRowDimension($row)->setRowHeight(20);
        }

        // ── Summary row ───────────────────────────────────────────────────────
        $lastRow     = $history->count() + 7;
        $totalMasuk  = $history->where('type', 'masuk')->sum('amount');
        $totalKeluar = $history->where('type', 'keluar')->sum('amount');
        $net         = $totalMasuk - $totalKeluar;

        $sheet->mergeCells("A{$lastRow}:F{$lastRow}");
        $sheet->setCellValue("A{$lastRow}",
            'Ringkasan: Masuk Rp ' . number_format($totalMasuk, 0, ',', '.') .
            ' | Keluar Rp ' . number_format($totalKeluar, 0, ',', '.') .
            ' | Selisih Rp ' . number_format($net, 0, ',', '.')
        );
        $sheet->getStyle("A{$lastRow}:F{$lastRow}")->applyFromArray([
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFF1F5F9']],
            'font'      => ['bold' => true, 'size' => 11, 'color' => ['argb' => 'FF334155']],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            'borders'   => ['outline' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['argb' => 'FF94A3B8']]],
        ]);
        $sheet->getRowDimension($lastRow)->setRowHeight(25);

        // ── Column widths ─────────────────────────────────────────────────────
        $widths = [
            'A' => 8,   // No
            'B' => 16,  // Tanggal
            'C' => 22,  // Referensi
            'D' => 35,  // Pihak
            'E' => 14,  // Tipe
            'F' => 24,  // Jumlah
        ];
        foreach ($widths as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }

        $sheet->freezePane('A7');
        $sheet->setAutoFilter('A6:F6');

        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
        $sheet->getPageSetup()->setFitToWidth(1);
        $sheet->getPageSetup()->setFitToHeight(0);

        $slug     = str($bankAccount->bank_name)->slug('-');
        $filename = "laporan-rekening-{$slug}-" . now()->format('Y-m-d') . '.xlsx';
        $writer   = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control'       => 'max-age=0',
        ]);
    }

    public function edit(BankAccount $bankAccount): View
    {
        return view('bank-accounts.form', [
            'bankAccount' => $bankAccount,
            'pageTitle' => 'Edit Rekening Bank',
            'submitLabel' => 'Perbarui Data',
            'action' => route('bank-accounts.update', $bankAccount),
            'method' => 'PUT',
        ]);
    }

    public function update(Request $request, BankAccount $bankAccount): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'account_name' => ['required', 'string', 'max:255'],
            'account_number' => ['required', 'string', 'max:50'],
            'bank_name' => ['required', 'string', 'max:255'],
            'saldo' => ['required', 'numeric', 'min:0'],
        ]);

        $bankAccount->update($validated);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Rekening bank berhasil diperbarui.', 'redirect' => route('bank-accounts.index')]);
        }

        return redirect()->route('bank-accounts.index')->with('success', 'Rekening bank berhasil diperbarui.');
    }

    public function destroy(BankAccount $bankAccount): RedirectResponse|JsonResponse
    {
        if ($bankAccount->transactions()->exists() || $bankAccount->finances()->exists()) {
            if (request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Rekening ini memiliki catatan transaksi atau arus kas.']);
            }
            return redirect()->route('bank-accounts.index')->with('error', 'Rekening ini memiliki catatan transaksi atau arus kas.');
        }

        $bankAccount->delete();

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Rekening bank berhasil dihapus.', 'redirect' => route('bank-accounts.index')]);
        }

        return redirect()->route('bank-accounts.index')->with('success', 'Rekening bank berhasil dihapus.');
    }
}
