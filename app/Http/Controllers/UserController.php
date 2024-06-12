<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return view('user.index', compact('roles'));
    }

    public function store(Request $request)
    {
        $member = Member::query()->find($request->member);
        $user = new User();
        $user->name = $member->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->member_id = $member->id;

        if($user->save()) {
            $user->assignRole($request->role);
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

    public function get()
    {
        $users = User::all();

        return DataTables::collection($users)
            ->addColumn('role', function ($user) {
                return $user->getRoleNames()->implode('name');
            })
            ->toJson();
    }
}
