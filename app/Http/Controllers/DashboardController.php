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

        // Calculate previous month data for growth comparison
        $prevStartDate = (clone $startDate)->subMonth();
        $prevEndDate = (clone $prevStartDate)->endOfMonth();

        $prevIncome = Finance::where('type', 'income')
            ->whereBetween('created_at', [$prevStartDate, $prevEndDate])
            ->sum('amount');

        $prevExpense = Finance::where('type', 'expense')
            ->whereBetween('created_at', [$prevStartDate, $prevEndDate])
            ->sum('amount');

        $incomeGrowth = $prevIncome > 0 ? (($income - $prevIncome) / $prevIncome) * 100 : ($income > 0 ? 100 : 0);
        $expenseGrowth = $prevExpense > 0 ? (($expense - $prevExpense) / $prevExpense) * 100 : ($expense > 0 ? 100 : 0);

        // Daily Stats for Charts
        $dailyIncome = Finance::where('type', 'income')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DAY(created_at) as day, SUM(amount) as total')
            ->groupBy('day')
            ->pluck('total', 'day')
            ->all();

        $dailyExpense = Finance::where('type', 'expense')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DAY(created_at) as day, SUM(amount) as total')
            ->groupBy('day')
            ->pluck('total', 'day')
            ->all();

        // Fill missing days with 0
        $daysInMonth = $startDate->daysInMonth;
        $chartIncome = [];
        $chartExpense = [];
        $maxIncome = count($dailyIncome) > 0 ? max($dailyIncome) : 0;
        $maxExpense = count($dailyExpense) > 0 ? max($dailyExpense) : 0;

        for ($i = 1; $i <= $daysInMonth; $i++) {
            $valICount = $dailyIncome[$i] ?? 0;
            $valECount = $dailyExpense[$i] ?? 0;
            
            $chartIncome[$i] = $maxIncome > 0 ? ($valICount / $maxIncome) * 100 : 0;
            $chartExpense[$i] = $maxExpense > 0 ? ($valECount / $maxExpense) * 100 : 0;
        }

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

        $loyalCustomers = Customer::withCount('transactions')
            ->orderBy('transactions_count', 'desc')
            ->limit(3)
            ->get();

        $topSuppliers = Supplier::withCount('transactions')
            ->orderBy('transactions_count', 'desc')
            ->limit(3)
            ->get();

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
            'incomeGrowth' => $incomeGrowth,
            'expenseGrowth' => $expenseGrowth,
            'chartIncome' => $chartIncome,
            'chartExpense' => $chartExpense,
            'balance' => $income - $expense,
            'recentFinances' => $recentFinances,
            'loyalCustomers' => $loyalCustomers,
            'topSuppliers' => $topSuppliers,
        ]);
    }
}
