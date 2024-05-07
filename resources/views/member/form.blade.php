@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header mb-0">
            <div class="d-flex flex-wrap justify-content-between">
                <select class="form-control w-sm-100 w-lg-50" id="organization" name="organization">
                    <option>Pilih Organisasi</option>
                    @foreach($organizations as $organization)
                        <option value="{{ $organization->code }}" data-id="{{ $organization->id }}">{{ $organization->name }}</option>
                    @endforeach
                </select>
                @hasrole('admin-opd|super-admin')
                    <div class="btn-group">
                        <button type="button" class="btn btn-flat-primary btn-labeled btn-labeled-start dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                <span class="btn-labeled-icon bg-primary text-white">
                                                    <i class="ph-check-square-offset"></i>
                                                </span>
                            Tambah Anggota
                        </button>

                        <div class="dropdown-menu" style="">
                            <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#memberModal">Cari ASN</a>
                            <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#memberNaModal">Tambah Non ASN</a>
                        </div>
                    </div>
{{--                    <button class="btn btn-secondary rounded-pill" data-bs-toggle="modal" data-bs-target="#memberModal">Tambah Anggota</button>--}}
                @endhasrole
            </div>
        </div>
        <div class="table-responsive">
            <table class="table text-nowrap" id="membersTable">
                <thead>
                <tr>
{{--                    <th style="width: 7%" class="text-center">No.</th>--}}
                    <th>NIP</th>
                    <th>Nama</th>
                    <th>No. Hp</th>
                    <th></th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div id="memberModal" class="modal fade" data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Anggota</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body mb-3">
                    <div class="mb-3">
                        <label class="col-form-label">Pilih OPD</label>
                        <select class="form-control" id="organizationId" name="organization" required>
                            <option value="">- Pilih OPD -</option>
                            @foreach($organizations as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <input type="text" class="form-control" id="name" name="name" placeholder="Masukkan nama pegawai">
                    </div>
                    <div class="mb-3" id="memberList">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /modal -->

    <!-- Modal -->
    <div id="memberNaModal" class="modal fade" data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Anggota Non ASN</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body mb-3">
                    <form id="memberNaForm" action="{{ route('members.store') }}"> @csrf
                        <div class="mb-3">
                            <label class="col-form-label">Pilih OPD</label>
                            <select class="form-control" id="organizationId" name="organization" required>
                                <option value="">- Pilih OPD -</option>
                                @foreach($organizations as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="col-form-label">Nama</label>
                            <input type="text" class="form-control" id="nameNa" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="col-form-label">No. Hp</label>
                            <input type="text" class="form-control" id="phone" name="phone" required>
                        </div>
                        <div class="">
                            <button class="btn btn-secondary rounded-pill">Tambahkan Anggota</button>
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
            $('#memberMenu').addClass('active');

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

            $('#organization').change(function () {
                loadNewMembers();
            });

            $("input#name").keypress(function (e) {
                if (e.key === 'Enter' || e.keyCode === 13) {
                    $.ajax({
                        url: '{{ route('members.getByName') }}',
                        data: {
                            name: $(this).val()
                        },
                        async: false,
                        success: function (data) {
                            $('#memberList').html('');
                            if(data[0] === undefined) {
                                if($.isEmptyObject(data)) {
                                    $('#memberList').html('Data tidak ditemukan');
                                } else {
                                    $('#memberList').html(`<div class="d-flex flex-wrap justify-content-between"><span>${data.name} - NIP ${data.nip}</span>
                                    <button data-nip="${data.nip}" data-name="${data.name}" class="btn btn-link" id="createMember">tambahkan</button></div>`);
                                }
                            } else {
                                $.each(data, function (k, v) {
                                    $('#memberList').append(`<p class="d-flex flex-wrap justify-content-between"><span>${v.name} - NIP ${v.nip}</span>
                                    <button class="btn btn-link" data-nip="${v.nip}" data-name="${v.name}" id="createMember">tambahkan</button></p>`)
                                });
                            }
                        }

                    });
                }
            });

            function loadNewMembers() {
                let code = $('#organization option:selected').val();
                $('#membersTable').DataTable({
                    ajax: '{{ url('organizations/get-new-members') }}/'+code,
                    destroy: true,
                    paging: false,
                    columns: [
                        { data: 'nip' },
                        { data: 'name' },
                        { data: 'phone' },
                        { data: 'nip', className: 'text-end',
                            render: function (data, type, row) {
                                return '<button class="btn btn-secondary rounded-pill" id="addMember">Tambahkan</button>';
                            }
                        },
                    ]

                });
            }

            $(document).on('click', '#addMember', function (e) {
                e.preventDefault();
                let data = $('#membersTable').DataTable().row($(this).parents('tr')).data();
                $.ajax({
                    url: '{{ route('members.store') }}',
                    type: 'post',
                    data: {
                        _token  : '{{ csrf_token() }}',
                        nip     : data.nip,
                        name    : data.name,
                        phone   : data.phone,
                        organization : $('#organization option:selected').data('id')
                    },
                    success: function (res) {
                        new Noty({
                            text: res.message,
                            type: res.status == true ? 'success' : 'error'
                        }).show();
                    }
                }).done(function () {
                    loadNewMembers()
                })
            });

            $(document).on('click', '#createMember', function () {
                let payload = {
                    _token  : '{{ csrf_token() }}',
                    nip     : $(this).data('nip'),
                    name    : $(this).data('name'),
                    organization : $('#organizationId').val(),
                    phone   : null
                }

                storeMember(payload);
            });

            $('#memberNaForm').submit(function (e) {
                e.preventDefault();
                storeMember($(this).serialize());
            });

            function storeMember(payload) {
                $.ajax({
                    url: '{{ route('members.store') }}',
                    type: 'post',
                    data: payload,
                    success: function (res) {
                        new Noty({
                            text: res.message,
                            type: res.status == true ? 'success' : 'error'
                        }).show();
                    }
                }).done(function () {
                    $('.modal').modal('hide');
                    loadNewMembers()
                })
            }

        });

    </script>
@endprepend
