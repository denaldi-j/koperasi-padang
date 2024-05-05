<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'display_name' => 'Super Admin',
                'name' => 'super-admin'
            ],
            [
                'display_name' => 'Pimpinan',
                'name' => 'pimpinan'
            ],
            [
                'display_name' => 'Admin OPD',
                'name' => 'admin-opd'
            ],
            [
                'display_name' => 'Operator',
                'name' => 'operator'
            ],
        ];

        foreach ($roles as $role) {
            Role::query()->updateOrCreate(['name' => $role['name']], ['display_name' => $role['display_name']]);
        }
    }
}
