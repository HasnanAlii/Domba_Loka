<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Finance;
use App\Models\Growth;
use App\Models\Sheep;
use App\Models\Supplier;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $selectedMonth = max(1, min(12, $request->integer('month', now()->month)));
        $selectedYear = $request->integer('year', now()->year);

        $startDate = Carbon::create($selectedYear, $selectedMonth, 1)->startOfMonth();
        $endDate = (clone $startDate)->endOfMonth();

        $income = Finance::where('type', 'income')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');

        $expense = Finance::where('type', 'expense')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');

        $recentFinances = Finance::with(['bankAccount', 'transaction'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->limit(5)
            ->get();

        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        return view('dashboard', [
            'months' => $months,
            'years' => range(now()->year - 4, now()->year + 1),
            'selectedMonth' => $selectedMonth,
            'selectedYear' => $selectedYear,
            'totalSheep' => Sheep::count(),
            'totalCustomers' => Customer::count(),
            'totalSuppliers' => Supplier::count(),
            'transactionsThisMonth' => Transaction::whereBetween('created_at', [$startDate, $endDate])->count(),
            'growthChecksThisMonth' => Growth::whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])->count(),
            'averageSheepWeight' => (float) Sheep::avg('weight'),
            'income' => $income,
            'expense' => $expense,
            'balance' => $income - $expense,
            'recentFinances' => $recentFinances,
        ]);
    }
}
