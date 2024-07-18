<?php

use App\Models\Payment;

if (!function_exists('getSumOfTransactions')) {
    /**
     * Get the sum of transactions within a specified date range.
     *
     * @param string $start
     * @param string $end
     * @return float
     */
    function getSumOfTransactions($start = null, $end = null)
    {
        return Payment::whereBetween('created_at', [$start, $end])->sum('amount');
    }

}

if(!function_exists('getSumAllOfTransactions')){

    function getSumAllOfTransactions()
    {
        return Payment::sum('amount');
    }
}

if(!function_exists('getSumOfTransactionsFilter')){

    function getSumOfTransactionsFilter($start, $end)
    {
        return Payment::whereBetween('created_at', [$start, $end])->sum('amount');
    }
}
