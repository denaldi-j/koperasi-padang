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
                        <a href="#" class="dropdown-item" id="updateMonthlyBalance">
                            <i class="ph-arrows-clockwise me-2"></i>
                            Update limit saldo bulanan
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
                        <h5 class="mb-0">Rp {{ number_format($balance?->final_balance, 0, ',', '.') }}</h5>
                    </div>
                    <span class="d-inline-block bg-success rounded-pill p-1 me-1"></span>
                    <span class="text-muted">Saldo Akhir</span>
                </div>
            </div>

            <div class="d-flex align-items-center mb-3 mb-sm-0">
                <div id="campaigns-donut"></div>
                <div class="ms-3">
                    <div class="d-flex align-items-center">
                        <h5 class="mb-0">Rp {{ number_format($balance?->amount, 0, ',', '.') }}</h5>
                    </div>
                    <span class="d-inline-block bg-success rounded-pill p-1 me-1"></span>
                    <span class="text-muted">Total Deposit</span>
                </div>
            </div>

            <div class="d-flex align-items-center mb-3 mb-sm-0">
                <div id="campaign-status-pie"></div>
                <div class="ms-3">
                    <div class="d-flex align-items-center">
                        <h5 class="mb-0">Rp {{ number_format($balance?->total_transaction, 0, ',', '.') }}</h5>
                    </div>
                    <span class="d-inline-block bg-danger rounded-pill p-1 me-1"></span>
                    <span class="text-muted">Total Transaksi</span>
                </div>
            </div>

            <div class="d-flex align-items-center mb-3 mb-sm-0">
                <div id="campaign-status-pie"></div>
                <div class="ms-3">
                    <div class="d-flex align-items-center">
                        <h5 class="mb-0">Rp {{ number_format($balance?->monthly_deposit, 0, ',', '.') }}</h5>
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
                            <th colspan="3">Riwayat Deposit</th>
                        </tr>
                        </thead>
                        <tbody>
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
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Deposit-->
    <div id="depositModal" class="modal fade" data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Saldo Bulanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body mb-3">
                    <form action="" method="post" id="depositForm"> @csrf
                        <div class="mb-3">
                            <label class="form-label">Jumlah Saldo:</label>
                            <input type="number" class="form-control" id="amount" name="amount" required>
                        </div>
                        <div class="mt-2">
                            <button type="submit" class="btn btn-outline-secondary btn-labeled btn-labeled-start rounded-pill">
                                        <span class="btn-labeled-icon bg-secondary text-white rounded-pill">
                                            <i class="ph-floppy-disk"></i>
                                        </span>
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /modal -->

    <!-- Modal Balance-->
    <div id="balanceModal" class="modal fade" data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Limit Saldo Bulanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body mb-3">
                    <form action="{{ route('balance.update',['id' => $balance->id]) }}" method="post" id="balanceForm"> @csrf
                        <div class="mb-3">
                            <label class="form-label">Jumlah Saldo:</label>
                            <input type="number" class="form-control" id="monthly_deposit" name="monthly_deposit" required>
                        </div>
                        <div class="mt-2">
                            <button type="submit" class="btn btn-outline-secondary btn-labeled btn-labeled-start rounded-pill">
                                        <span class="btn-labeled-icon bg-secondary text-white rounded-pill">
                                            <i class="ph-floppy-disk"></i>
                                        </span>
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /modal -->
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
                    { data: 'amount', class: 'text-end',
                        render: function (data) {
                            return (data) ? data.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.") : 0;
                        }
                    },
                    { data: 'id', class: 'text-end',
                        render: function (data) {
                            return '<a class="text-warning" href="#" id="editDepo">edit</a>';
                        }
                    }
                ]
            });

            $(document).on('click', '#editDepo', function (e) {
                $('#depositModal').modal('show');
                let data = $('#depositTable').DataTable().row($(this).parents('tr')).data();
                $('#depositForm').attr('action', '{{ url('deposit/update') }}/'+data.id);
                $('#amount').val(data.amount);
            });

            $('#depositForm').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'post',
                    data: $(this).serialize(),
                    success: function (res) {
                        new Noty({
                            text: res.message,
                            type: res.status == true ? 'success' : 'error'
                        }).show();
                        depositTable.ajax.reload();
                    }
                }).done(function () {
                    $('.modal').modal('hide');
                    window.location.reload()
                });
            });

            $('#updateMonthlyBalance').click(function (e) {
                e.preventDefault();
                $('#balanceModal').modal('show');
                $('#monthly_deposit').val('{{ $balance->monthly_deposit }}');
            });

            $('#balanceForm').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'post',
                    data: $(this).serialize(),
                    success: function (res) {
                        new Noty({
                            text: res.message,
                            type: res.status == true ? 'success' : 'error'
                        }).show();
                    }
                }).done(function () {
                    $('.modal').modal('hide');
                    window.location.reload()
                });
            });

            const paymentTable = $('#paymentTable').DataTable({
                ajax: '{{ route('payments.get', $balance?->id) }}',
                searching: false,
                paging: false,
                sorting: false,
                columns: [
                    { data: 'created_at' },
                    { data: 'amount', class: 'text-end',
                        render: function (data) {
                            return (data) ? data.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.") : 0;
                        }
                    },
                    { data: 'id', class: 'text-end',
                        render: function (data) {
                            return `<a href="#" id="deleteTr" data-id="${data}" class="text-danger" title="hapus transaksi"><i class="ph-trash-simple"></i></a>`
                        }
                    }

                ]
            })

            $(document).on('click', '#deleteTr', function (e) {
                e.preventDefault();
                if(confirm('Yakin ingin menghapus data transaksi?')) {
                    $.ajax({
                        url: '{{ route('payments.destroy', ['id' => '__id__']) }}'.replace('__id__', $(this).data('id')),
                        type: 'post',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function (res) {
                            new Noty({
                                text: res.message,
                                type: res.status === true ? 'success' : 'error'
                            }).show()
                        },
                        error: {
                            //
                        }
                    })
                }
            })
        })
    </script>
@endprepend
