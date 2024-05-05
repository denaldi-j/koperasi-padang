<?php

namespace App\Actions\Member;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class FetchOrganizations
{
    public function handle()
    {
        $collection = Cache::get('organizations');

        if(!$collection) {
            $response   = Http::get('http://103.141.74.121:81/api/v1/master-organisasi');
            $results    = $response->collect('data');

            if($results->isNotEmpty()) {
                $collection = collect($results)->map(function ($item) {
                    return [
                        'name'  => $item['UNOR_INDUK_NAMA'],
                        'code'  => $item['UNOR_INDUK_ID'],
                    ];
                });

                Cache::put('organizations', $collection);
            }
        }

        return $collection;
    }
}
