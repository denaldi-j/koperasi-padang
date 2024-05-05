<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ReportController extends Controller
{
    public function index()
    {
        $organizations = Organization::all();
        return view('report.index', compact('organizations'));
    }

    public function get(Request $request)
    {
        $query = Organization::query()
            ->with(['members' => function ($member) {
                $member->with(['balance' => function ($balance) {
                    $balance->withSum('payments as total_payment', 'final_amount');
                    $balance->withSum('payments as payment_on_cash', 'cash');
                    $balance->withSum('deposits as total_deposit', 'amount');
                }]);
            }]);

        $query->where('id', $request->organization);

        if (!is_null($request->date)) {
            $req = explode('/', $request->date);
            $date = Carbon::parse($req[1] . '-' . $req[0] . '-01');
            $endDate = $date->endOfMonth()->toDateTimeString();
            $startDate = $date->startOfYear()->toDateTimeString();

            $query->with(['members' => function ($member) use ($startDate, $endDate) {
                $member->with(['balance' => function ($balance) use ($startDate, $endDate) {
                    $balance->withSum(['payments as total_payment' => function ($payments) use ($startDate, $endDate) {
                        $payments->whereBetween('created_at', [$startDate, $endDate]);
                    }], 'final_amount');

                    $balance->withSum(['payments as payment_on_cash' => function ($payments) use ($startDate, $endDate) {
                        $payments->whereBetween('created_at', [$startDate, $endDate]);
                    }], 'cash');

                    $balance->withSum(['deposits as total_deposit' => function ($deposits) use ($startDate, $endDate) {
                        $deposits->whereBetween('date', [Carbon::parse($startDate)->toDateString(), Carbon::parse($endDate)->toDateString()]);
                    }], 'amount');
                }]);
            }]);
        }


        $reports = $query->first()?->members;



        return DataTables::of($reports)
            ->toJson();
    }
}
