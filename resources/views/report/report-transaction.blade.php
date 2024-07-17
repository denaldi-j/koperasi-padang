@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-6 col-xl-3">
            <div class="card card-body bg-primary text-white">
                <div class="d-flex align-items-center">
                    <div class="flex-fill">
                        <h4 class="mb-0">Rp

                        </h4>
                        Total Transaction
                    </div>

                    <i class="ph-chats ph-2x opacity-75 ms-3"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card p-1">
        <div class="row">
            <div class="col-lg-8 col-md-6">
                <div class="nav nav-tab nav-tabs-dark d-flex ms-2 rounded p-1">
                    <div class="nav-item">
                        <h5 class="title p-1 mb-0">Laporan Transaksi</h5>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-6 justify-content-end">
                <input type="text" name="daterange" class="form-control mt-1" value="" />
            </div>
            <div class="col-lg-1 col-md-6 text-end">
                <button class="btn btn-primary filter mt-1">Filter</button>
            </div>
            <div class="col-lg-1 col-md-6 text-start">
                <button class="btn btn-primary mt-1" id="print">Print</button>

                {{-- <a href="{{ route('reports.export-all') }}" class="btn btn-primary mt-1" target="_blank">Print</a> --}}

            </div>
        </div>
    </div>
    <div class="card">

        <div class="">
            <table class="table table-responsive data-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Tanggal</th>
                        <th>Total Voucher</th>
                        <th>Total Belanja</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($trx as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->created_at }}</td>
                            <td>{{ $item->amount }}</td>
                            <td>{{ $item->total_amount }}</td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
@endsection
@prepend('scripts')
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="{{ asset('assets/js/vendor/picker/daterangepicker.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/picker/datepicker.min.js') }}"></script>

    <script>
        $('#print').click(function() {
            var daterange = $('input[name="daterange"]').val();

            if (daterange === "") {
                new Noty({
                    text: 'Harap pilih Date',
                    type: 'error'
                }).show();
            } else {
                window.open('{{ route('reports.export-all') }}?' + $.param({
                    daterange: daterange
                }), '_blank');
            }
        });

        $(function() {
            var start = null;
            var end = null;

            $('input[name="daterange"]').daterangepicker({
                autoApply: true,
                autoUpdateInput: false,
                ranges: {
                    'All': [moment().subtract(5, 'year')],
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')],

                }

            });

            $('input[name="daterange"]').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format(
                    'YYYY-MM-DD'));
                start = picker.startDate.format('YYYY-MM-DD');
                end = picker.endDate.format('YYYY-MM-DD');
            });

            $('input[name="daterange"]').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                start = null;
                end = null;
            });

            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('reports.get-trx') }}",
                    data: function(d) {
                        d.from_date = start;
                        d.to_date = end;
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        render: function(data, type, full, meta) {
                            return moment(data).format('YYYY-MM-DD HH:mm:ss');
                        }
                    },
                    {
                        data: 'amount',
                        name: 'amount',
                        render: function(data) {
                            return (data) ? data.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g,
                                "$1.") : 0;
                        }
                    },
                    {
                        data: 'total_amount',
                        name: 'total_amount',
                        render: function(data) {
                            return (data) ? data.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g,
                                "$1.") : 0;
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                createdRow: function(row, data, dataIndex) {
                    $(row).find('td:eq(0)').html(dataIndex + 1);
                }
            });

            // Muat ulang tabel saat tombol filter ditekan
            $(".filter").click(function() {
                table.draw();
            });
        });
    </script>



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
    </script>
@endprepend
