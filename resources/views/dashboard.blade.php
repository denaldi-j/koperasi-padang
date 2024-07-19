@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-sm-6 col-xl-3">
            <div class="card card-body bg-primary text-white">
                <div class="d-flex align-items-center">
                    <div class="flex-fill">
                        <h4 class="mb-0">Rp {{ number_format($balance->amount, 0, ',', '.') }}</h4>
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
    <div class="row">
        <div class="col-sm-6 col-xl-6">
            <div class="card">
                <div class="card-header d-sm-flex align-items-sm-center py-sm-0">
                    <h5 class="py-sm-2 my-sm-1">Transactions statistics</h5>
                    <div class="mt-2 mt-sm-0 ms-sm-auto">
                        {{-- <span id="datetrx">Date</span> --}}
                    </div>
                </div>

                <div class="card-body pb-0">
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="mb-3">
                                <h5 class="mb-0" id="count">-</h5>
                                <div class="text-muted fs-sm">Sum by Filter Transaction</div>
                                <span class="fs-sm" id="datetrx">Date</span>
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="mb-3">
                                <h5 class="mb-0">{{ number_format($monthly) ?? '' }}</h5>
                                <div class="text-muted fs-sm">This Month</div>
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="mb-3">
                                <h5 class="mb-0">{{ number_format($monthbefore) ?? '' }}</h5>
                                <div class="text-muted fs-sm">Last Month</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-6">

            <div class="card">
                <div class="card-header d-sm-flex align-items-sm-center py-sm-0">
                    <h5 class="py-sm-2 my-sm-1 text-end">All Transactions</h5>
                    <div class="mt-2 mt-sm-0 ms-sm-auto">
                        {{-- <span id="datetrx">Date</span> --}}
                    </div>
                </div>
                <div class="card-body d-flex align-items-center">
                    <div class="flex-fill">
                        <h5 class="mb-3">Rp {{ number_format($all) ?? '' }}
                        </h5>
                        Total Transaction
                    </div>
                    <i class="ph-money ph-2x opacity-75 ms-3"></i>
                </div>
            </div>
        </div>
    </div>
@endsection
