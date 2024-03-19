@extends('layouts/contentLayoutMaster')

@section('title', $data->title)

@section('button-right')
<a href="/master/jadwal-konseling" class="btn btn-primary">Kembali</a>
@endsection

@section('content')

<div class="row">
    <div class="col-md-12 col-12">
        <div class="card">
            <div class="card-body">


                <form id="form" action="{{ $data->action }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method($data->method)
                    <div class="row">
                        <div class="form-group row mb-2">
                            <label  for="" class="col-sm-1 col-form-label">Konselor <sup class="text-danger">*</sup></label>
                            <div class="col-sm-3">
                                <div class="input-group input-group-merge">
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#KonselorModal"
                                     title="Pilih Konselor" id="btn-konselor"><i data-feather='user'></i></button>
                                    <span id="Konselor" style="padding-left: 10px">{{ $data->jadwalKonseling->konselor->NIP ?? $data->jadwalKonseling->konselor->NIK ?? '' }}  {{ $data->jadwalKonseling->konselor->Nama ?? '' }}</span>
                                        <input type="text" required style="opacity: 0"  name="MstKonselorId" id="MstKonselorId" value="{{ $data->jadwalKonseling->MstKonselorId ?? '' }}">
                                </div>
                            </div>
                            <label for="" class="col-sm-1 col-form-label">Regional Konseling <sup class="text-danger">*</sup></label>
                            <label for="" class="col-sm-3 col-form-label " id="RegionalKonseling">{{ $data->jadwalKonseling->regional->Nama ?? '' }}</label>
                            <label for="" class="col-sm-1 col-form-label">Lokasi Konseling <sup class="text-danger">*</sup></label>
                            <label for="" class="col-sm-3 col-form-label " id="LokasiKonseling">{{ $data->jadwalKonseling->lokasi->Nama ?? '' }}</label>
                        </div>
                        <div class="form-group row mb-2">
                            <label for="" class="col-sm-1 col-form-label">Tanggal</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control {{ $data->method=='POST'? 'flatpickr-multi':'flatpickr' }}" id="Tanggal" name="Tanggal" required
                                    placeholder="Tanggal"
                                    value="{{ isset($data->jadwalKonseling) ? dateOutput($data->jadwalKonseling->Tanggal) : null }}">
                            </div>
                            <label for="" class="col-sm-1 col-form-label">Jam Mulai</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control flatpickr-time text-start flatpickr-input active" style="height: 100px;" id="JamMulai" name="JamMulai" required
                                    placeholder="JamMulai"
                                    value="{{ isset($data->jadwalKonseling) ? $data->jadwalKonseling->JamMulai : null }}">
                            </div>
                            <label for="" class="col-sm-1 col-form-label">Jam Selesai</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control flatpickr-time text-start flatpickr-input active" style="height: 100px;" id="JamSelesai" name="JamSelesai" required
                                    placeholder="JamSelesai"
                                    value="{{ isset($data->jadwalKonseling) ? $data->jadwalKonseling->JamSelesai : null }}">
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="btn-group" role="group">
                            <input type="submit" value="Simpan" name="submit" class="btn btn-warning">
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@include('modal.konselor')
@endsection
@section('vendor-style')
<link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/wizard/bs-stepper.min.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/toastr.min.css')) }}">
@endsection
@section('page-style')
<link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-wizard.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('css/base/plugins/extensions/ext-component-toastr.css')) }}">
<style>
    .ajukan {
        margin-left: 10px !important
    }
</style>
@endsection

@section('vendor-script')
<script src="{{ asset(mix('vendors/js/forms/wizard/bs-stepper.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/extensions/toastr.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/blockui/blockui.min.js')) }}"></script>
@endsection
@section('page-script')
<script src="{{ asset(mix('js/scripts/forms/form-wizard.js')) }}"></script>
@endsection
@push('scripts')
@include('system.layouts._script-delete')
@include('system.layouts._delete')
@include('master.jadwal-konseling.script')
@endpush