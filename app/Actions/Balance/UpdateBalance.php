<?php

namespace App\Actions\Balance;

use App\Models\Balance;
use App\Models\Deposit;
use App\Models\Payment;

class UpdateBalance
{
    public function handle($balance = null): void
    {
        $amount = Deposit::query()
            ->where('balance_id', $balance)
            ->sum('amount');

        $payment = Payment::query()
            ->where('balance_id', $balance)
            ->sum('final_amount');

        $balance = Balance::query()->where('id', $balance)->first();

        $final_balance = $amount - $payment;

        $balance->amount = $amount;
        $balance->total_transaction = $payment;
        $balance->final_balance = max($final_balance, 0);
        $balance->update();

    }
}
