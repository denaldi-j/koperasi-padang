<?php

namespace Database\Seeders;

use App\Actions\Member\FetchOrganizations;
use App\Models\Organization;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(FetchOrganizations $organizations): void
    {
        $results = $organizations->handle();

        foreach ($results as $result) {
            Organization::query()->updateOrCreate(['code' => $result['code']], [
                'name' => $result['name'],
            ]);
        }
    }
}
