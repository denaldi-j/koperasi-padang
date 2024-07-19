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
        $user->password = Hash::make($request->password);
        $user->organization_id = $member->id;

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

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->organization_id = $request->member ?? $user->organization_id;
        $user->username = $request->username;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        if ($user->save()) {

            $user->assignRole($request->role);
            return response([
                'status' => true,
                'message' => 'Saved',
            ]);
        } else {
            return response([
                'status' => false,
                'message' => 'Failed',
            ]);
        }
        return response([
            'status' => true,
            'message' => 'Saved',
        ]);
    }

    public function softdelete()
    {
        response([
            'status'=>''
        ]);
    }

}
