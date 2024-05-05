<?php

namespace App\Http\Controllers;

use App\Actions\Balance\UpdateBalance;
use App\Models\Balance;
use App\Models\Deposit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Yajra\DataTables\Facades\DataTables;

class DepositController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('role:super-admin|admin-opd')
        ];
    }

    public function storeMonthlyDeposit(Request $request, UpdateBalance $updateBalance)
    {
        $month = date('m', strtotime($request->date));
        $year = date('Y', strtotime($request->date));

        try {
            $balances = Balance::all();
            foreach ($balances as $balance) {
                $deposit = Deposit::query()
                    ->whereMonth('date', $month)
                    ->whereYear('date', $year)
                    ->where('balance_id', $balance->id)
                    ->first();



                $monthly_deposit = ($balance->monthly_deposit !== 0) ? $balance->monthly_deposit : $request->amount;
                $plusDiscount = $monthly_deposit + ($monthly_deposit * 1 / 100);

                if($deposit) {
                    $deposit->date = $request->date;
                    $deposit->amount = $plusDiscount;
                    $deposit->update();
                    return $deposit;
                } else {
                    Deposit::query()->create([
                        'balance_id'    => $balance->id,
                        'date'          => $request->date,
                        'amount'        => $plusDiscount,
                    ]);
                }

                $updateBalance->handle($balance->id);
            }

            return response()->json(['status' => true]);

        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }

    }

    public function get($balance_id)
    {
        $deposits = Deposit::query()
            ->where('balance_id', $balance_id)
            ->orderByDesc('date')
            ->get();

        return DataTables::collection($deposits)
            ->editColumn('created_at', function ($deposit) {
                return Carbon::parse($deposit->date)->format('F Y');
            })
            ->toJson();
    }
}
