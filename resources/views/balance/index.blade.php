@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <select class="form-control w-lg-50 w-sm-100" id="opd" name="opd">
                <option value="">Semua Organisasi</option>
                @foreach($organization as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="table-responsive">
            <table class="table text-nowrap" id="balanceTable">
                <thead>
                <tr>
{{--                    <th style="width: 7%" class="text-center">No.</th>--}}
                    <th>Anggota</th>
                    <th>Total Setor</th>
                    <th>Total Transaksi</th>
                    <th>Saldo Akhir</th>
                    <th style="width: 10%"></th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div id="balanceModal" class="modal fade" data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Saldo Bulanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body mb-3">
                    <form action="{{ route('deposit.store-monthly') }}" method="post" id="depositForm"> @csrf
                        <div class="mb-3">
                            <input type="hidden" id="organization_id" name="organization_id" class="form-control" required>
                            <label class="form-label fw-bold" id="organization_name"></label>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal:</label>
                            <input type="date" class="form-control" id="date" name="date" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jumlah Saldo (ASN):</label>
                            <input type="number" class="form-control" id="amount_asn" name="amount_asn" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jumlah Saldo (Non ASN):</label>
                            <input type="number" class="form-control" id="amount_non" name="amount_non" required>
                        </div>

                        <div class="mt-2">
                            <button type="submit" class="btn btn-outline-secondary btn-labeled btn-labeled-start rounded-pill">
                                        <span class="btn-labeled-icon bg-secondary text-white rounded-pill">
                                            <i class="ph-floppy-disk"></i>
                                        </span>
                                Simpan Saldo Bulanan
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
    <script src="{{ asset('assets/js/vendor/tables/datatable-buttons.min.js') }}"></script>
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

            loadData();
            function loadData() {
                const table = $('#balanceTable').DataTable({
                    buttons: [
                        {
                            text: 'Tambah Saldo Bulanan',
                            className: 'btn btn-teal',
                            action: function(e, dt, node, config) {
                                showModal();
                            }
                        }
                    ],
                    ajax: {
                        url: '{{ route('members.get') }}',
                        type: 'get',
                        data: { organization_id: $('#opd').val() }
                    },
                    destroy: true,
                    columns: [
                        { data: 'name' },
                        { data: 'balance.amount', className: 'text-end',
                            render: function (data) {
                                return (data) ? data.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.") : 0;
                            }
                        },
                        { data: 'balance.total_transaction', className: 'text-end',
                            render: function (data) {
                                return (data) ? data.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.") : 0;
                            }
                        },
                        { data: 'balance.final_balance', className: 'text-end',
                            render: function (data) {
                                return (data) ? data.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.") : 0;
                            }
                        },
                        { data: 'id', className: 'text-center',
                            render: function (data, type, row) {
                                return `<a href="{{ url('balance/show') }}/${data}" type="button" class="btn btn-sm btn-outline-secondary btn-labeled btn-labeled-start rounded-pill">
                                        <span class="btn-labeled-icon bg-secondary text-white rounded-pill">
                                            <i class="ph-info"></i>
                                        </span>
                                        Detail
                                    </a>`;
                            }
                        },
                    ]
                });
            }

            function showModal() {
                if($('#opd').val() === '') {
                    new Noty({
                        text: 'Silahkan pilih Organisasi!',
                        type: 'error'
                    }).show();
                } else {
                    $('#balanceModal').modal('show');
                    $('#organization_id').val($('#opd').val());
                    $('#organization_name').html($('#opd option:selected').text())
                }
            }

            $('select#opd').change(function () {
               loadData();
            });

            $('#depositForm').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'post',
                    data: $(this).serialize(),
                    success: function (res) {
                        new Noty({
                            text: res.message,
                            type: res.status === true ? 'success' : 'error'
                        }).show();
                    }
                }).done(function () {
                    $('#balanceTable').DataTable().ajax.reload();
                    $('#balanceModal').modal('hide');
                });
            });
        })
    </script>
@endprepend
