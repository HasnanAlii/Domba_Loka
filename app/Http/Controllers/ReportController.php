<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function laporanKeuangan(): View
    {
        return view('reports.keuangan');
    }

    public function laporanpenjualan(): View
    {
        return view('reports.penjualan');
    }

    public function laporanPembelian(): View
    {
        return view('reports.pembelian');
    }

    public function labaRugi(): View
    {
        return view('reports.laba-rugi');
    }
    public function pertumbuhanDomba(): View
    {
        return view('reports.pertumbuhan-domba');
    }
}
