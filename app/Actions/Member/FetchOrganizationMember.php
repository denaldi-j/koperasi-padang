<?php

namespace App\Actions\Member;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class FetchOrganizationMember
{
    public function handle($code)
    {
        $collection = Cache::get('organization_members'. $code);

        if(!$collection) {
            $response   = Http::post('http://103.141.74.121:81/api/v1/asn-per-organisasi', ['unor_induk_id' => $code]);
            $results    = $response->collect('data');

            if($results->isNotEmpty()) {
                $collection = $results->map(function ($item) use($code) {
                    return (object) [
                        'name'      => $item['NAMA'],
                        'nip'       => $item['NIP_BARU'],
                        'org_code'  => $code,
                        'phone'     => null
                    ];
                });
            }
        }

        return $collection;
    }
}
