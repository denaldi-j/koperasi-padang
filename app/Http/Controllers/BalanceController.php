<?php

namespace App\Http\Controllers;

use App\Models\Balance;
use Illuminate\Http\Request;

class BalanceController extends Controller
{
    public function index()
    {
        return view('balance.index');
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
