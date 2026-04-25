<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\Customer;
use App\Models\Finance;
use App\Models\Supplier;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FinanceController extends Controller
{
    public function index(Request $request): View|HttpResponse|StreamedResponse
    {
        $validated = $request->validate([
            'type' => ['nullable', Rule::in(['income', 'expense'])],
            'bank_account_id' => ['nullable', 'exists:bank_accounts,id'],
            'date_range' => ['nullable', 'string', 'max:50'],
            'customer' => ['nullable', 'string', 'max:255'],
            'supplier' => ['nullable', 'string', 'max:255'],
            'search' => ['nullable', 'string', 'max:255'],
        ]);

        $filters = [
            'type' => $validated['type'] ?? null,
            'bank_account_id' => $validated['bank_account_id'] ?? null,
            'date_range' => trim((string) ($validated['date_range'] ?? '')),
            'customer' => trim((string) ($validated['customer'] ?? '')),
            'supplier' => trim((string) ($validated['supplier'] ?? '')),
            'search' => trim((string) ($validated['search'] ?? '')),
        ];

        $rangeStart = null;
        $rangeEnd = null;
        if ($filters['date_range'] !== '') {
            if (str_contains($filters['date_range'], ' to ')) {
                [$rangeStart, $rangeEnd] = explode(' to ', $filters['date_range']);
            } else {
                $rangeStart = $filters['date_range'];
            }
        }

        $financeQuery = Finance::query()
            ->with(['bankAccount', 'transaction'])
            ->when($filters['type'], function ($query, $type) {
                $query->where('type', $type);
            })
            ->when($filters['bank_account_id'], function ($query, $bankAccountId) {
                $query->where('bank_account_id', $bankAccountId);
            })
            ->when($filters['customer'] !== '', function ($query) use ($filters) {
                $query->whereHas('transaction.customer', function ($customerQuery) use ($filters) {
                    $customerQuery->where('name', $filters['customer']);
                });
            })
            ->when($filters['supplier'] !== '', function ($query) use ($filters) {
                $query->whereHas('transaction.supplier', function ($supplierQuery) use ($filters) {
                    $supplierQuery->where('name', $filters['supplier']);
                });
            })
            ->when($rangeStart, function ($query) use ($rangeStart, $rangeEnd) {
                if ($rangeEnd) {
                    $query->whereBetween('created_at', [
                        $rangeStart.' 00:00:00',
                        $rangeEnd.' 23:59:59',
                    ]);

                    return;
                }

                $query->whereDate('created_at', $rangeStart);
            })
            ->when($filters['search'] !== '', function ($query) use ($filters) {
                $search = $filters['search'];

                $query->where(function ($searchQuery) use ($search) {
                    $searchQuery->where('description', 'like', "%{$search}%")
                        ->orWhereHas('transaction', function ($transactionQuery) use ($search) {
                            $transactionQuery->where('reference_number', 'like', "%{$search}%");
                        })
                        ->orWhereHas('transaction.customer', function ($customerQuery) use ($search) {
                            $customerQuery->where('name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('transaction.supplier', function ($supplierQuery) use ($search) {
                            $supplierQuery->where('name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('bankAccount', function ($bankQuery) use ($search) {
                            $bankQuery->where('bank_name', 'like', "%{$search}%")
                                ->orWhere('account_number', 'like', "%{$search}%");
                        });
                });
            })
            ->latest();

        $finances = (clone $financeQuery)->paginate(15)->withQueryString();
        $income = (clone $financeQuery)->where('type', 'income')->sum('amount');
        $expense = (clone $financeQuery)->where('type', 'expense')->sum('amount');

        // ── Export handler ─────────────────────────────────────────────────
        if ($request->query('export') === 'pdf') {
            return $this->exportPdf((clone $financeQuery)->get(), $filters);
        }

        if ($request->query('export') === 'excel') {
            return $this->exportExcel((clone $financeQuery)->get(), $filters);
        }

        return view('finances.index', [
            'finances'     => $finances,
            'income'       => $income,
            'expense'      => $expense,
            'balance'      => $income - $expense,
            'filters'      => $filters,
            'bankAccounts' => $this->bankAccounts(),
            'customers'    => Customer::orderBy('name')->get(),
            'suppliers'    => Supplier::orderBy('name')->get(),
        ]);
    }

    private function exportPdf(Collection $finances, array $filters): HttpResponse
    {
        $pdf = Pdf::loadView('finances.export.pdf', compact('finances', 'filters'))
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'isRemoteEnabled'    => true,
                'isHtml5ParserEnabled' => true,
                'defaultFont'        => 'Poppins',
            ]);

        return $pdf->download('laporan-keuangan-' . now()->format('Y-m-d') . '.pdf');
    }

    private function exportExcel(Collection $finances, array $filters): StreamedResponse
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Keuangan');

        // ── Branding rows ─────────────────────────────────────────────────────
        $sheet->mergeCells('A1:G1');
        $sheet->setCellValue('A1', 'DOMBA LOKA — Sistem Manajemen Ternak');
        $sheet->getStyle('A1')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 16, 'color' => ['argb' => 'FF1E40AF']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(28);

        $sheet->mergeCells('A2:G2');
        $sheet->setCellValue('A2', 'LAPORAN KEUANGAN');
        $sheet->getStyle('A2')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 13, 'color' => ['argb' => 'FF0F172A']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(2)->setRowHeight(22);

        $sheet->mergeCells('A3:G3');
        $sheet->setCellValue('A3', 'Arus Kas Masuk & Keluar Peternakan');
        $sheet->getStyle('A3')->applyFromArray([
            'font'      => ['size' => 10, 'color' => ['argb' => 'FF64748B']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
        ]);

        $sheet->mergeCells('A4:G4');
        $meta = 'Tanggal Cetak: ' . now()->format('d/m/Y H:i') . '   |   Total Data: ' . $finances->count() . ' record';
        if (!empty($filters['date_range'])) {
            $meta .= '   |   Periode: ' . $filters['date_range'];
        }
        if (!empty($filters['type'])) {
            $meta .= '   |   Tipe: ' . ($filters['type'] === 'income' ? 'Pemasukan' : 'Pengeluaran');
        }
        $sheet->setCellValue('A4', $meta);
        $sheet->getStyle('A4')->applyFromArray([
            'font'      => ['size' => 10, 'color' => ['argb' => 'FF475569']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
        ]);
        $sheet->getRowDimension(4)->setRowHeight(18);
        $sheet->getRowDimension(5)->setRowHeight(8);

        // ── Table headers ─────────────────────────────────────────────────────
        $headers = ['No', 'Tanggal', 'Bank / Akun', 'No. Transaksi', 'Tipe', 'Jumlah (Rp)', 'Deskripsi'];
        $cols    = range('A', 'G');
        foreach ($headers as $i => $header) {
            $sheet->setCellValue($cols[$i] . '6', $header);
        }
        $sheet->getStyle('A6:G6')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 11, 'color' => ['argb' => 'FFFFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1E3A8A']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FF1E3A8A']]],
        ]);
        $sheet->getRowDimension(6)->setRowHeight(25);

        // ── Data rows ─────────────────────────────────────────────────────────
        foreach ($finances as $i => $f) {
            $row    = $i + 7;
            $isIncome = $f->type === 'income';
            $rowBg  = ($i % 2 === 0) ? 'FFFFFFFF' : 'FFF8FAFC';

            $rowData = [
                'A' => $i + 1,
                'B' => $f->created_at->format('d/m/Y'),
                'C' => $f->bankAccount->bank_name ?? 'Kas Tunai',
                'D' => $f->transaction->reference_number ?? 'External',
                'E' => $isIncome ? 'Masuk' : 'Keluar',
                'F' => ($isIncome ? '+' : '-') . number_format($f->amount, 0, ',', '.'),
                'G' => $f->description ?? '-',
            ];

            foreach ($rowData as $col => $val) {
                $sheet->setCellValue($col . $row, $val);
            }

            $sheet->getStyle("A{$row}:G{$row}")->applyFromArray([
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => $rowBg]],
                'font'      => ['size' => 11],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFCBD5E1']]],
            ]);

            // Bank name bold blue
            $sheet->getStyle("C{$row}")->applyFromArray([
                'font' => ['bold' => true, 'color' => ['argb' => 'FF1E40AF']],
            ]);

            // Tipe badge colour
            $sheet->getStyle("E{$row}")->applyFromArray([
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => $isIncome ? 'FFDCFCE7' : 'FFFEE2E2']],
                'font' => ['bold' => true, 'color' => ['argb' => $isIncome ? 'FF166534' : 'FF991B1B']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ]);

            // Jumlah colour
            $sheet->getStyle("F{$row}")->applyFromArray([
                'font'      => ['bold' => true, 'color' => ['argb' => $isIncome ? 'FF15803D' : 'FFDC2626']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
            ]);

            $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("B{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $sheet->getRowDimension($row)->setRowHeight(20);
        }

        // ── Summary row ───────────────────────────────────────────────────────
        $lastRow     = $finances->count() + 7;
        $totalIncome  = $finances->where('type', 'income')->sum('amount');
        $totalExpense = $finances->where('type', 'expense')->sum('amount');
        $net          = $totalIncome - $totalExpense;

        $sheet->mergeCells("A{$lastRow}:G{$lastRow}");
        $sheet->setCellValue("A{$lastRow}",
            'Ringkasan: Masuk Rp ' . number_format($totalIncome, 0, ',', '.') .
            ' | Keluar Rp ' . number_format($totalExpense, 0, ',', '.') .
            ' | Saldo Rp ' . number_format($net, 0, ',', '.')
        );
        $sheet->getStyle("A{$lastRow}:G{$lastRow}")->applyFromArray([
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
            'C' => 25,  // Bank
            'D' => 22,  // No Transaksi
            'E' => 14,  // Tipe
            'F' => 24,  // Jumlah
            'G' => 35,  // Deskripsi
        ];
        foreach ($widths as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }

        // ── Freeze & filter ───────────────────────────────────────────────────
        $sheet->freezePane('A7');
        $sheet->setAutoFilter('A6:G6');

        // ── Page setup ────────────────────────────────────────────────────────
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
        $sheet->getPageSetup()->setFitToWidth(1);
        $sheet->getPageSetup()->setFitToHeight(0);

        // ── Stream ────────────────────────────────────────────────────────────
        $filename = 'laporan-keuangan-' . now()->format('Y-m-d') . '.xlsx';
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
            finance: new Finance,
            pageTitle: 'Tambah Data Keuangan',
            submitLabel: 'Simpan Data',
            action: route('finances.store'),
        );
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'type' => ['required', 'in:income,expense'],
            'amount' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'transaction_id' => ['nullable', 'exists:transactions,id'],
            'bank_account_id' => ['required', 'exists:bank_accounts,id'],
        ]);

        Finance::create($validated);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Keuangan berhasil ditambahkan.', 'redirect' => route('finances.index')]);
        }

        return redirect()->route('finances.index')->with('success', 'Keuangan berhasil ditambahkan.');
    }

    public function show(Finance $finance): View
    {
        $finance->load(['bankAccount', 'transaction']);

        return view('finances.show', compact('finance'));
    }

    public function edit(Finance $finance): View
    {
        return $this->formView(
            finance: $finance,
            pageTitle: 'Edit Data Keuangan',
            submitLabel: 'Perbarui Data',
            action: route('finances.update', $finance),
            method: 'PUT',
        );
    }

    public function update(Request $request, Finance $finance): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'type' => ['required', 'in:income,expense'],
            'amount' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'transaction_id' => ['nullable', 'exists:transactions,id'],
            'bank_account_id' => ['required', 'exists:bank_accounts,id'],
        ]);

        $finance->update($validated);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Data keuangan berhasil diperbarui.', 'redirect' => route('finances.index')]);
        }

        return redirect()->route('finances.index')->with('success', 'Data keuangan berhasil diperbarui.');
    }

    public function destroy(Finance $finance): RedirectResponse|JsonResponse
    {
        if ($finance->transaction_id) {
            if (request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Arus kas ini terhubung otomatis dengan transaksi jual/beli dan tidak dapat dihapus manual.']);
            }
            return redirect()->route('finances.index')->with('error', 'Arus kas ini terhubung otomatis dengan transaksi jual/beli dan tidak dapat dihapus manual.');
        }

        $finance->delete();

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Data keuangan berhasil dihapus.', 'redirect' => route('finances.index')]);
        }

        return redirect()->route('finances.index')->with('success', 'Data keuangan berhasil dihapus.');
    }

    private function formView(
        Finance $finance,
        string $pageTitle,
        string $submitLabel,
        string $action,
        string $method = 'POST',
    ): View {
        return view('finances.form', [
            'finance' => $finance,
            'pageTitle' => $pageTitle,
            'submitLabel' => $submitLabel,
            'action' => $action,
            'method' => $method,
            'bankAccounts' => $this->bankAccounts(),
            'transactions' => $this->transactions(),
        ]);
    }

    private function bankAccounts(): Collection
    {
        return BankAccount::orderBy('bank_name')->get();
    }

    private function transactions(): Collection
    {
        return Transaction::orderBy('reference_number')->get();
    }
}
