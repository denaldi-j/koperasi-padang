<?php

namespace App\Http\Controllers;

use App\Models\Balance;
use App\Models\Organization;
use Illuminate\Http\Request;

class BalanceController extends Controller
{
    public function index()
    {
        $organization = Organization::all();
        return view('balance.index', compact('organization'));
    }

    public function show($id)
    {
        $balance = Balance::query()->find($id);
        return view('balance.show', compact('balance'));
    }

    public function get($id)
    {
        return Balance::query()
            ->where('id', $id)
            ->with(['deposits', 'member'])
            ->first();
    }

    public function getByMember($id)
    {
        return Balance::query()->whereHas('member', function ($query) use ($id) {
                $query->where('id', $id);
            })
            ->first();
    }

    public function update($id, Request $request)
    {
        $balance = Balance::query()->find($id);
        $balance->monthly_deposit = $request->monthly_deposit;

        if ($balance->update()) {
            return response()->json(['success' => true, 'message' => 'Berhasil']);
        }

        return response()->json(['success' => false, 'message' => 'Gagal']);
    }

}
