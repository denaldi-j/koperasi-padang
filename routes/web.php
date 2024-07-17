<?php

use App\Http\Controllers\BalanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware('auth:web')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('members')->name('members.')->group(function () {
        Route::get('/', [MemberController::class, 'index'])->name('index');
        Route::get('/create', [MemberController::class, 'create'])->name('create');
        Route::post('/store', [MemberController::class, 'store'])->name('store');
        Route::post('/update/{member}', [MemberController::class, 'update'])->name('update');
        Route::get('/get', [MemberController::class, 'get'])->name('get');
        Route::get('search', [MemberController::class, 'search'])->name('search');
        Route::get('get-by-name', [MemberController::class, 'getEmployeeByName'])->name('getByName');
        Route::get('form-import', [MemberController::class, 'formImport'])->name('formImport');
        Route::post('import', [MemberController::class, 'importFromExcel'])->name('import');
    });

    Route::prefix('balance')->name('balance.')->group(function () {
        Route::get('/', [BalanceController::class, 'index'])->name('index');
        Route::get('/show/{id}', [BalanceController::class, 'show'])->name('show');
        Route::get('/get-by-member/{id}', [BalanceController::class, 'getByMember'])->name('getByMember');
    });

    Route::prefix('deposit')->name('deposit.')->group(function () {
        Route::post('/store-monthly', [DepositController::class, 'storeMonthlyDeposit'])->name('store-monthly');
        Route::get('/get/{balance_id}', [DepositController::class, 'get'])->name('get');
    });

    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', [PaymentController::class, 'index'])->name('index');
        Route::get('/get/{balance_id}', [PaymentController::class, 'get'])->name('get');
        Route::post('/store', [PaymentController::class, 'store'])->name('store');
    });

    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/get', [UserController::class, 'get'])->name('get');
        Route::post('/store', [UserController::class, 'store'])->name('store');
    });

    Route::prefix('organizations')->name('organizations.')->group(function () {
        Route::get('get/{id?}', [OrganizationController::class, 'get'])->name('get');
        Route::get('get-new-members/{code?}', [OrganizationController::class, 'getNewMember'])->name('get-new-members');
    });

    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/get', [ReportController::class, 'get'])->name('get');
        Route::get('/get-trx', [ReportController::class, 'get_trx'])->name('get-trx');
        Route::post('/get-count', [ReportController::class, 'getCount'])->name('get-count');
        Route::get('/print-all', [ReportController::class, 'print_trx'])->name('export-all');
        Route::get('/export-pdf', [ReportController::class, 'exportPDF'])->name('export-pdf');
        Route::get('/transaction', [ReportController::class, 'transaction'])->name('transaction');
    });
});
