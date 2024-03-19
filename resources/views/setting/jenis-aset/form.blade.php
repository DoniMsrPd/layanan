@extends('core.layouts.master')

@section('content')

<div class="element-wrapper">
    <div class="element-box">

        <h5 class="form-header">{{ $data->title }}<a class="btn btn-primary btn-sm" href="javascript:history.back()"
                style=" float: right;">Kembali</a></h5>
        <form method="POST" action="{{ $data->action }}" enctype="multipart/form-data">
            @csrf
            @method( $data->method )
            <input type="hidden" class="form-control" name="id" id="id" value="{{ $data->jenisAset->Id ??'' }}">
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group row">
                        <label class="col-form-label col-sm-3" for=""> Nama <sup
                                class="text-danger">*</sup></label>
                        <div class="col-sm-6"><input class=" form-control" placeholder="Nama" required=""
                                value="{{ $data->jenisAset->Nama ??'' }}" name="nama">
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
    @if(($data->jenisAset))
        $( function() {
            var tglStart = '{{ $data->jenisAset->TglStart }}';
            $('#tglStart').datepicker({ dateFormat: 'dd M yy'}).datepicker("setDate", new Date(tglStart));
            var tglEnd = '{{ $data->jenisAset->TglEnd }}';
            $('#tglEnd').datepicker({ dateFormat: 'dd M yy'}).datepicker("setDate", new Date(tglEnd));
        });
    @else

        $( function() {
            $('.datepicker').datepicker({ dateFormat: 'dd M yy'}).datepicker("setDate", new Date());
        });
    @endif
</script>
@endpush