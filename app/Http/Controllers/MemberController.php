<?php

namespace App\Http\Controllers;

use App\Actions\Member\GetEmployeeByName;
use App\Models\Member;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Yajra\DataTables\Facades\DataTables;

class MemberController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('role:operator', only: ['search']),
        ];
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
        $member->nip = $request->nip;
        $member->name = $request->name;
        $member->organization_id = $request->organization;
        $member->phone = $request->phone;

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
            $member = Member::query()->updateOrCreate(['nip' => $request->nip], [
                'name'  => $request->name,
                'phone' => $request->phone,
                'organization_id' => auth()->user()->member?->organization_id,
            ]);

            $member->balance()->create([
                'amount' => 0,
                'total_transaction' => 0,
                'final_balance' => 0,
                'monthly_deposit' => 0,
            ]);

            return response([
                'status' => true,
                'message' => 'Berhasil Menyimpan Data',
            ]);
        } catch (\Exception $e) {
            return response([
                'status' => false,
                'message' => 'Gagal menyimpan Data',
            ]);
        }
    }

    public function get()
    {
        $query = Member::query();

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
                ->orWhere('nip', $request->search);
        }

        $query->inRandomOrder();

        return $query->limit(5)->get(['id', 'name', 'nip'])->map(function ($value) {
            return [
                'id' => $value->id,
                'text' => $value->nip .' - '. $value->name,
            ];
        });
    }

    public function getEmployeeByName(Request $request, GetEmployeeByName $getEmployeeByName)
    {
        return $getEmployeeByName->handle($request->name);
    }

}
