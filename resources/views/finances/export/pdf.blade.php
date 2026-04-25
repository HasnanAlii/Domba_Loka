<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #333333;
            background: #ffffff;
            line-height: 1.5;
        }

        .w-full { width: 100%; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .align-top { vertical-align: top; }
        .align-middle { vertical-align: middle; }

        /* Header */
        .header-container {
            margin-bottom: 25px;
            border-bottom: 2px solid #1d4ed8;
            padding-bottom: 15px;
            padding-top: 20px;
            padding-left: 30px;
            padding-right: 30px;
        }

        .header-table { width: 100%; }

        .header-title {
            font-size: 24px;
            font-weight: 700;
            color: #1e3a8a;
            margin-bottom: 5px;
            letter-spacing: -0.5px;
            text-transform: uppercase;
        }

        .header-subtitle {
            font-size: 11px;
            color: #64748b;
            font-weight: 500;
        }

        .header-meta {
            font-size: 10px;
            color: #475569;
            line-height: 1.6;
        }

        .header-meta strong { color: #1e293b; }

        /* Stats */
        .stats-container {
            margin: 0 30px 25px;
        }

        .stats-table {
            width: 100%;
            border-spacing: 10px;
            border-collapse: separate;
            margin-left: -10px;
            margin-right: -10px;
        }

        .stat-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 15px;
            text-align: center;
            width: 25%;
        }

        .stat-val {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 4px;
        }

        .stat-lbl {
            font-size: 9px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        /* Filter */
        .filter-container {
            margin: 0 30px 20px;
            background: #f1f5f9;
            padding: 10px 15px;
            border-radius: 6px;
            font-size: 10px;
            color: #334155;
            border-left: 3px solid #3b82f6;
        }

        /* Main Table */
        .table-wrap {
            margin: 0 30px 30px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #cbd5e1;
        }

        .data-table thead tr {
            background: #1e3a8a;
        }

        .data-table thead th {
            padding: 10px 12px;
            text-align: left;
            font-size: 13px;
            font-weight: 600;
            color: #ffffff;
            border: 1px solid #1e3a8a;
        }

        .data-table tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        .data-table tbody tr:nth-child(odd) {
            background: #ffffff;
        }

        .data-table tbody td {
            padding: 9px 12px;
            border: 1px solid #cbd5e1;
            font-size: 12px;
            color: #334155;
            vertical-align: middle;
        }

        /* Badges */
        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-income {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .badge-expense {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .text-income { color: #15803d; font-weight: 700; }
        .text-expense { color: #dc2626; font-weight: 700; }
        .font-bold { font-weight: 700; }

        /* Footer */
        .footer {
            margin: 0 30px;
            padding-top: 15px;
            border-top: 1px solid #cbd5e1;
            font-size: 9px;
            color: #64748b;
        }

        .footer-table { width: 100%; }

        @page { margin: 20px 0; }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header-container">
        <table class="header-table">
            <tr>
                <td class="align-middle text-left" style="width: 8%; padding-bottom: 5px;">
                    @php
                        $path = public_path('images/icon/logo.png');
                        $type = pathinfo($path, PATHINFO_EXTENSION);
                        $data = file_get_contents($path);
                        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                    @endphp
                    <img src="{{ $base64 }}" style="height: 50px; width: auto;" alt="Logo">
                </td>
                <td class="align-middle text-left" style="width: 52%; padding-left: 15px;">
                    <div style="font-size: 25px; font-weight: 800; letter-spacing: 0.5px;">DOMBA LOKA</div>
                    <div class="header-title" style="font-size: 18px; color: #0f172a;">LAPORAN KEUANGAN</div>
                    <div class="header-subtitle">Arus Kas Masuk &amp; Keluar Peternakan</div>
                </td>
                <td class="align-middle text-right header-meta" style="width: 40%;">
                    <div><strong>Tanggal Cetak:</strong> {{ now()->format('d/m/Y H:i') }}</div>
                    <div><strong>Total Data:</strong> {{ $finances->count() }} record</div>
                    @if (!empty($filters['date_range']))
                        <div><strong>Periode:</strong> {{ $filters['date_range'] }}</div>
                    @endif
                    @if (!empty($filters['type']))
                        <div><strong>Tipe:</strong> {{ $filters['type'] === 'income' ? 'Pemasukan' : 'Pengeluaran' }}</div>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    @php
        $totalIncome  = $finances->where('type', 'income')->sum('amount');
        $totalExpense = $finances->where('type', 'expense')->sum('amount');
        $netBalance   = $totalIncome - $totalExpense;
        $totalRecord  = $finances->count();
    @endphp


    @if (array_filter($filters))
        <div class="filter-container">
            <strong>Filter Aktif:</strong>
            @if ($filters['date_range'] ?? null)
                Tanggal: <strong>{{ $filters['date_range'] }}</strong>&nbsp;|&nbsp;
            @endif
            @if ($filters['type'] ?? null)
                Tipe: <strong>{{ $filters['type'] === 'income' ? 'Pemasukan' : 'Pengeluaran' }}</strong>&nbsp;|&nbsp;
            @endif
            @if ($filters['search'] ?? null)
                Pencarian: <strong>{{ $filters['search'] }}</strong>
            @endif
        </div>
    @endif

    <!-- Table -->
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th class="text-center" style="width: 40px;">No</th>
                    <th class="text-center">Tanggal</th>
                    <th>Bank / Akun</th>
                    <th>No. Transaksi</th>
                    <th class="text-center">Tipe</th>
                    <th class="text-right">Jumlah</th>
                    <th>Deskripsi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($finances as $i => $f)
                    <tr>
                        <td class="text-center">{{ $i + 1 }}</td>
                        <td class="text-center">{{ $f->created_at->format('d/m/Y') }}</td>
                        <td class="font-bold" style="color: #1e40af;">{{ $f->bankAccount->bank_name ?? 'Kas Tunai' }}</td>
                        <td>
                            @if ($f->transaction)
                                {{ $f->transaction->reference_number }}
                            @else
                                <span style="color: #94a3b8; font-style: italic;">External</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="status-badge {{ $f->type === 'income' ? 'badge-income' : 'badge-expense' }}">
                                {{ $f->type === 'income' ? 'Masuk' : 'Keluar' }}
                            </span>
                        </td>
                        <td class="text-right {{ $f->type === 'income' ? 'text-income' : 'text-expense' }}">
                            {{ $f->type === 'income' ? '+' : '-' }} Rp {{ number_format($f->amount, 0, ',', '.') }}
                        </td>
                        <td>{{ $f->description ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center"
                            style="padding: 30px; font-style: italic; color: #94a3b8;">
                            Tidak ada data keuangan untuk kriteria ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <!-- Stats -->
    <div class="stats-container">
        <table class="stats-table">
            <tr>
                <td class="stat-box">
                    <div class="stat-val">{{ $totalRecord }}</div>
                    <div class="stat-lbl">Total Transaksi</div>
                </td>
                <td class="stat-box">
                    <div class="stat-val" style="color: #15803d;">Rp {{ number_format($totalIncome, 0, ',', '.') }}</div>
                    <div class="stat-lbl">Total Masuk</div>
                </td>
                <td class="stat-box">
                    <div class="stat-val" style="color: #dc2626;">Rp {{ number_format($totalExpense, 0, ',', '.') }}</div>
                    <div class="stat-lbl">Total Keluar</div>
                </td>
                <td class="stat-box">
                    <div class="stat-val" style="color: {{ $netBalance >= 0 ? '#15803d' : '#dc2626' }};">
                        {{ $netBalance >= 0 ? '' : '-' }}Rp {{ number_format(abs($netBalance), 0, ',', '.') }}
                    </div>
                    <div class="stat-lbl">Saldo Bersih</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        <table class="footer-table">
            <tr>
                <td class="text-left"><strong>DOMBA LOKA</strong> - Sistem Manajemen Ternak Profesional</td>
                <td class="text-right">Di-generate otomatis pada {{ now()->format('d F Y H:i:s') }}</td>
            </tr>
        </table>
    </div>
</body>

</html>
