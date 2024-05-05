@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <h5 class="mb-0">Saldo</h5>
            <div class="d-inline-flex ms-auto">
                <div class="dropdown d-inline-flex ms-3">
                    <a href="#" class="text-body d-inline-flex align-items-center dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="ph-gear"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a href="#" class="dropdown-item">
                            <i class="ph-arrows-clockwise me-2"></i>
                            Update data
                        </a>
                        <a href="#" class="dropdown-item">
                            <i class="ph-list-dashes me-2"></i>
                            Detailed log
                        </a>
                        <a href="#" class="dropdown-item">
                            <i class="ph-chart-line me-2"></i>
                            Statistics
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="ph-eraser me-2"></i>
                            Clear list
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body d-sm-flex align-items-sm-center justify-content-sm-between flex-sm-wrap">
            <div class="d-flex align-items-center mb-3 mb-sm-0">
                <div id="campaigns-donut"></div>
                <div class="ms-3">
                    <div class="d-flex align-items-center">
                        <h5 class="mb-0">Rp {{ number_format($balance->final_balance, 0, ',', '.') }}</h5>
                    </div>
                    <span class="d-inline-block bg-success rounded-pill p-1 me-1"></span>
                    <span class="text-muted">Saldo Akhir</span>
                </div>
            </div>

            <div class="d-flex align-items-center mb-3 mb-sm-0">
                <div id="campaigns-donut"></div>
                <div class="ms-3">
                    <div class="d-flex align-items-center">
                        <h5 class="mb-0">Rp {{ number_format($balance->amount, 0, ',', '.') }}</h5>
                    </div>
                    <span class="d-inline-block bg-success rounded-pill p-1 me-1"></span>
                    <span class="text-muted">Total Deposit</span>
                </div>
            </div>

            <div class="d-flex align-items-center mb-3 mb-sm-0">
                <div id="campaign-status-pie"></div>
                <div class="ms-3">
                    <div class="d-flex align-items-center">
                        <h5 class="mb-0">Rp {{ number_format($balance->total_transaction, 0, ',', '.') }}</h5>
                    </div>
                    <span class="d-inline-block bg-danger rounded-pill p-1 me-1"></span>
                    <span class="text-muted">Total Transaksi</span>
                </div>
            </div>

            <div class="d-flex align-items-center mb-3 mb-sm-0">
                <div id="campaign-status-pie"></div>
                <div class="ms-3">
                    <div class="d-flex align-items-center">
                        <h5 class="mb-0">Rp {{ number_format($balance->monthly_deposit, 0, ',', '.') }}</h5>
                    </div>
                    <span class="d-inline-block bg-danger rounded-pill p-1 me-1"></span>
                    <span class="text-muted">Batas Deposit bulanan</span>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col">
                <div class="table-responsive border border-light">
                    <table class="table text-nowrap" id="depositTable">
                        <thead>
                        <tr>
                            <th colspan="2">Riwayat Deposit</th>
                        </tr>
                        </thead>
                        <tbody>
{{--                        <tr>--}}
{{--                            <td class="fw-normal">--}}
{{--                                januari 2024--}}
{{--                            </td>--}}
{{--                            <td><h6 class="mb-0">$5,489</h6></td>--}}

{{--                        </tr>--}}
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col">
                <div class="table-responsive border border-light">
                    <table class="table text-nowrap" id="paymentTable">
                        <thead>
                        <tr>
                            <th>Riwayat Transaksi</th>
                            <th>Jumlah</th>
                            <th>Diskon</th>
                            <th>Total Bayar</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@prepend('scripts')
    <script>
        $(function () {
            $('#balanceMenu').addClass('active');
            $.extend( $.fn.dataTable.defaults, {
                autoWidth: false,
                dom: '<"datatable-header justify-content-start"f<"ms-sm-auto"l><"ms-sm-3"B>><"datatable-scroll-wrap"t><"datatable-footer"ip>',
                language: {
                    search: '<span class="me-3">Filter:</span> <div class="form-control-feedback form-control-feedback-end flex-fill">_INPUT_<div class="form-control-feedback-icon"><i class="ph-magnifying-glass opacity-50"></i></div></div>',
                    searchPlaceholder: 'Type to filter...',
                    lengthMenu: '<span class="me-3">Show:</span> _MENU_',
                    paginate: { 'first': 'First', 'last': 'Last', 'next': document.dir == "rtl" ? '&larr;' : '&rarr;', 'previous': document.dir == "rtl" ? '&rarr;' : '&larr;' }
                }
            });

            const depositTable = $('#depositTable').DataTable({
                ajax: '{{ route('deposit.get', $balance->id) }}',
                searching: false,
                paging: false,
                columns: [
                    { data: 'created_at' },
                    { data: 'amount' },
                ]
            });

            const paymentTable = $('#paymentTable').DataTable({
                ajax: '{{ route('payments.get', $balance->id) }}',
                searching: false,
                paging: false,
                sorting: false,
                columns: [
                    { data: 'created_at' },
                    { data: 'amount' },
                    { data: 'discount' },
                    { data: 'final_amount' }
                ]
            })
        })
    </script>
@endprepend
