<?php

namespace App\Http\Controllers;

use App\Actions\Member\FetchOrganizationMember;
use App\Models\Member;
use App\Models\Organization;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class OrganizationController extends Controller
{
    public function get($id = null)
    {
        $results = Organization::query();

        if($id) {
            $results = $results->where('id', $id);
        }

        return $results->get();
    }

    public function getNewMember($code, FetchOrganizationMember $organizationMember)
    {
        $results        = $organizationMember->handle($code);

        $activeMember   = Member::query()
            ->whereHas('organization', function ($organization) use($code) {
                $organization->where('code', $code);
            })
            ->pluck('nip')
            ->toArray();

        $newMembers = collect($results)->map(function($result) use($activeMember) {
                if(!in_array($result->nip, $activeMember) && !is_null($result)) {
                    return $result;
                };
            })->toArray();

        return DataTables::collection(array_filter($newMembers))->toJson();
    }
}
