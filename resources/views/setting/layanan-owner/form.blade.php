@extends('layouts/contentLayoutMaster')

@section('content')
<div class="card">
    <h5 class="card-header">{{ $data->title }}<a class="btn btn-primary btn-sm" href="javascript:history.back()"
            style=" float: right;">Kembali</a></h5>
    <div class="card-body">
        <form id="form" method="POST" action="{{ $data->action }}" enctype="multipart/form-data">
            @csrf
            @method( $data->method )
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group row">
                        <label class="col-form-label col-sm-4" for=""> Kode Unit Org Owner <sup
                                class="text-danger">*</sup></label>
                        <div class=" col-sm-6"><input class=" form-control" placeholder="Kode Unit Org Owner" required=""
                                value="{{ $data->layananOwner->KdUnitOrgOwnerLayanan ??'' }}" name="KdUnitOrgOwnerLayanan" {{ $data->method=='PATCH' ? 'readonly' :'' }}>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-sm-4" for=""> Nama Unit Org Owner <sup
                                class="text-danger">*</sup></label>
                        <div class="col-sm-6"><input class=" form-control" placeholder="Nama Unit Org Owner" required=""
                                value="{{ $data->layananOwner->NmUnitOrgOwnerLayanan ??'' }}" name="NmUnitOrgOwnerLayanan">
                        </div>
                    </div>
                    <div class="form-group row ">
                        <label class="col-sm-4" for=""> Icon</label>
                        <div class="col-sm-6">
                            <div class="date-input"><input type="file" class="form-control" placeholder="Tanggal End"
                                    value="" name="Icon" id="Icon"></div>
                        </div>
                    </div>
                    <div class="form-group row ">
                        <label class="col-sm-4" for=""> Tanggal End </label>
                        <div style="width:20%;padding-left:10px">
                            <div class="date-input"><input class="datepicker form-control" placeholder="Tanggal End"
                                    value="" name="TglEnd" id="TglEnd"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-left form-buttons-w"><button class="btn btn-primary submit-button">
                    Submit</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>


@if(($data->layananOwner))
        var TglEnd = '{{ $data->layananOwner->Tglend }}';
        $('#TglEnd').flatpickr({
            dateFormat: 'd M Y',
            defaultDate: new Date(TglEnd ? TglEnd: null)
        });
    @else
        $('#TglEnd').flatpickr({
            dateFormat: 'd M Y',
            defaultDate: new Date()
        });
    @endif
    // $(document).ready(function(){
    //     $('#form').on('submit', function(e){
    //         e.preventDefault();
    //         var element = this;
    //         var csrf_token = $('meta[name="csrf-token"]').attr('content');
    //         $.ajax({
    //             url: '{{ route('setting.layanan-owner.check') }}',
    //             type: "POST",
    //             data: $("#form").find("input[name!=_method]").serialize(),
    //             beforeSend : function (data) {
    //                 // addLoading()
    //             },
    //             success: function (response) {
    //                 console.log(element);
    //                 if(response.success){
    //                     element.submit();
    //                 } else {

    //                     swal({
    //                         // title: "",
    //                         text: 'Service Catalog Sudah Ada',
    //                         type: "warning",
    //                         timer: 10000,
    //                     });
    //                     // removeLoading()
    //                 }
    //             },
    //             error: function (error) {
    //                 alert('error; ' + eval(error));
    //                 // removeLoading()
    //             }
    //         });
    //     });
    // });
</script>
@endpush
