@extends('layouts/contentLayoutMaster')
@section('content')

<div class="card">
    <h5 class="card-header">{{ $data->title }}<a class="btn btn-primary btn-sm" href="javascript:history.back()"
            style=" float: right;">Kembali</a></h5>
    <div class="card-body">
        <form method="POST" action="{{ $data->action }}" enctype="multipart/form-data">
            @csrf
            @method( $data->method )
            <input type="hidden" name="id" id="id" value="{{ $data->templatePenyelesaian->Id ??''}}">
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group row">
                        <label class="col-form-label col-sm-3" for=""> Nama <sup class="text-danger">*</sup></label>
                        <div class=" col-sm-8">
                            <div class="input-group">
                                <input type="text" name="Nama" id="Nama" required class="form-control"
                                    value="{{ $data->templatePenyelesaian->Nama ??'' }}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-sm-3" for=""> Template <sup class="text-danger">*</sup></label>
                        <div class="col-sm-8">
                            <textarea class="form-control ckeditor" rows="3" name="Template"
                                required>{{ $data->templatePenyelesaian->Template ??'' }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-left form-buttons-w"><button class="btn btn-primary" type="submit">
                    Submit</button>
            </div>
        </form>
    </div>
</div>
@endsection
@include('core.modals.unit_org')

@push('scripts')
<script>
    $(document).on("click",'.pilih',function () {
    kd_unit_org = $(this).data('kd_unit_org')
    nm_unit_org = $(this).data('nm_unit_org')
    $('#Nama').val(nm_unit_org)
    $('#Id').val(kd_unit_org)
    $('#unit-org-modal').modal('toggle');
});
</script>
@endpush
@section('vendor-script')
<script src="{{ asset(mix('vendors/js/extensions/toastr.min.js')) }}"></script>
<script src="{{ asset('vendors/js/editors/ckeditor/ckeditor.js') }}"></script>
@endsection