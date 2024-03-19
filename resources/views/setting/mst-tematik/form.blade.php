@extends('core.layouts.master')

@section('content')

<div class="element-wrapper">
    <div class="element-box">

        <h5 class="form-header">{{ $data->title }}<a class="btn btn-primary btn-sm" href="javascript:history.back()"
                style=" float: right;">Kembali</a></h5>
        <form method="POST" action="{{ $data->action }}" enctype="multipart/form-data">
            @csrf
            @method( $data->method )
            <input type="hidden" class="form-control" name="id" id="id" value="{{ $data->mstTematik->Id ??'' }}">
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group row">
                        <label class="col-form-label col-sm-3" for=""> Judul Tematik <sup
                                class="text-danger">*</sup></label>
                        <div class=" col-sm-6"><input class=" form-control" placeholder="Judul Tematik" required=""
                                value="{{ $data->mstTematik->Tema ??'' }}" name="tema">
                        </div>
                    </div>
                    <div class="form-group row"><label class="col-sm-3 col-form-label">Deskripsi <sup
                        class="text-danger">*</sup></label>
                        <div class="col-sm-6"><textarea required class="form-control" rows="3" name="keterangan">{{ $data->mstTematik->Keterangan ??'' }}</textarea></div>
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
    @if(($data->mstTematik))
        $( function() {
            var tglStart = '{{ $data->mstTematik->TglStart }}';
            $('#tglStart').datepicker({ dateFormat: 'dd M yy'}).datepicker("setDate", new Date(tglStart));
            var tglEnd = '{{ $data->mstTematik->TglEnd }}';
            $('#tglEnd').datepicker({ dateFormat: 'dd M yy'}).datepicker("setDate", new Date(tglEnd));
        });
    @else

        $( function() {
            $('.datepicker').datepicker({ dateFormat: 'dd M yy'}).datepicker("setDate", new Date());
        });
    @endif
</script>
@endpush