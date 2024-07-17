@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Laporan</h5>
        </div>
        <div class="card-body">
            <form id="filterForm"> @csrf
                <div class="d-flex flex-wrap justify-content-start">
                    <div class="me-2 mb-3">
                        <select class="form-control" id="organization" name="organization">
                            <option value="">- Pilih OPD -</option>
                            @foreach ($organizations as $organization)
                                <option value="{{ $organization->id }}">{{ $organization->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="me-2 mb-3">
                        <input class="form-control datepicker-pick-level" id="date" name="date"
                            placeholder="bulan/tahun">
                    </div>
                    <div class="me-2 mb-3">
                        <button class="btn btn-secondary rounded-pill" type="submit"><i
                                class="ph-magnifying-glass me-2"></i>Tampil</button>
                    </div>
                    <div class="me-2 mb-3">
                        <button class="btn btn-danger rounded-pill" type="button" id="exportPDF"><i
                                class="ph-file-pdf me-2"></i>Export PDF</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="table-responsive">
            <table id="reportTable" class="table table-hover">
                <thead>
                    <th>Nama</th>
                    <th>Total Voucher</th>
                    <th>Total Belanja</th>
                    <th>Pembayaran Cash</th>
                    <th></th>
                </thead>
            </table>
        </div>
    </div>
@endsection

@prepend('scripts')
    <script src="{{ asset('assets/js/vendor/picker/daterangepicker.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/picker/datepicker.min.js') }}"></script>
    <script>
        $.extend($.fn.dataTable.defaults, {
            autoWidth: false,
            dom: '<"datatable-header justify-content-start"f<"ms-sm-auto"l><"ms-sm-3"B>><"datatable-scroll-wrap"t><"datatable-footer"ip>',
            language: {
                search: '<span class="me-3">Filter:</span> <div class="form-control-feedback form-control-feedback-end flex-fill">_INPUT_<div class="form-control-feedback-icon"><i class="ph-magnifying-glass opacity-50"></i></div></div>',
                searchPlaceholder: 'Type to filter...',
                lengthMenu: '<span class="me-3">Show:</span> _MENU_',
                paginate: {
                    'first': 'First',
                    'last': 'Last',
                    'next': document.dir == "rtl" ? '&larr;' : '&rarr;',
                    'previous': document.dir == "rtl" ? '&rarr;' : '&larr;'
                }
            }
        });

        $(function() {
            $('#reportMenu').addClass('active');
            const dpPickLevelElement = document.querySelector('.datepicker-pick-level');
            if (dpPickLevelElement) {
                const dpPickLevel = new Datepicker(dpPickLevelElement, {
                    container: '.content-inner',
                    buttonClass: 'btn',
                    prevArrow: document.dir == 'rtl' ? '&rarr;' : '&larr;',
                    nextArrow: document.dir == 'rtl' ? '&larr;' : '&rarr;',
                    pickLevel: 1,
                    format: 'mm/yyyy'
                });
            }

            $('#filterForm').submit(function(e) {
                e.preventDefault();
                loadReport()
            });

            $('#exportPDF').click(function() {

                if ($('#organization').val() === "" || $('#date').val() === "") {
                    new Noty({
                        text: 'Harap pilih OPD dan Bulan/Tahun',
                        type: 'error'
                    }).show();
                } else {
                    window.open('{{ route('reports.export-pdf') }}/?' + $('#filterForm').serialize(),
                        '_blank');
                }
            });

            function loadReport() {
                let reportTable = $('#reportTable').DataTable({
                    ajax: {
                        url: '{{ route('reports.get') }}',
                        type: 'get',
                        data: {
                            organization: $('#organization').val(),
                            date: $('#date').val()
                        }
                    },
                    destroy: true,
                    columns: [{
                            data: 'name'
                        },
                        {
                            data: 'balance.total_deposit',
                            className: 'text-end',
                            render: function(data) {
                                return (data) ? data.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g,
                                    "$1.") : 0;
                            }
                        },
                        {
                            data: 'balance.total_payment',
                            className: 'text-end',
                            render: function(data) {
                                return (data) ? data.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g,
                                    "$1.") : 0;
                            }
                        },
                        {
                            data: 'balance.payment_on_cash',
                            className: 'text-end',
                            render: function(data) {
                                return (data) ? data.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g,
                                    "$1.") : 0;
                            }
                        },
                        {
                            data: 'id',
                            className: 'text-end',
                            render: function(data) {
                                return `<a href="{{ url('balance/show') }}/${data}" class="btn btn-sm btn-outline-secondary btn-labeled btn-labeled-start rounded-pill">
                                <span class="btn-labeled-icon bg-secondary text-white rounded-pill">
                                            <i class="ph-info"></i>
                                        </span>
                                        Detail
                                </a>`;
                            }

                        }
                    ]

                })
            }
        });
    </script>
@endprepend
