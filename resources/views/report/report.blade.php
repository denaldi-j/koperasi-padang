<div style="text-align: center">
    <p style="font-size: 16px; font-weight: bold">
        DAFTAR NAMA PENERIMA VOUCHER KPN MART<br>
        {{ $organization }}<br>
        {{ strtoupper($month) }}
    </p>

    <hr>
</div>

<table border="1" style="border: 1px solid black; border-collapse: collapse; width: 100%">
    <tr style="font-weight: bold;">
        <td style="text-align: center; padding: 0.5rem">No.</td>
        <td style="padding: 0.5rem">Nama</td>
        <td style="text-align: center; padding: 0.5rem">Voucher KPN <br>(+ Diskon 1%)</td>
        <td style="text-align: center; padding: 0.5rem">Total Belanja</td>
        <td style="text-align: center; padding: 0.5rem">Sisa Voucher</td>
        <td style="text-align: center; padding: 0.5rem">Pembayaran <br>Cash</td>
    </tr>

    @php($i=1)
    @foreach($reports as $report)
        <tr>
            <td style="text-align: center">{{ $i++ }}</td>
            <td style="padding-left: 0.5rem">{{ $report->name }}</td>
            <td style="text-align: right; padding-right: 0.5rem">{{ number_format($report->balance->total_deposit, 0, '.', '.') }}</td>
            <td style="text-align: right; padding-right: 0.5rem">{{ number_format($report->balance->total_payment, 0, '.', ',')  }}</td>
            <td style="text-align: right; padding-right: 0.5rem">{{ number_format($report->balance->final_balance, 0, '.', ',') }}</td>
            <td style="text-align: right; padding-right: 0.5rem">{{ number_format($report->balance->payment_on_cash, 0, '.', ',') }}</td>
        </tr>
    @endforeach

</table>
