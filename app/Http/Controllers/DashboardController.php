<?php

namespace App\Http\Controllers;

use App\Models\Balance;
use App\Models\Member;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $balance = Balance::query()->toBase()
            ->selectRaw('SUM(amount) as amount')
            ->selectRaw('SUM(total_transaction) as transaction')
            ->first();

        $todayTransaction = Payment::query()->whereDate('created_at', Carbon::now())->sum('amount');
        $cashPayment = Payment::query()->whereDate('created_at', Carbon::now())->sum('cash');
        $members = Member::query()->count();

        $all = getSumAllOfTransactions();
        $s_now = Carbon::now()->startOfMonth()->toDateString();
        $e_now = Carbon::now()->endOfMonth()->toDateString();
        $s_last = Carbon::now()->subMonth()->startOfMonth()->toDateString();
        $e_last = Carbon::now()->subMonth()->endOfMonth()->toDateString();
        $monthbefore = getSumOfTransactions($s_last, $e_last);
        $monthly = getSumOfTransactions($s_now, $e_now);
        // $monthly = getSumOfTransactions($s_now, $e_now);

        return view('dashboard', compact('balance', 'todayTransaction', 'members', 'cashPayment','monthly','monthbefore','all'));
    }
}
