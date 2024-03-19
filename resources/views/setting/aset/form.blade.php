@extends('core.layouts.master')

@section('content')
<style>
    .ui-datepicker-calendar {
        display: none;
    }
</style>
<div class="element-wrapper">
    <div class="element-box">

        <h5 class="form-header">{{ $data->title }}<a class="btn btn-primary btn-sm" href="javascript:history.back()"
                style=" float: right;">Kembali</a></h5>
        <form method="POST" action="{{ $data->action }}" enctype="multipart/form-data">
            @csrf
            @method( $data->method )

            @include('setting::aset._form')
            <div class="text-left form-buttons-w"><button class="btn btn-primary" type="submit">
                    Submit</button>
            </div>
        </form>
    </div>
</div>
@endsection

@include('core.modals.pegawai9')
@push('scripts')
@include('setting::aset._script')
@endpush