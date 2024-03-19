@extends('layouts/contentLayoutMaster')

@section('title', $data->title)

@section('button-right')
<a href="/konseling" class="btn btn-primary">Kembali</a>
@endsection

@section('content')

<div class="row">
    <div class="col-md-12 col-12">
        <div class="card">
            <div class="card-body">

                @php
                    $isStatusSelesai = ($data->method=='PUT'&& $data->konseling->RefTahapanId==4) || ($data->method=='POST' && request()->IdLama);
                @endphp
                <form id="form" action="{{ $data->action }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method($data->method)
                    @if ($isStatusSelesai)
                        <input type="hidden" name="RefJenisKonselingId" value="{{ $data->konseling->RefJenisKonselingId??null }}">
                        <input type="hidden" name="RefHubunganKeluargaId" value="{{ $data->konseling->RefHubunganKeluargaId??null }}">
                        <input type="hidden" name="NmPeg" value="{{ $data->konseling->NmPeg??null }}">
                        <input type="hidden" name="KdUnitOrg" value="{{ $data->konseling->KdUnitOrg??null }}">
                        <input type="hidden" name="RefPelaksanaanId" value="{{ $data->konseling->RefPelaksanaanId??null }}">
                        <input type="hidden" name="RefStatusId" value="{{ $data->konseling->RefStatusId??null }}">
                        <input type="hidden" name="RefRujukanId" value="{{ $data->konseling->RefRujukanId??null }}">
                        <input type="hidden" name="TglRujukan" value="{{ $data->konseling->TglRujukan? dateOutput($data->konseling->TglRujukan) :null }}">
                    @endif
                    <input type="hidden" name="IdParent" value="{{ request()->IdLama ?? $data->konseling->IdParent ?? '' }}">
                    <div class="row">
                        <div class="form-group row mb-3">
                            <label for="" class="col-sm-2 col-form-label">Pegawai</label>
                            <div class="col-sm-3">
                                <div class="input-group input-group-merge">
                                    @if (auth()->user()->can('konseling.create-all') && !$isStatusSelesai)
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#PegawaiModal" title="Pilih Pegawai" id="btn-pegawai"><i
                                            data-feather='user'></i></button>
                                    @endif
                                    <span id="Pegawai" style="padding-left: 10px">{{ $data->konseling->Nip ??
                                        auth()->user()->NIP }} {{ $data->konseling->pegawai->NmPeg ??
                                        auth()->user()->pegawai->NmPeg }}</span>
                                        <input type="hidden" id="NmPegawai" value="{{ $data->konseling->pegawai->NmPeg ?? auth()->user()->pegawai->NmPeg }}">
                                    <input type="hidden" name="Nip" id="Nip"
                                        value="{{ $data->konseling->Nip ?? auth()->user()->NIP }}">
                                    <input type="hidden" name="KdUnitOrg" id="KdUnitOrg"
                                        value="{{ $data->konseling->KdUnitOrg ?? auth()->user()->pegawai->KdUnitOrg }}">
                                </div>
                            </div>
                            <label for="" class="col-sm-2 col-form-label">Jenis Konseling <sup class="text-danger">*</sup></label>
                            <div class="col-sm-3">
                                <select required {{ $isStatusSelesai? 'disabled':'' }} name="RefJenisKonselingId" id="RefJenisKonselingId" class="form-select">
                                    <option value="">Pilih Jenis Konseling</option>
                                    @foreach ($data->refJenisKonseling as $jk)
                                    <option {{ isset($data->konseling) && $jk->Id==$data->konseling->RefJenisKonselingId
                                        ?
                                        'selected':'' }} value="{{ $jk->Id }}">{{ $jk->Nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="" class="col-sm-2 col-form-label">Nomor Konseli</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" name="NoKonseli" id="NoKonseli"
                                    placeholder="NoKonseli" value="{{ $data->noKonseli?? null }}" readonly>
                            </div>
                            <label for="" class="col-sm-2 col-form-label">Tanggal Konseling <sup class="text-danger">*</sup></label>
                            <div class="col-sm-3">
                                <div class="input-group input-group-merge">
                                    @if (!$isStatusSelesai || request()->IdLama)
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#JadwalKonselingModal" title="Pilih Jadwal" id="btn-jadwal-konseling"><i
                                            data-feather='calendar'></i></button>
                                    @endif
                                        <span style="padding-left: 10px" id="TglKonselingUsulan2"  >{{ isset($data->konseling) ? dateOutput($data->konseling->TglKonselingUsulan) : null }}</span>
                                        <input required  style="opacity: 0" type="text" class="form-control" name="TglKonselingUsulan" id="TglKonselingUsulan" value="{{ isset($data->konseling) ? dateOutput($data->konseling->TglKonselingUsulan) : null }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label for="" class="col-sm-2 col-form-label">Hubungan Keluarga Pegawai  <sup class="text-danger">*</sup></label>
                            <div class="col-sm-3">
                                <select required {{ $isStatusSelesai? 'disabled':'' }} name="RefHubunganKeluargaId" id="RefHubunganKeluargaId" class="form-select">
                                    <option value="">Pilih Hubungan Keluarga</option>
                                    @foreach ($data->refHubunganKeluarga as $hk)
                                    <option {{ (isset($data->konseling) &&
                                        $hk->Id==$data->konseling->RefHubunganKeluargaId) || (!isset($data->konseling)&& $hk->Id=='0') ?
                                        'selected':'' }} value="{{ $hk->Id }}">{{ $hk->Nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <label for="" class="col-sm-2 col-form-label">Regional Konseling</label>
                            <label for="" class="col-sm-2 col-form-label" id="RegionalKonseling">{{ $data->konseling->regional->Nama ?? '' }}</label>
                            <input type="hidden" value="{{ $data->konseling->RefRegionalId ?? '' }}" name="RefRegionalId" id="RefRegionalId">
                            <input type="hidden" value="{{ $data->konseling->JadwalKonselingId ?? '' }}" name="JadwalKonselingId" id="JadwalKonselingId">
                        </div>
                        <div class="form-group row mb-3 NmPeg" @if($data->method=='POST' || $data->method=='PUT' && $data->konseling->RefHubunganKeluargaId==0) style="display: none" @endif>
                            <label for="" class="col-sm-2 col-form-label">Nama</label>
                            <div class="col-sm-3">
                                {{-- <input type="text" class="form-control" name="NmPeg" id="NmPeg"
                                    placeholder="Nama" value="{{ $data->konseling->NmPeg ?? auth()->user()->pegawai->NmPeg }}"> --}}
                                <select {{ $isStatusSelesai? 'disabled':'' }} name="NmPeg" id="NmPeg" class="form-control" >
                                    @if ($data->method=='PUT' && $data->konseling &&  $data->hubungan)
                                        @foreach ($data->hubungan as $rk)
                                            <option {{ $rk->NAMA==$data->konseling->NmPeg }} value="{{ $rk->NAMA }}">{{ $rk->NAMA }}</option>
                                        @endforeach
                                    @elseif ($data->method=='PUT' && $data->konseling->RefHubunganKeluargaId==0)
                                    <option selected="" value="{{ $data->konseling->NmPeg }}">{{ $data->konseling->NmPeg }}</option>

                                    @else
                                    <option selected="" value="{{ $data->konseling->NmPeg ?? auth()->user()->pegawai->NmPeg }}">{{ $data->konseling->NmPeg ?? auth()->user()->pegawai->NmPeg }}</option>

                                    @endif
                                </select>
                            </div>

                        </div>
                        <div class="form-group row mb-3">
                            <label for="" class="col-sm-2 col-form-label">Pelaksanaan Konseling<sup class="text-danger">*</sup></label>
                            <div class="col-sm-3">
                                @foreach ($data->refPelaksanaan as $rp)
                                <div class="form-check form-check-inline">
                                    <input  required {{ $isStatusSelesai? 'disabled':'' }} class="form-check-input" type="radio" name="RefPelaksanaanId"
                                        id="RefPelaksanaanId" value="{{ $rp->Id }}" {{ isset($data->konseling) &&
                                    $rp->Id==$data->konseling->RefPelaksanaanId ?
                                    'checked=""':'' }}>
                                    <label class="form-check-label" for="inlineRadio1">{{ $rp->Nama }}</label>
                                </div>
                                @endforeach
                            </div>
                            <label for="" class="col-sm-2 col-form-label">Lokasi Konseling</label>
                            <label for="" class="col-sm-2 col-form-label" id="LokasiKonseling">{{ $data->konseling->lokasi->Nama ?? '' }}</label>
                            <input type="hidden" value="{{ $data->konseling->RefLokasiId ?? '' }}" name="RefLokasiId" id="RefLokasiId">
                        </div>
                        @php
                        $linkOnline = isset($data->konseling) && $data->konseling->RefPelaksanaanId==2;
                        @endphp

                        <div class="form-group row mb-3 LinkOnline" @if (!$linkOnline) style="display: none" @endif>
                            <label for="" class="col-sm-2 col-form-label">Link Online <sup class="text-danger">*</sup></label>
                            <div class="col-sm-3 ">
                                <input {{ $isStatusSelesai? 'readonly':'' }} type="text" class="form-control " name="LinkOnline" id="LinkOnline"
                                    placeholder="LinkOnline" value="{{ $data->konseling->LinkOnline ?? null }}">
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label for="" class="col-sm-2 col-form-label">Status Konseli</label>
                            <div class="col-sm-3">
                                <select {{ $isStatusSelesai? 'disabled':'' }} name="RefStatusId" id="RefStatusId" class="form-select">
                                    <option value="">Pilih Status Konseli</option>
                                    @foreach ($data->refStatus as $status)
                                    <option {{ isset($data->konseling) && $status->Id==$data->konseling->RefStatusId ?
                                        'selected':'' }}

                                        {{ auth()->user()->roles->count() == 1 && $data->method=='POST' && $status->Id==1 ?'selected':''}}
                                        value="{{ $status->Id }}">{{ $status->Nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <label for="" class="col-sm-2 col-form-label">Konselor</label>
                            <label for="" class="col-sm-2 col-form-label" id="Konselor">{{ $data->konseling->konselor->NIP ?? $data->konseling->konselor->NIK  ?? '' }} {{ $data->konseling->konselor->Nama ?? '' }}</label>
                            <input type="hidden" value="{{ $data->konseling->MstKonselorId ?? '' }}" name="MstKonselorId" id="MstKonselorId">
                        </div>
                        @php
                        $rujukan = isset($data->konseling) && $data->konseling->RefStatusId==2;
                        @endphp
                        <div class="form-group row mb-3 rujukan"  @if (!$rujukan) style="display: none" @endif>
                            <label for="" class="col-sm-2 col-form-label">Rujukan</label>
                            <div class="col-sm-3">
                                <select {{ $isStatusSelesai? 'disabled':'' }}  name="RefRujukanId" id="RefRujukanId" class="form-select">
                                    <option value="">Pilih Rujukan</option>
                                    @foreach ($data->refRujukan as $rRujukan)
                                    <option {{ isset($data->konseling) && $rRujukan->Id==$data->konseling->RefRujukanId ?
                                        'selected':'' }} value="{{ $rRujukan->Id }}">{{ $rRujukan->Nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @php
                        $konfirmasiPerubahan = isset($data->konseling) && $data->konseling->RefTahapanId==11;
                        $konfirmasiPenolakan = isset($data->konseling) && $data->konseling->RefTahapanId==10;
                        @endphp
                        @if ($data->isVerifikasi)

                            <div class="form-group row mb-3 konfirmasi-perubahan" @if (!$konfirmasiPerubahan) style="display: none"  @endif >
                                <label for="" class="col-sm-2 col-form-label ">Konfirmasi Perubahan</label>
                                <div class="col-sm-3 ">
                                    <textarea name="AlasanPerubahan" class="form-control">{{ $data->konseling->AlasanPerubahan ?? '' }}</textarea>
                                </div>
                            </div>
                            <div class="form-group row mb-3 konfirmasi-penolakan" @if (!$konfirmasiPenolakan) style="display: none" @endif>
                                <label  for="" class="col-sm-2 col-form-label ">Konfirmasi Penolakan</label>
                                <div class="col-sm-3">
                                    <textarea name="AlasanPenolakan" class="form-control">{{ $data->konseling->AlasanPenolakan ?? '' }}</textarea>
                                </div>
                            </div>
                            @elseif ($konfirmasiPerubahan)

                            <div class="form-group row mb-3">
                                <label for="" class="col-sm-2 col-form-label">Alasan Perubahan</label>
                                <div class="col-sm-3">
                                    {{ $data->konseling->AlasanPerubahan ?? '' }}
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="" class="col-sm-2 col-form-label">Status</label>
                                <div class="col-sm-3">
                                    <select name="RefTahapanId" id="RefTahapanId" class="form-select">
                                        @foreach ($data->refTahapan->whereIn('Id',[12,13]) as $tahapan)
                                        <option {{ isset($data->konseling) && ($tahapan->Id==$data->konseling->RefTahapanId) ?
                                            'selected':'' }} value="{{ $tahapan->Id }}">{{ $tahapan->Nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label  for="" class="col-sm-2 col-form-label konfirmasi-pembatalan">Konfirmasi Pembatalan</label>
                                <div class="col-sm-3 konfirmasi-pembatalan">
                                    <textarea name="AlasanPembatalan" class="form-control">{{ $data->konseling->AlasanPembatalan ?? '' }}</textarea>
                                </div>
                            </div>
                        @endif
                        @if (auth()->user()->can('konseling.verifikasi') || $rujukan)

                            <div class="form-group row mb-3 rujukan" @if (!$rujukan) style="display: none" @endif>
                                <label for="" class="col-sm-2 col-form-label">ND Rujukan <sup class="text-danger">*</sup></label>
                                <div class="col-sm-3 ">
                                    <input {{ $isStatusSelesai? 'readonly':'' }} type="text" @if (!auth()->user()->can('konseling.verifikasi')) readonly  @endif class="form-control " name="NDRujukan" id="NDRujukan"
                                        placeholder="NDRujukan" value="{{ $data->konseling->NDRujukan ?? null }}">
                                </div>
                            </div>

                            <div class="form-group row mb-3 rujukan" @if (!$rujukan) style="display: none" @endif>
                                <label for="" class="col-sm-2 col-form-label">Tanggal Rujukan <sup class="text-danger">*</sup></label>
                                <div class="col-sm-3 ">
                                    <input {{ $isStatusSelesai? 'disabled':'' }} type="text" @if (!auth()->user()->can('konseling.verifikasi')) readonly  @endif class="form-control @if (auth()->user()->can('konseling.verifikasi')) flatpickr  @endif" name="TglRujukan" id="TglRujukan"
                                        placeholder="TglRujukan" value="{{ isset($data->konseling) ? dateOutput($data->konseling->TglRujukan) : null }}">
                                </div>
                            </div>
                            <div class="form-group row mb-3 rujukan" @if (!$rujukan) style="display: none" @endif>
                                <label for="" class="col-sm-2 col-form-label">File <sup class="text-danger">*</sup></label>
                                <div class="col-sm-3 ">
                                    @if (auth()->user()->can('konseling.verifikasi') && !$isStatusSelesai)
                                    <input type="file"  class="form-control " name="files[]" id="files" placeholder="files" value="" multiple>
                                    @endif
                                    <br>
                                    <table width="100%">
                                        @if (isset($data->konseling))

                                        @foreach ($data->konseling->files as $file)

                                        <tr>
                                            <td><a href="{{ route('file',$file->Id) }}">{{ $file->NmFileOriginal }}</a></td>

                                            @if (auth()->user()->can('konseling.verifikasi') && $data->konseling->RefTahapanId <> 4)
                                            <td width="10%">

                                                <a href="#" class="text-danger delete" target="_blank"
                                                    title="Hapus File" data-type="dokumenFile" data-id="{{ $file->Id }}"
                                                    data-url={{url('/file/'.$file->Id) }}>
                                                    <i data-feather='trash-2'>
                                            </td>
                                            @endif
                                        </tr>
                                        @endforeach
                                        @endif
                                    </table>
                                </div>
                            </div>
                        @endif
                        @if ($data->isVerifikasi)
                        <div class="form-group row mb-3">
                            <label for="" class="col-sm-2 col-form-label">Status</label>
                            <div class="col-sm-3">
                                @if ($data->method=='PUT'&&in_array($data->konseling->RefTahapanId,[4,5]))

                                <select name="RefTahapanId" id="RefTahapanId" class="form-select">
                                    <option {{ isset($data->konseling) && (4==$data->konseling->RefTahapanId) ?
                                        'selected':'' }} value="4">Belum Dinyatakan Selesai</option>
                                    <option {{ isset($data->konseling) && (5==$data->konseling->RefTahapanId) ?
                                        'selected':'' }} value="5">Validasi Selesai Konseling</option>
                                </select>
                                @else
                                <select name="RefTahapanId" id="RefTahapanId" class="form-select">
                                    @foreach ($data->refTahapan->whereIn('Id',[2,11,10]) as $tahapan)
                                    <option {{ isset($data->konseling) && ($tahapan->Id==$data->konseling->RefTahapanId) ?
                                        'selected':'' }} value="{{ $tahapan->Id }}">{{ $tahapan->Nama }}</option>
                                    @endforeach
                                </select>
                                @endif
                            </div>
                        </div>
                        @endif
                        @if ($data->method=='PUT'&&in_array($data->konseling->RefTahapanId,[4,5]))
                        <div class="form-group row mb-3">
                            <label  for="" class="col-sm-2 col-form-label ">Catatan Validasi Konseling</label>
                            <div class="col-sm-3">
                                <textarea name="CatatanHasilKonseling" class="form-control">{{ $data->konseling->CatatanHasilKonseling ?? '' }}</textarea>
                            </div>
                        </div>

                        @endif
                    </div>
                    @if ($data->method=='PUT'&& $data->hasilKonseling==1)

                        <div class="table-responsive">
                            <table class="table b-table table-striped table-hover" id="table">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Durasi</th>
                                        <th>Permasalahan</th>
                                        <th>Sub Masalah</th>
                                        <th>Rujukan</th>
                                        <th>Catatan</th>
                                        <th>File</th>
                                        <th>Status Rekomendasi</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <br>
                    @endif
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

@include('modal.pegawai-modal')
@include('modal.jadwal-konseling')
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
@include('konseling.script')
@endpush