@extends('layouts.app')
@section('content')
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

    <div class="card p-1">
        <div class="row flex-col">
            <div class="col-lg-8 col-md-6">
                <div class="nav nav-tab nav-tabs-dark d-flex rounded p-1">
                    <div class="nav-item">
                        <h5 class="title p-1 mb-0">Laporan Transaksi</h5>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 ">
                <div class="input-group mt-1">
                    <span class="input-group-text">
                        <i class="ph-calendar"></i></span>
                    <input type="text" name="daterange" class="form-control daterange-basic "
                        placeholder="day/month/years">
                </div>
            </div>
            <div class="col-lg-1 col-md-6 text-center ">
                <div class="btn-group d-flex me-1">
                    <button class="filter btn btn-outline-light mt-1 btn-outline-primary">
                        Filter</button>
                </div>
            </div>
            {{-- <div class="col-lg-1 col-md-6 text-center">
                <button class="btn btn-sm btn-secondary mt-1" id="print">Print</button>
            </div> --}}
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <div class="col-lg-12 col-md-6 text-end">
                <button class="btn btn-outline-primary btn-labeled btn-labeled-start rounded-pill" id="print">
                    <span class="btn-labeled-icon bg-primary text-white rounded-pill">
                        <i class="ph-check-square-offset"></i>
                    </span>
                    Print</button>
            </div>

        </div>

        <div class="">
            <table class="table table-responsive data-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Total Amount</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($trx as $item)
                        <tr>
                            <td>{{ $loop->iteration ?? '' }}</td>
                            <td>{{ $item->created_at ?? '' }}</td>
                            <td>{{ $item->amount ?? '' }}</td>
                            <td>{{ $item->final_amount ?? '' }}</td>
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
                    text: 'Tolong Isi Date',
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
                locale: false,
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
                $('#datetrx').text(start + 's/d' + end);
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
                            return (data) ? formatRupiah(data) : 0;
                        }
                    },
                    {
                        data: 'final_amount',
                        name: 'final_amount',
                        render: function(data) {
                            return (data) ? formatRupiah(data) : 0;
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
            $(".filter").click(function() {
                table.draw();
                getCount();
            });

            function formatRupiah(number) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                }).format(number);
            }

            function getCount() {

                $.ajax({
                    url: '{{ route('reports.get-count') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        start: start,
                        end: end
                    },
                    success: function(response) {
                        console.log(response);
                        var formattedCount = formatRupiah(response.count);
                        $('#count').text(formattedCount);
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                    }
                });
            }

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
