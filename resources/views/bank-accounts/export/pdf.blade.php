<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Rekening Bank</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Poppins', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #333333;
            background: #ffffff;
            line-height: 1.5;
        }

        .text-right  { text-align: right; }
        .text-center { text-align: center; }
        .text-left   { text-align: left; }
        .align-middle { vertical-align: middle; }
        .font-bold    { font-weight: 700; }

        /* Header */
        .header-container {
            margin-bottom: 25px;
            border-bottom: 2px solid #1d4ed8;
            padding: 20px 30px 15px;
        }

        .header-table { width: 100%; }

        .header-subtitle { font-size: 11px; color: #64748b; font-weight: 500; }

        .header-meta { font-size: 10px; color: #475569; line-height: 1.6; }
        .header-meta strong { color: #1e293b; }

        /* Info card */
        .info-container { margin: 0 30px 20px; }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
        }
        .info-table td { padding: 10px 16px; font-size: 11px; color: #334155; }
        .info-label { color: #94a3b8; font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600; display: block; margin-bottom: 2px; }
        .info-value { font-weight: 700; color: #0f172a; font-size: 13px; }

        /* Stats */
        .stats-container { margin: 0 30px 20px; }
        .stats-table {
            width: 100%;
            border-spacing: 8px;
            border-collapse: separate;
            margin-left: -8px;
            margin-right: -8px;
        }
        .stat-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 12px 15px;
            text-align: center;
            width: 33%;
        }
        .stat-val { font-size: 13px; font-weight: 700; color: #0f172a; margin-bottom: 4px; }
        .stat-lbl { font-size: 9px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600; }

        /* Table */
        .table-wrap { margin: 0 30px 30px; }

        .data-table { width: 100%; border-collapse: collapse; border: 1px solid #cbd5e1; }
        .data-table thead tr { background: #1e3a8a; }
        .data-table thead th {
            padding: 10px 12px;
            text-align: left;
            font-size: 13px;
            font-weight: 600;
            color: #ffffff;
            border: 1px solid #1e3a8a;
        }
        .data-table tbody tr:nth-child(even) { background: #f8fafc; }
        .data-table tbody tr:nth-child(odd)  { background: #ffffff; }
        .data-table tbody td {
            padding: 9px 12px;
            border: 1px solid #cbd5e1;
            font-size: 12px;
            color: #334155;
            vertical-align: middle;
        }

        /* Badges */
        .status-badge { padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; text-transform: uppercase; }
        .badge-income  { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .badge-expense { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .text-income   { color: #15803d; font-weight: 700; }
        .text-expense  { color: #dc2626; font-weight: 700; }

        /* Footer */
        .footer { margin: 0 30px; padding-top: 15px; border-top: 1px solid #cbd5e1; font-size: 9px; color: #64748b; }
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
                    <div style="font-size: 18px; font-weight: 700; color: #0f172a; text-transform: uppercase;">LAPORAN REKENING BANK</div>
                    <div class="header-subtitle">Riwayat Transaksi &amp; Arus Kas</div>
                </td>
                <td class="align-middle text-right header-meta" style="width: 40%;">
                    <div><strong>Tanggal Cetak:</strong> {{ now()->format('d/m/Y H:i') }}</div>
                    <div><strong>Total Data:</strong> {{ $history->count() }} record</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Bank Info Card -->
    <div class="info-container">
        <table class="info-table">
            <tr>
                <td style="width: 33%;">
                    <span class="info-label">Bank</span>
                    <span class="info-value">{{ $bankAccount->bank_name }}</span>
                </td>
                <td style="width: 33%;">
                    <span class="info-label">No. Rekening</span>
                    <span class="info-value">{{ $bankAccount->account_number }}</span>
                </td>
                <td style="width: 33%;">
                    <span class="info-label">Saldo Rekening</span>
                    <span class="info-value">Rp {{ number_format($bankAccount->saldo, 0, ',', '.') }}</span>
                </td>
            </tr>
        </table>
    </div>

    @php
        $totalMasuk  = $history->where('type', 'masuk')->sum('amount');
        $totalKeluar = $history->where('type', 'keluar')->sum('amount');
        $net         = $totalMasuk - $totalKeluar;
    @endphp


    <!-- Table -->
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th class="text-center" style="width: 40px;">No</th>
                    <th class="text-center">Tanggal</th>
                    <th>No. Referensi</th>
                    <th>Pihak / Deskripsi</th>
                    <th class="text-center">Tipe</th>
                    <th class="text-right">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @forelse($history->values() as $i => $item)
                    <tr>
                        <td class="text-center">{{ $i + 1 }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
                        <td class="font-bold" style="color: #1e40af;">{{ $item->ref }}</td>
                        <td>{{ $item->party ?? '-' }}</td>
                        <td class="text-center">
                            <span class="status-badge {{ $item->type === 'masuk' ? 'badge-income' : 'badge-expense' }}">
                                {{ $item->type === 'masuk' ? 'Masuk' : 'Keluar' }}
                            </span>
                        </td>
                        <td class="text-right {{ $item->type === 'masuk' ? 'text-income' : 'text-expense' }}">
                            {{ $item->type === 'masuk' ? '+' : '-' }} Rp {{ number_format($item->amount, 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center"
                            style="padding: 30px; font-style: italic; color: #94a3b8;">
                            Tidak ada riwayat transaksi untuk rekening ini.
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
                    <div class="stat-val" style="color: #15803d;">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</div>
                    <div class="stat-lbl">Total Masuk</div>
                </td>
                <td class="stat-box">
                    <div class="stat-val" style="color: #dc2626;">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</div>
                    <div class="stat-lbl">Total Keluar</div>
                </td>
                <td class="stat-box">
                    <div class="stat-val" style="color: {{ $net >= 0 ? '#15803d' : '#dc2626' }};">
                        {{ $net >= 0 ? '' : '-' }}Rp {{ number_format(abs($net), 0, ',', '.') }}
                    </div>
                    <div class="stat-lbl">Selisih</div>
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
