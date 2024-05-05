<?php

namespace App\Actions\Member;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use function PHPUnit\Framework\objectEquals;

class GetEmployeeByName
{
    public function handle($name): object
    {
        $response = Http::get('http://103.141.74.121:81/api/v1/asn', ['nama' => $name]);
        $collection = $response->collect('data');

        if($collection->isEmpty()) {
           return (object) [];
        }

        $isMultiArray = self::isMultiArray($collection->toArray());
        if($isMultiArray) {
            return $collection->map(function ($item) {
                return (object) [
                    'nip'   => $item['NIP_BARU'],
                    'name'  => $item['NAMA']
                ];
            });
        } else {
            return (object) [
                'nip' => $collection['NIP_BARU'],
                'name' => $collection['NAMA']
            ];
        }

    }

    private function isMultiArray(array $array): bool
    {
        $rv = array_filter($array, 'is_array');
        if(count($rv)>0) return true;
        return false;
    }
}
