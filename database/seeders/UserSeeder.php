<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::query()->updateOrCreate(['username' => 'admin'], [
            'name' => 'Administrator',
            'password' => Hash::make('password@123'),
            'email' => 'admin@admin.com',
        ]);

        $user->syncRoles(['super-admin']);
    }
}
