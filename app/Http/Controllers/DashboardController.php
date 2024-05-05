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

        return view('dashboard', compact('balance', 'todayTransaction', 'members', 'cashPayment'));
    }
}
