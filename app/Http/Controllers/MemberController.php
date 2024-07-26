<?php

namespace App\Http\Controllers;

use App\Actions\Member\GetEmployeeByName;
use App\Imports\MemberImport;
use App\Models\Balance;
use App\Models\Member;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class MemberController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
//        return [
//            new Middleware('role:operator', only: ['search']),
//        ];
    }

    public function index()
    {
        $organizations = Organization::all();
        return view('member.index', compact('organizations'));
    }

    public function create()
    {
        $query = Organization::query();

        if(auth()->user()->hasRole('admin-opd')){
            $query->where('id', auth()->user()->member?->organization_id);
        }

        $organizations = $query->get();

        return view('member.form', compact('organizations'));
    }

    public function update(Request $request, Member $member)
    {
        $member->name = $request->name;
        $member->organization_id = $request->organization;
        $member->phone = $request->phone;
        $member->member_code = $request->member_code;

        if($member->update()) {
            return response([
                'status' => true,
                'message' => 'Berhasil Menyimpan Data',
            ]);
        } else {
            return response([
                'status' => false,
                'message' => 'Gagal Menyimpan Data',
            ]);
        }
    }

    public function store(Request $request)
    {
        try {

            if(auth()->user()->hasRole('admin-opd')){
                $organization_id = auth()->user()->member?->organization_id;
            } else {
                $organization_id = $request->organization;
            }

            $nip = $request->has('nip') ? $request->nip : rand(4, 8);

            $member = Member::query()->updateOrCreate(['nip' => $nip], [
                'name'  => $request->name,
                'phone' => $request->phone,
                'organization_id' => $organization_id,
                'is_asn'    => $request->is_asn
            ]);

            $amount = 0;
            if($request->has('amount')) {
                $amount = $request->amount;
            }

            $member->balance()->delete();
            Balance::query()->updateOrCreate(['member_id' => $member->id], [
                    'amount' => $amount,
                    'total_transaction' => 0,
                    'final_balance' => $amount,
                    'monthly_deposit' => 0,
            ]);

            return response([
                'status' => true,
                'message' => 'Berhasil Menyimpan Data',
            ]);
        } catch (\Exception $e) {
            return response([
                'status' => false,
                'message' => 'Gagal menyimpan Data '. $e->getMessage(),
            ]);
        }
    }

    public function get(Request $request)
    {
        $query = Member::query();

        if(!is_null($request->organization_id)) {
            $query->where('organization_id', $request->organization_id);
        }

        if(auth()->user()->hasRole('admin-opd')) {
            $query->where('organization_id', auth()->user()?->member->organization_id);
        }

        $query->with(['organization', 'balance']);

        $members = $query->get();

        return DataTables::collection($members)->toJson();
    }

    public function search(Request $request)
    {
        $query = Member::query();

        if($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('member_code', 'like', '%'. $request->search. '%');
        }

        if(!empty($request->organization_id)) {
            $query->where('organization_id', $request->organization_id);
        }

        $query->inRandomOrder();

        return $query->limit(5)->get(['id', 'name', 'member_code'])->map(function ($value) {
            return [
                'id' => $value->id,
                'text' => $value->member_code .' - '. $value->name,
            ];
        });
    }

    public function getEmployeeByName(Request $request, GetEmployeeByName $getEmployeeByName)
    {
        return $getEmployeeByName->handle($request->name);
    }

    public function formImport()
    {
        $organizations = Organization::all();
        return view('member.import', compact('organizations'));
    }

    public function importFromExcel(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|mimes:xlsx,xls',
                'is_asn' => 'nullable',
                'organization_id' => 'required|exists:organizations,id',
            ]);


            Excel::import(new MemberImport($request->except('file')), $request->file('file'));
            return response([
                'status' => true,
                'message' => 'Berhasil Mengimport Data',
            ]);
        } catch (\Exception $exception) {
            return response([
                'status' => false,
                'message' => $exception->getMessage(),
            ]);
        }
    }

}
