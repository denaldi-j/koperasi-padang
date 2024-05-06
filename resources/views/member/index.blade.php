@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="table-responsive">
            <table class="table text-nowrap" id="membersTable">
                <thead>
                <tr>
{{--                    <th style="width: 7%" class="text-center">No.</th>--}}
{{--                    <th>NIP</th>--}}
                    <th>Nama</th>
                    <th>OPD</th>
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
                    <h5 class="modal-title">Edit Data Anggota</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <form id="memberForm"> @csrf
{{--                        <div class="mb-2">--}}
{{--                            <label class="col-form-label" for="nip">NIP</label>--}}
{{--                            <input class="form-control" id="nip" name="nip" required>--}}
{{--                        </div>--}}
                        <div class="mb-2">
                            <label class="col-form-label" for="name">Nama</label>
                            <input class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-2">
                            <label class="col-form-label" for="phone">No. Hp</label>
                            <input class="form-control" id="phone" name="phone">
                        </div>
                        <div class="mb-2">
                            <label class="col-form-label" for="organization">Pilih OPD</label>
                            <select class="form-control" id="organization" name="organization" required>
                                <option>- - -</option>
                                @foreach($organizations as $organization)
                                    <option value="{{ $organization->id }}">{{ $organization->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-3 mb-2">
                            <button class="btn btn-secondary" type="submit">Simpan</button>
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

            const table = $('#membersTable').DataTable({
                buttons: [
                    {
                        text: 'Tambah Anggota',
                        className: 'btn btn-teal',
                        action: function (e, dt, node, config) {
                            window.location.href = '{{ route('members.create') }}'
                        }
                    }
                ],
                ajax: '{{ route('members.get') }}',
                columns: [
                    // {data: 'nip'},
                    {data: 'name'},
                    {
                        data: 'organization',
                        render: function (data) {
                            return data.name;
                        }
                    },
                    {data: 'phone'},
                    {
                        data: 'nip', className: 'text-center',
                        render: function (data, type, row) {
                            return '<button class="btn btn-outline-secondary rounded-pill" id="editMember">edit</button>';
                        }

                    }
                ]
            });

            $(document).on('click', '#editMember', function () {
                 let data = table.row($(this).parents('tr')).data();
                 // $('#nip').val(data.nip);
                 $('#name').val(data.name);
                 $('#phone').val(data.phone);
                 $('#organization').val(data.organization_id);
                 $('#memberModal').modal('show');
                 $('#memberForm').attr('action', '{{ url('members/update') }}/'+data.id);
            });

            $('#memberForm').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'post',
                    data: $(this).serialize(),
                    success: function (res) {
                        table.ajax.reload();
                        new Noty({
                            text: res.message,
                            type: res.status == true ? 'success' : 'error'
                        }).show();
                        $('#memberModal').modal('hide');
                    }
                })
            });
        });


    </script>
@endprepend
