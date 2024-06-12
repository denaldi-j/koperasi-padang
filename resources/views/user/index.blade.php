@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="table-responsive">
            <table class="table text-nowrap" id="userTable">
                <thead>
                <tr>
                    <th style="width: 7%" class="text-center">No.</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th style="width: 10%"></th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div id="userModal" class="modal fade" data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Form User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body mb-3">
                    <form action="" method="post" id="userForm"> @csrf
                        <div class="mb-3">
                            <label class="col-form-label">Pilih Anggota</label>
                            <select id="member" name="member" class="form-control select-search" required>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Username:</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password:</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Pilih Role</label>
                            <select class="form-control" id="role" name="role" required>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-2">
                            <button type="submit" class="btn btn-secondary rounded-pill">
                                            <i class="ph-floppy-disk me-2"></i>
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
    <script src="{{ asset('assets/js/vendor/form/select2.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/tables/datatable-buttons.min.js') }}"></script>
    <script>
        $('#member').select2({
            placeholder: 'Cari NIP atau Nama',
            dropdownParent: $('#userModal'),
            ajax: {
                url: '{{ route('members.search') }}',
                type:'GET',
                dataType: 'json',
                data: function (params) {
                    return {
                        search: params.term,
                    }
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                }
            },
            cache: true,
        });

        $(function () {
            $('#user-menu').addClass('active');
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

            const userTable = $('#userTable').DataTable({
                buttons: [
                    {
                        text: 'Tambah User',
                        className: 'btn btn-teal',
                        action: function(e, dt, node, config) {
                            $('#userModal').modal('show');
                            $('#userForm').attr('action', '{{ route('users.store') }}')
                        }
                    }
                ],
                ajax: '{{ route('users.get') }}',
                columns: [
                    { data: 'id' },
                    { data: 'name' },
                    { data: 'username' },
                    { data: 'role' },
                    { data: 'id', className: 'text-center', orderable: false,
                        render: function (data) {
                            return `<button id="edit" class="btn btn-outline-warning rounded-pill"><i class="ph-pencil-simple-line me-2"></i>Edit</button>`;
                        }
                    }
                ]
            });

            $('#userForm').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'post',
                    data: $(this).serialize(),
                    success: function (res) {
                        $('#userModal').modal('hide');
                        new Noty({
                            text: res.message,
                            type: res.status == true ? 'success' : 'error'
                        }).show();
                    }
                });
            }).done(function () {
                userTable.ajax.reload();
            });

            $(document).on('click', '#edit', function () {
                let data = table.row($(this).parents('tr')).data();

            })
        });

        $(document).on('select2:open', () => {
            document.querySelector('.select2-search__field').focus();
        });
    </script>
@endprepend
