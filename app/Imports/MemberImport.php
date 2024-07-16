<?php

namespace App\Imports;

use AllowDynamicProperties;
use App\Actions\Balance\UpdateBalance;
use App\Models\Balance;
use App\Models\Deposit;
use App\Models\Member;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class MemberImport implements ToModel, WithStartRow, SkipsOnError
{
    use Importable, SkipsErrors;
    protected $organization_id;
    protected $is_asn;

    public function __construct($request)
    {
        $this->organization_id  = $request['organization_id'];
        $this->is_asn           = $request['is_asn'];
    }

    /**
     * @param array $row
     *
     * @return Model|Member|null
     */
    public function model(array $row): Model|Member|null
    {
        $member = Member::query()->updateOrCreate(['member_code' => $row[1]], [
            'nip'               => null,
            'name'              => strval($row[2]),
            'phone'             => null,
            'organization_id'   => $this->organization_id,
            'is_asn'            => $this->is_asn,
        ]);

        $balance = Balance::query()->updateOrCreate(['member_id' => $member->id], [
            'amount'            => 0,
            'total_transaction' => 0,
            'final_balance'     => 0,
            'monthly_deposit'   => 0,
        ]);

        $month = date('m', strtotime(now()));
        $year  = date('Y', strtotime(now()));

        $deposit = Deposit::query()
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->where('balance_id', $balance->id)
            ->first();

        $plusDiscount = intval($row[3]); // + (intval($row[3]) * 1 / 100);

        if($deposit) {
            $deposit->date = now();
            $deposit->amount = $plusDiscount;
            $deposit->update();
        } else {
            Deposit::query()->create([
                'balance_id'    => $balance->id,
                'date'          => now(),
                'amount'        => $plusDiscount,
            ]);
        }

        $updateBalance = new UpdateBalance();
        $updateBalance->handle($balance->id);

        return  $member;


    }

    public function startRow(): int
    {
        return 2;
    }

    public function onError(\Throwable $e)
    {
        // Handle the exception how you'd like.
    }
}
