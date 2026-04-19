<?php

use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\CashMigrationController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\GrowthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SheepController;
use App\Http\Controllers\SheepTypeController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $featuredSheep = \App\Models\Sheep::with('sheepType')
        ->where('status', 'tersedia')
        ->whereIn('condition', ['sehat', 'baik','sangat baik'])
        ->latest()
        ->take(2)
        ->get();
    return view('welcome', compact('featuredSheep'));
});

Route::get('/katalog', [SheepController::class, 'catalog'])->name('public.catalog');
Route::get('/katalog/{sheep:code}', [SheepController::class, 'catalogDetail'])->name('public.catalog.show');
Route::get('/tentang-kami', function () {
    return view('public.about');
})->name('public.about');

Route::get('/dashboard', DashboardController::class)->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('sheep', SheepController::class);
    Route::resource('sheep-types', SheepTypeController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('transactions', TransactionController::class);
    Route::get('transactions/{transaction}/export-pdf', [TransactionController::class, 'exportPDF'])->name('transactions.export-pdf');
    Route::resource('growths', GrowthController::class);
    Route::resource('finances', FinanceController::class);
    Route::resource('keuangan', FinanceController::class)
        ->parameters(['keuangan' => 'finance'])
        ->names('keuangan');
    Route::resource('bank-accounts', BankAccountController::class);
    Route::post('bank-accounts/{bankAccount}/migrate-cash', [CashMigrationController::class, 'store'])
        ->name('bank-accounts.migrate-cash');

    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/laporan-keuangan', [ReportController::class, 'laporanKeuangan'])->name('keuangan');
        Route::get('/laporan-penjualan', [ReportController::class, 'laporanPenjualan'])->name('penjualan');
        Route::get('/laporan-pembelian', [ReportController::class, 'laporanPembelian'])->name('pembelian');
        Route::get('/laba-rugi', [ReportController::class, 'labaRugi'])->name('laba-rugi');
        Route::get('/pertumbuhan-domba', [ReportController::class, 'pertumbuhanDomba'])->name('pertumbuhan-domba');
    });
});

Route::post('/chatbot', \App\Http\Controllers\ChatbotController::class)->name('api.chatbot');

require __DIR__.'/auth.php';
