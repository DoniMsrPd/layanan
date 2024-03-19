@extends('layouts/contentLayoutMaster')

@section('title', $data->title)

@section('button-right')
@if (request()->konseling)
<a href="/konseling" class="btn btn-primary">Kembali</a>
@elseif (request()->monitoring)
<a href="/monitoring" class="btn btn-primary">Kembali</a>
@else
<a href="/hasil-konseling" class="btn btn-primary">Kembali</a>
@endif
@endsection

@section('content')
<div class="row">
    <div class="col-md-12 col-12">
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
                                <td>Hubungan Keluarga Pegawai</td>
                                <td>:</td>
                                <td>
                                    {{ $data->konseling->hubunganKeluarga->Nama ?? '' }}
                                </td>
                            </tr>

                            <tr>
                                <td>Unit Organisasi</td>
                                <td>:</td>
                                <td>
                                    {{ getNmUnitOrg($data->konseling->KdUnitOrg) }} <br> {{ getNmUnitOrgInduk($data->konseling->KdUnitOrg) }}
                                </td>
                            </tr>
                            <tr>
                                <td>No HP</td>
                                <td>:</td>
                                <td>
                                    {{ $data->konseling->pegawai->NoHp ?? '' }}
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
                            @if ($data->konseling->RefTahapanId==5)

                            <tr>
                                <td>Catatan Validasi Selesai</td>
                                <td>:</td>
                                <td>
                                    {{ $data->konseling->CatatanHasilKonseling ?? '' }}
                                </td>
                            </tr>
                            @endif
                        </table>
                    </div>
                    <div class="col-md-6">

                        <table class="table b-table table-striped table-hover">
                            @if ($data->konseling->RefStatusId==2)
                            <tr>
                                <td>ND Rujukan</td>
                                <td>:</td>
                                <td>
                                    {{ $data->konseling->NDRujukan?? '' }}
                                </td>
                            </tr>
                            <tr>
                                <td>Tanggal Rujukan</td>
                                <td>:</td>
                                <td>
                                    {{ dateOutput($data->konseling->TglRujukan) }}
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <td>Jenis Konseling</td>
                                <td>:</td>
                                <td>
                                    {{ $data->konseling->jenisKonseling->Nama ?? '' }}
                                </td>
                            </tr>
                            <tr>
                                <td>Tanggal Konseling</td>
                                <td>:</td>
                                <td>
                                    {{ dateOutput($data->konseling->TglKonselingUsulan) }}
                                </td>
                            </tr>
                            <tr>
                                <td>Regional</td>
                                <td>:</td>
                                <td>
                                    {{ $data->konseling->regional->Nama ?? '' }}
                                </td>
                            </tr>
                            <tr>
                                <td>Lokasi</td>
                                <td>:</td>
                                <td>
                                    {{ $data->konseling->lokasi->Nama ?? '' }}
                                </td>
                            </tr>
                            <tr>
                                <td>Konselor</td>
                                <td>:</td>
                                <td>
                                    {{ $data->konseling->konselor->NIP ?? $data->konseling->konselor->NIK ?? '' }} {{
                                    $data->konseling->konselor->Nama ?? '' }}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="col-md-12 col-12" id="hasilKonselingContainer">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Hasil Konseling </h4>
                @if (auth()->user()->can('hasil-konseling.create')&& $data->hasilKonseling==0 && !request()->monitoring)
                <h5 class="card-header d-flex justify-content-between align-items-center">
                    <a href="#" id="addHasilKonseling" class="btn btn-primary btn-sm" data-method="POST"
                        data-url="{{ url('hasil-konseling') }}">+ Tambah</a>
                </h5>
                @endif
            </div>
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table b-table table-striped table-hover" id="table">
                        <thead>
                            <tr>
                                <th width="10%">Tanggal</th>
                                <th width="8%">Durasi</th>
                                <th width="10%">Permasalahan</th>
                                <th width="10%">Sub Masalah</th>
                                <th width="10%">Rujukan</th>
                                <th>Catatan</th>
                                <th width="5%">File</th>
                                <th width="15%">Status Rekomendasi</th>
                                <th width="5%"></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-12" id="hasilKonselingForm" style="display: none">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Form Hasil Konseling </h4>
            </div>
            <div class="card-body">
                <form id="form" action="" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row mb-3">
                                <label for="" class="col-sm-3 col-form-label">Tanggal </label>
                                <div class="col-sm-4 ">
                                    <input type="text" class="form-control  flatpickr " name="TglKonselingRealisasi"
                                        id="TglKonselingRealisasi" placeholder="TglKonselingRealisasi" value="">
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="" class="col-sm-3 col-form-label">Durasi</label>
                                <div class="col-sm-2 ">
                                    <input type="number" class="form-control " name="Jam" id="Jam" placeholder="Jam"
                                        value="">
                                </div>
                                <label for="" class="col-sm-2 col-form-label">Jam</label>
                                <div class="col-sm-2 ">
                                    <input type="number" class="form-control " name="Menit" id="Menit"
                                        placeholder="Menit" value="">
                                </div>
                                <label for="" class="col-sm-2 col-form-label">Menit</label>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="" class="col-sm-3 col-form-label">Permasalahan</label>
                                <div class="col-sm-4">
                                    <select name="RefPermasalahanId" id="RefPermasalahanId" class="form-select">
                                        <option value="">Pilih Permasalahan</option>
                                        @foreach ($data->refPermasalahan as $permasalahan)
                                        <option value="{{ $permasalahan->Id }}">{{ $permasalahan->Nama }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="" class="col-sm-3 col-form-label">Sub Masalah</label>
                                <div class="col-sm-4">
                                    <select name="RefSubMasalahId" id="RefSubMasalahId" class="form-select">
                                        <option value="">Pilih Sub Masalah</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row mb-3">
                                <label for="" class="col-sm-3 col-form-label">Rujukan</label>
                                <div class="col-sm-4">
                                    <select name="RefRujukanId" id="RefRujukanId" class="form-select">
                                        <option value="">Pilih Rujukan</option>
                                        @foreach ($data->refRujukan as $rujukan)
                                        <option value="{{ $rujukan->Id }}">{{ $rujukan->Nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="" class="col-sm-3 col-form-label">Catatan</label>
                                <div class="col-sm-8 ">
                                    <textarea name="Catatan" id="Catatan" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="form-group row mb-3 ">
                                <label for="" class="col-sm-3 col-form-label">File</label>
                                <div class="col-sm-8 ">
                                    <input type="file" readonly class="form-control " name="files[]" id="files"
                                        placeholder="files" value="" multiple>
                                    <br>
                                    <table width="100%" id="table-file">
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="" class="col-sm-3 col-form-label">Status Rekomendasi</label>
                                <div class="col-sm-4">
                                    <select name="RefStatusRekomendasiId" id="RefStatusRekomendasiId" class="form-select">
                                        <option value="">Pilih Status</option>
                                        @foreach ($data->refStatus as $status)
                                        <option value="{{ $status->Id }}">{{ $status->Nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="btn-group" role="group">
                            <input value="Simpan" id="simpan" class="btn btn-warning btn-sm btnpadding"
                                data-url="{{ url('hasil-konseling') }}">
                            <input value="Batal" id="batal" class="btn btn-danger btn-sm btnpadding">
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
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
    .btnpadding {
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
@include('hasil-konseling.script')
@endpush