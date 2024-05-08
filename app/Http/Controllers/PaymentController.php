<?php

namespace App\Http\Controllers;

use App\Actions\Balance\UpdateBalance;
use App\Models\Balance;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Yajra\DataTables\Facades\DataTables;

class PaymentController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('role:operator', only: ['index'. 'store']),
            new Middleware('role:super-admin|admin-opd|pimpinan', only: ['get']),
        ];
    }

    public function index()
    {
        return view('payment.index');
    }

    public function store(Request $request, UpdateBalance $updateBalance)
    {
        try {
            $balance    = Balance::query()->where('member_id', $request->member)->first();
            $payment    = new Payment();
            $payment->balance_id    = $balance->id;
            $payment->amount        = $request->amount;
            $payment->discount      = $request->discount;
            $payment->final_amount  = $request->total;

            if($request->cash > 0) {
                $payment->is_cash       = !is_null($request->cash);
                $payment->cash          = $request->cash;
            }

            $payment->operator_id = auth()->user()->id;

            $payment->save();
            $updateBalance->handle($balance->id);

            return response()->json(['status' => 'success', 'data' => $payment]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);

        }
    }

    public function get($balance_id)
    {
        $payments = Payment::query()
            ->where('balance_id', $balance_id)
            ->orderByDesc('created_at')
            ->get();

        return DataTables::collection($payments)
            ->editColumn('created_at', function ($payment) {
                return Carbon::parse($payment->created_at)->format('d F Y');
            })
            ->toJson();
    }
}
