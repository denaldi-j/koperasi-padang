@extends('layouts.app')

@section('content')
    <div class="card col-lg-8 col-sm-12">
        <div class="card-header">
            <h5 class="mb-0 card-title">Form Import Data Anggota</h5>
        </div>
        <form method="post" enctype="multipart/form-data" action="{{ route('members.import') }}">
            @csrf
            <div class="card-body">
                <div class="mb-3">
                    <label class="col-form-label" for="organization_id">Organisasi</label>
                    <select class="form-control" id="organization_id" name="organization_id" required>
                        <option value="">- - -</option>
                        @foreach($organizations as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="col-form-label" for="is_asn">Pilih ASN/Non ASN</label>
                    <select class="form-control" id="is_asn" name="is_asn" required>
                        <option value="1">ASN</option>
                        <option value="0">Non ASN</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="col-form-label" for="file">Upload File</label>
                    <input type="file" class="form-control" id="file" name="file" required>
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-primary me-2" type="submit"><i class="ph-upload me-2"></i>Import File</button>
                <button class="btn btn-light" type="reset">Batal</button>
            </div>
        </form>
    </div>

@endsection

@prepend('scripts')
    <script>
        $('#importMenu').addClass('active');
        $(function () {
           $('form').submit(function (e) {
               e.preventDefault();
               $.ajax({
                   url: $(this).attr('action'),
                   type: 'POST',
                   data:  new FormData(this),
                   contentType: false,
                   processData:false,
                   cache: false,
                   success: function (res) {
                       $('form')[0].reset();
                       new Noty({
                           text: res.message,
                           type: res.status == true ? 'success' : 'error'
                       }).show();
                   },
                   error: function (response) {
                       //
                   }
               })
           })
        });
    </script>
@endprepend
