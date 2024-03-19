@extends('layouts/contentLayoutMaster')

@section('content')

<div class="card">
    <h5 class="card-header" {{ $data->title }} <a class="btn btn-primary btn-sm" href="javascript:history.back()"
            style=" float: right;">Kembali</a></h5>
    <div class="card-body">
        <form method="POST" action="{{ $data->action }}" enctype="multipart/form-data">
            @csrf
            @method( $data->method )
            <input type="hidden" name="KdUnitOrgOwnerLayanan" value="{{ kdUnitOrgOwner() }}">
            <input type="hidden" class="form-control" name="id" id="id" value="{{ $data->kategori->Id ??'' }}">
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group row">
                        <label class="col-form-label col-sm-3" for=""> Nama <sup class="text-danger">*</sup></label>
                        <div class=" col-sm-6"><input class=" form-control" placeholder="Nama" required=""
                                value="{{ $data->kategori->Nama ??'' }}" name="Nama">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-sm-3" for=""> Keterangan <sup
                                class="text-danger">*</sup></label>
                        <div class=" col-sm-6">
                            <textarea name="Keterangan" class="form-control" cols="30"
                                rows="2">{{ $data->kategori->Keterangan ??'' }}</textarea>
                        </div>
                    </div>
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
            <div class="text-left form-buttons-w"><button class="btn btn-primary" type="submit">
                    Submit</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>

    @if(($data->kategori))
            var tglStart = '{{ $data->kategori->TglStart }}';
            $('#tglStart').flatpickr({
                dateFormat: 'd M Y',
                defaultDate: new Date(tglStart ? tglStart: null)
            });
            var tglEnd = '{{ $data->kategori->TglEnd }}';
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
</script>
@endpush