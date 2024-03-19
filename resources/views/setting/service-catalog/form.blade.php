@extends('layouts/contentLayoutMaster')

@section('content')
<div class="card">
    <h5 class="card-header">{{ $data->title }}<a class="btn btn-primary btn-sm" href="javascript:history.back()"
            style=" float: right;">Kembali</a></h5>
    <div class="card-body">
        <form id="form" method="POST" action="{{ $data->action }}" enctype="multipart/form-data">
            @csrf
            @method( $data->method )
            <input type="hidden" name="KdUnitOrgOwnerLayanan" value="{{ kdUnitOrgOwner() }}">
            <input type="hidden" class="form-control" name="id" id="id" value="{{ $data->catalog->Id ??'' }}">
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group row">
                        <label class="col-form-label col-sm-3" for=""> Kode ITSM <sup
                                class="text-danger">*</sup></label>
                        <div class=" col-sm-3"><input class=" form-control" placeholder="Kode ITSM" required=""
                                value="{{ $data->catalog->Kode ??'' }}" name="kode">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-sm-3" for=""> Nama ITSM <sup
                                class="text-danger">*</sup></label>
                        <div class="col-sm-6"><input class=" form-control" placeholder="Nama ITSM" required=""
                                value="{{ $data->catalog->Nama ??'' }}" name="nama">
                        </div>
                    </div>
                    @if (substr($data->KdUnitOrgLayanan, 0, 6) == '100205')
                    <div class="form-group row">
                        <label class="col-form-label col-sm-3" for=""></label>
                        <div class="col-sm-6">
                            <input type="checkbox" name="isPeminjaman" value="1" @if(($data->catalog)
                            &&$data->catalog->IsPeminjaman==1 ) checked @endif> Peminjaman <br>
                            <input type="checkbox" name="isPersediaan" value="1" @if(($data->catalog)
                            &&$data->catalog->IsPersediaan==1 ) checked @endif> Persediaan <br>
                            <input type="checkbox" name="isPerbaikan" value="1" @if(($data->catalog)
                            &&$data->catalog->IsPerbaikan==1 ) checked @endif> Perbaikan <br>
                        </div>
                    </div>
                    @endif
                    <div class="form-group row ">
                        <label class="col-form-label col-sm-3" for=""> Periode: <sup class="text-danger">*</sup></label>
                        <div style="width:20%;padding-left:10px">
                            <div class="date-input"><input class="datepicker form-control" placeholder="Tanggal Mulai"
                                    value="" name="tglStart" id="tglStart"></div>
                        </div>
                        <label class="text-center col-form-label col-sm-1" for=""> S.D </label>
                        <div style="width:20%;padding-left:10px">
                            <div class="date-input"><input class="datepicker form-control" placeholder="Tanggal Akhir"
                                    value="" name="tglEnd" id="tglEnd"></div>
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
    @if(($data->catalog))
        var tglStart = '{{ $data->catalog->TglStart }}';
        $('#tglStart').flatpickr({
            dateFormat: 'd M Y',
            defaultDate: new Date(tglStart ? tglStart: null)
        });
        var tglEnd = '{{ $data->catalog->TglEnd }}';
        $('#tglEnd').flatpickr({
            dateFormat: 'd M Y',
            defaultDate: new Date(tglEnd ? tglEnd: null)
        });
    @else
        $('#tglStart').flatpickr({
            dateFormat: 'd M Y',
            defaultDate: new Date()
        });
        $('#tglEnd').flatpickr({
            dateFormat: 'd M Y',
            defaultDate: new Date()
        });
    @endif

    $(document).ready(function(){
        $('#form').on('submit', function(e){
            e.preventDefault();
            var element = this;
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '{{ route('setting.service-catalog.check') }}',
                type: "POST",
                data: $("#form").find("input[name!=_method]").serialize(),
                beforeSend : function (data) {
                    // addLoading()
                },
                success: function (response) {
                    console.log(element);
                    if(response.success){
                        element.submit();
                    } else {

                        swal({
                            // title: "",
                            text: 'Service Catalog Sudah Ada',
                            type: "warning",
                            timer: 10000,
                        });
                        // removeLoading()
                    }
                },
                error: function (error) {
                    alert('error; ' + eval(error));
                    // removeLoading()
                }
            });
        });
    });
</script>
@endpush
