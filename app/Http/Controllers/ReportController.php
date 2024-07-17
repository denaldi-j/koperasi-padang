<?php

namespace App\Http\Controllers;

use App\Models\Balance;
use App\Models\Organization;
use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ReportController extends Controller
{
    private $type;
    public function __construct()
	{
		$this->type = [
			'all' => 'All',
			'day' => 'Day',
			'week' => 'Week',
			'month' => 'Month',
		];
	}
    public function index()
    {
        $organizations = Organization::all();
        return view('report.index', compact('organizations'));
    }

    public function get(Request $request)
    {
        $reports = $this->query($request);
        return DataTables::of($reports)->toJson();
    }

    public function exportPDF(Request $request)
    {
        $request->validate([
            'date' => 'required'
        ]);

        $req = explode('/', $request->date);
        $month = Carbon::parse($req[1] . '-' . $req[0] . '-01')->format('F Y');

        $reports = $this->query($request);
        $organization = Organization::query()->find($request->organization)?->name;


//        return view('report.report', compact('reports', 'month', 'organization'));
        $pdf = Pdf::loadView('report.report', compact('reports', 'month', 'organization'));
        return $pdf->stream('report.pdf');
    }

    private function query($request)
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


        return $query->first()?->members;
    }

    public function get_trx(Request $request)
    {

        if ($request->ajax()) {
            $data = Payment::select('*');
            if ($request->filled('from_date') && $request->filled('to_date')) {
                $data = $data->whereBetween('created_at', [$request->from_date, $request->to_date]);

            }

            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
                        $btn = '<a href="javascript:void(0)" class="edit btn btn-outline-success text-end rounded-4 btn-sm">View</a>';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
    }

    public function getCount(Request $request)
    {
        $start = $request->start;
        $end = $request->end;
        $count = getSumOfTransactionsFilter($start,$end);
        return response()->json(['count' => $count]);

    }
    public function print_trx(Request $request)
    {
        $daterange = $request->input('daterange');

        if ($daterange) {
            $dates = explode(' - ', $daterange);
            $from_date = $dates[0];
            $to_date = $dates[1];
            $data = Payment::select('*');

            if (!empty($from_date) && !empty($to_date)) {
                $data = $data->whereBetween('created_at', [$from_date, $to_date]);
            }
            $reports = $data->get();
            return response()->json($reports, 200);
        }
    }

    public function transaction($start=null, $end=null)
    {
        $trx = Payment::paginate(10);

        $all = getSumAllOfTransactions();
        $s_now = Carbon::now()->startOfMonth()->toDateString();
        $e_now = Carbon::now()->endOfMonth()->toDateString();
        $s_last = Carbon::now()->subMonth()->startOfMonth()->toDateString();
        $e_last = Carbon::now()->subMonth()->endOfMonth()->toDateString();
        $monthbefore = getSumOfTransactions($s_last, $e_last);
        $monthly = getSumOfTransactions($s_now, $e_now);
        $monthly = getSumOfTransactions($s_now, $e_now);

        return view('report.report-transaction', compact('trx', 'monthly', 'monthbefore','all'));
    }
}
