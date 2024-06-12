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
            if(auth()->user()->hasRole('super-admin') && !is_null($request->organization_id)){
                $organizationId = $request->organization_id;
            }

            if(auth()->user()->hasRole('admin-opd')){
                $organizationId = auth()->user()->organization_id;
            }

            $balances = Balance::query()
                ->whereHas('member', function($query) use ($organizationId){
                    $query->where('organization_id', $organizationId);
                })->get();

            foreach ($balances as $balance) {
                $deposit = Deposit::query()
                    ->whereMonth('date', $month)
                    ->whereYear('date', $year)
                    ->where('balance_id', $balance->id)
                    ->first();

                $amount = $balance->member->is_asn ? $request->amount_asn : $request->amount_non;

                $monthly_deposit = ($balance->monthly_deposit !== 0) ? $balance->monthly_deposit : $amount;
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
