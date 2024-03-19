@extends('layouts/contentLayoutMaster')

@section('title', 'Form Intake')

@section('button-right')
@if ($data->konseling->RefTahapanId==0)
    <a href="{{ route('konseling.kirim',$data->konseling->Id) }}" class="btn btn-info">Kirim</a>
@endif
@can('konseling.create')
<a href="/konseling" class="btn btn-primary">Kembali</a>
@endcan
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">

            <div class="col-md-6">
                <table class="table b-table table-striped table-hover">
                    <tr>
                        <td>Pegawai</td>
                        <td>:</td>
                        <td>
                            {{ $data->konseling->Nip ??
                            auth()->user()->NIP }} {{ $data->konseling->NmPeg ??
                            auth()->user()->pegawai->NmPeg }}
                        </td>
                    </tr>
                    <tr>
                        <td>Nomor Konseli</td>
                        <td>:</td>
                        <td>
                            {{ $data->konseling->NoKonseli ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <td>Hubungan Keluaga Pegawai</td>
                        <td>:</td>
                        <td>
                            {{ $data->konseling->hubunganKeluarga->Nama ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <td>Pelaksanaan Konseling</td>
                        <td>:</td>
                        <td>
                            {{ $data->konseling->pelaksanaan->Nama ?? '' }}
                        </td>
                    </tr>
                    @if (isset($data->konseling) && $data->konseling->RefPelaksanaanId==2)

                    <tr>
                        <td>Link Online</td>
                        <td>:</td>
                        <td>
                            {{ $data->konseling->LinkOnline ?? '' }}
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <td>Status Konseli</td>
                        <td>:</td>
                        <td>
                            {{ $data->konseling->status->Nama ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td>:</td>
                        <td>
                            {{ $data->konseling->tahapan->Nama ?? '' }}
                        </td>
                    </tr>
                </table>
            </div>
            <br>
        </div>
        <dv class="row" style="padding-top: 20px">
            <a href="{{ route('file',$data->konseling->formIntake->Id) }}">{{ $data->konseling->formIntake->NmFile }}</a>
            <div class="col-md-12 placeframe" style="height: 1000px !important">
            </div>
        </dv>
    </div>
</div>
@endsection

@section('vendor-style')
<link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/toastr.min.css')) }}">
@endsection
@section('page-style')
<link rel="stylesheet" href="{{ asset(mix('css/base/plugins/extensions/ext-component-toastr.css')) }}">

@section('vendor-script')
<script src="{{ asset(mix('vendors/js/extensions/toastr.min.js')) }}"></script>
@endsection
@push('scripts')
<script>

    $(document).ready(function () {
        // $('.fullscreen').find('a').attr('href', ulrEdit);
        var htmlVal = '<iframe src="" id="myframe" ></iframe>'
        $('.placeframe').html(htmlVal)
        $('#myframe').attr('src', "{{ url('doc/edit/'.$data->konseling->formIntake->NmFile) }}").load()
        $('#myframe').css({width: '100%', 'height': '1000px'})
    });
</script>

@endpush
@push('css')
<style>
    #myframe {
        width :100%;
        height : 1000px;
    }
    .table-responsive {
        overflow-y: hidden;
    }

    .table td {
        vertical-align: top;
        font-size: 14px;
    }
</style>
@endpush