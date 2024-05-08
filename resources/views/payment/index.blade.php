@extends('layouts.app')

@section('content')
    <div class="col-lg-8 col-sm-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Form Pembayaran</h5>
            </div>
            <div class="card-body">
                <form action="" method="post" id="paymentForm"> @csrf
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
@endsection

@prepend('scripts')
    <script src="{{ asset('assets/js/vendor/form/select2.min.js') }}"></script>
    <script>

        $(document).on('select2:open', () => {
            document.querySelector('.select2-search__field').focus();
        });

        $('#member').select2({
            placeholder: 'Cari NIP atau Nama',
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
                    $('#nilaiSaldo').html(res.final_balance);
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
