@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-sm-6 col-xl-3">
            <div class="card card-body bg-primary text-white">
                <div class="d-flex align-items-center">
                    <div class="flex-fill">
                        <h4 class="mb-0">Rp {{ number_format($balance->amount, 0 , ',', '.') }}</h4>
                        Total Deposit
                    </div>

                    <i class="ph-chats ph-2x opacity-75 ms-3"></i>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card card-body bg-danger text-white">
                <div class="d-flex align-items-center">
                    <div class="flex-fill">
                        <h4 class="mb-0">Rp {{ number_format($balance->transaction, 0, ',', '.') }}</h4>
                        Total Pembayaran
                    </div>

                    <i class="ph-package ph-2x opacity-75 ms-3"></i>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card card-body bg-success text-white">
                <div class="d-flex align-items-center">
                    <i class="ph-hand-pointing ph-2x opacity-75 me-3"></i>

                    <div class="flex-fill text-end">
                        <h4 class="mb-0">{{ $members }}</h4>
                        Jumlah Anggota
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card card-body bg-indigo text-white">
                <div class="d-flex align-items-center">
                    <i class="ph-users-three ph-2x opacity-75 me-3"></i>

                    <div class="flex-fill text-end">
                        <h4 class="mb-0">Rp {{ number_format($todayTransaction, 0, ',', '.') }}</h4>
                        Tansaksi Hari ini
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
