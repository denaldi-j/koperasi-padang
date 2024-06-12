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

}
