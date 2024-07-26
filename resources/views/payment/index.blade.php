@extends('layouts.app')

@section('content')
    <style>
        #reader__dashboard_section_swaplink { display: none !important; }
        #reader__dashboard_section_csr >span > button {
            display: inline-block;
            font-weight: 400;
            border-radius: 10px !important;
            color: #212529;
            background-color: #ffc107;
            border-color: #ffc107;
        }
        #reader > img { display: none; }

        #reader {
            width: 400px;
        }
    </style>
    <div class="col-lg-8 col-sm-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex flex-row mb-0">
                    <h5 class="card-title mb-0">Form Pembayaran</h5>
{{--                    <div class="ms-auto">--}}
{{--                        <button class="btn btn-primary" type="button" id="scanButton">Scan Card</button>--}}
{{--                    </div>--}}
                </div>
            </div>
            <div class="card-body">
                <form action="" method="post" id="paymentForm"> @csrf
                    <div class="mb-3">
                        <label class="col-form-label">Pilih OPD</label>
                        <select id="organization" name="organization" class="form-control select2" required>
                            <option value="">- - -</option>
                            @foreach($organizations as $org)
                                <option value="{{ $org->id }}">{{ $org->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">Pilih Anggota</label>
                        <select id="member" name="member" class="form-control select-search" required>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold h4">Saldo: <span id="nilaiSaldo"></span></label>
                        <input type="number" class="form-control" id="balance" name="balance" hidden required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Total Belanja:</label>
                        <input type="number" class="form-control" id="amount" name="amount" required>
                    </div>

                    <div class="mb-3" hidden="">
                        <label class="form-label">Jumlah Bayar (Diskon 1%):</label>
                        <input type="number" class="form-control" id="discount" name="discount" hidden value="0">
                        <input type="number" class="form-control" id="total" name="total" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Sisa Bayar dalam cash:</label>
                        <input type="number" class="form-control" id="cash" name="cash" min="0" value="0">
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

    <!-- Scan modal -->
    <div id="scanModal" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Scan...</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div id="reader" class="mb-3 mx-auto"></div>
                </div>

                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /scan modal -->
@endsection

@prepend('scripts')
    <script src="{{ asset('assets/js/vendor/form/select2.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/jquery.validate.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/plugins/html5-qrcode.min.js') }}" type="text/javascript"></script>
    <script>
        $(document).ready(function() {
            $('#reader').find('img').first().remove();
            $('#reader__camera_permission_button').addClass('btn btn-warning');
            $('#reader').removeAttr('style');
        });

        const html5QrCode = new Html5Qrcode("reader");

        $('#scanButton').click(function () {
            $('#scanModal').modal('show');
            scan();
        });

        $('#organization').select2();

        function scan() {
            html5QrCode.start({ facingMode: "environment"}, { fps: 10, qrbox: 200 },
                onScanSuccess,
                onScanError)
                .catch(err => {
                    // Start failed, handle it. For example,
                    console.log(`Unable to start scanning, error: ${err}`);
                });
        }

        function onScanError(errorMessage) {
            // handle on error condition, with error message
        }

        function onScanSuccess(decodeText, decodeResult) {
            if(decodeText) {
                html5QrCode.stop();
                $('#loader').removeAttr('hidden');
                $.ajax({
                    url: decodeText,
                    type: 'post',
                    data: {
                        _token: '{{ csrf_token() }}',
                        email: $('#email').val()
                    },
                    success: function(response) {
                        notify(response)
                    },
                    error: function(response) {
                        console.log(response);
                        $('#loader').attr('hidden', true);
                    }
                }).done(function() {
                    $('#loader').attr('hidden', true);
                });
            }
        }

        $(document).on('select2:open', () => {
            document.querySelector('.select2-search__field').focus();
        });

        $('#member').select2({
            placeholder: 'Cari Nomor Anggota atau Nama',
            ajax: {
                url: '{{ route('members.search') }}',
                type:'GET',
                dataType: 'json',
                data: function (params) {
                    return {
                        search: params.term,
                        organization_id: $('#organization').val()
                    }
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                }
            },
        });

        $('#paymentForm').submit(function (e) {
            e.preventDefault();
            $.ajax({
                url: '{{ route('payments.store') }}',
                type: 'post',
                data: $(this).serialize(),
                success: function (res) {
                    if(res.status === 'success') {
                        Swal.fire({
                            title: "Berhasil!",
                            text: "Pembayaran Selesai!",
                            icon: "success"
                        }).then(function () {
                            $('#paymentForm')[0].reset();
                        });
                    } else {
                        Swal.fire({
                            title: "Gagal!",
                            text: "Gagal menyimpan pembayaran, coba lagi!",
                            icon: "error"
                        });
                    }
                }
            })
        })

        $('#member').change(function () {
            $.ajax({
                url: '{{ url('balance/get-by-member') }}/'+$(this).val(),
                type: 'get',
                success: function (res) {
                    console.log(res)
                    $('#nilaiSaldo').html(res.final_balance.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1."));
                    $('#balance').val(res.final_balance);
                }

            });
        });

        $('#amount').bind('keyup mouseup', function () {
            let amount = $(this).val();
            let balance = $('#balance').val();
            let pay = balance - amount;
            if(amount != 0) {
                // TODO: set discount 0, discount by deposit
                let discount = 0;
                let total = amount - discount;
                $('#discount').val(discount)
                $('#total').val(total)

                if(total > balance) {
                    $('#cash').val(total - balance);

                } else {
                    $('#cash').val(0)
                }
            } else {
                $('#discount').val(0)
                $('#total').val(0)
                $('#cash').val(0)
            }
        });
    </script>
@endprepend
