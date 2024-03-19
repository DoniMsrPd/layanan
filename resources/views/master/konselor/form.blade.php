@extends('layouts/contentLayoutMaster')

@section('title', $data->title)

@section('button-right')
<a href="/master/konselor" class="btn btn-primary">Kembali</a>
@endsection

@section('content')

<div class="row">
    <div class="col-md-12 col-12">
        <div class="card">
            <div class="card-body">

                <form id="form" action="{{ $data->action }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method($data->method)
                    @php
                    $isEksternal = isset($data->konselor) && $data->konselor->NIK;
                    $disable = isset(auth()->user()->konselor) || isset(auth()->user()->konselorInternal) ;
                    @endphp
                    <div class="row">
                        <div class="col-md-6">

                            <div class="form-group row mb-2">
                                <label for="" class="col-sm-2 col-form-label">Eksternal</label>
                                <div class="col-sm-3" style="height: 53px">
                                    <input {{ $disable ? 'disabled' :'' }} class="form-check-input" type="checkbox"
                                        id="isEksternal" name="isEksternal" value="1" @if ($isEksternal) checked @endif>
                                </div>
                            </div>

                            <div class="form-group row mb-2">
                                <label @if ($isEksternal) style="display: none" @endif for=""
                                    class="col-sm-2 col-form-label internal">Pegawai <sup
                                        class="text-danger">*</sup></label>
                                <div @if ($isEksternal) style="display: none" @endif class="col-sm-8 internal">
                                    <div class="input-group input-group-merge">
                                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#PegawaiModal" title="Pilih Pegawai" id="btn-pegawai"><i
                                                data-feather='user'></i></button>
                                        <span id="Pegawai" style="padding-left: 10px">{{ $data->konselor->NIP ?? '' }}
                                            {{ $data->konselor->Nama ?? '' }}</span>
                                        <input type="hidden" name="NIP" id="NIP"
                                            value="{{ $data->konselor->NIP ?? '' }}">
                                    </div>
                                </div>
                                <label for="" class="col-sm-2 col-form-label eksternal">NIK <sup
                                        class="text-danger">*</sup></label>
                                <div class="col-sm-4 eksternal">
                                    <input {{ $disable ? 'readonly' :'' }} type="text" class="form-control" name="NIK"
                                        id="NIK" placeholder="NIK" value="{{ $data->konselor->NIK ?? null }}">
                                </div>
                                <label for="" class="col-sm-2 col-form-label eksternal">Nama <sup
                                        class="text-danger">*</sup></label>
                                <div class="col-sm-4 eksternal">
                                    <input {{ $disable ? 'readonly' :'' }} type="text" class="form-control" name="Nama"
                                        id="Nama" placeholder="Nama" value="{{ $data->konselor->Nama ?? null }}">
                                </div>
                            </div>

                            <div class="form-group row mb-2">
                                <label for="" class="col-sm-2 col-form-label eksternal">Username <sup
                                        class="text-danger">*</sup></label>
                                <div class="col-sm-4 eksternal">
                                    <input {{ $disable ? 'readonly' :'' }} type="text" class="form-control"
                                        name="username" id="username" placeholder="Username"
                                        value="{{ $data->user->username ?? null }}">
                                    <input type="hidden" name="UserId" value="{{ $data->user->id ?? null }}">
                                </div>
                                <label for="" class="col-sm-2 col-form-label eksternal">Email<sup
                                        class="text-danger">*</sup></label>
                                <div class="col-sm-4 eksternal">
                                    <input {{ $disable ? 'readonly' :'' }} type="text" class="form-control" name="email"
                                        id="email" placeholder="Email" value="{{ $data->user->email ?? null }}">
                                </div>
                            </div>
                            @if ($data->method=='POST')
                            <div class="form-group row mb-2">
                                <label for="" class="col-sm-2 col-form-label eksternal">Password <sup
                                        class="text-danger">*</sup></label>
                                <div class="col-sm-4 eksternal">
                                    <input type="password" class="form-control" name="password" id="password"
                                        placeholder="Password" value="">
                                </div>
                                <label for="" class="col-sm-2 col-form-label eksternal">Ulangi Password<sup
                                        class="text-danger">*</sup></label>
                                <div class="col-sm-4 eksternal">
                                    <input type="password" class="form-control" name="password_confirmation"
                                        id="password_confirmation" placeholder="Ulangi Passowrd" value="">
                                </div>
                            </div>
                            @else

                            @if ($isEksternal)
                            <div class="form-group row mb-2">
                                <label for="" class="col-sm-2 col-form-label">Ganti Password</label>
                                <div class="col-sm-4">
                                    <input class="form-check-input" type="checkbox" id="changePassword"
                                        name="changePassword" value="1">
                                </div>
                            </div>

                            @endif
                            <div style="display: none" class="form-group row mb-2 changePassword">
                                <label for="" class="col-sm-2 col-form-label eksternal">Password Baru <sup
                                        class="text-danger">*</sup></label>
                                <div class="col-sm-4 eksternal">
                                    <input type="password" class="form-control" name="new_password" id="new_password"
                                        placeholder="Password Baru" value="">
                                </div>
                                <label for="" class="col-sm-2 col-form-label eksternal">Ulangi Password Baru<sup
                                        class="text-danger">*</sup></label>
                                <div class="col-sm-4 eksternal">
                                    <input type="password" class="form-control" name="new_password_confirmation"
                                        id="new_password_confirmation" placeholder="Ulangi Passowrd Baru" value="">
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="col-md-6">

                            <div class="form-group row mb-2">
                                <label for="" class="col-sm-2 col-form-label">Jenis Konselor <sup
                                        class="text-danger">*</sup></label>
                                <div class="col-sm-4">
                                    @if ($disable)

                                    <input readonly type="hidden" class="form-control" name="RefJenisKonselorId"
                                        id="RefJenisKonselorId" value="{{ $data->konselor->RefRegionalId?? '' }}">
                                    <input readonly type="text" class="form-control" value="{{ $data->konselor->jenisKonselor->Nama ?? '' }}">
                                    @else
                                    <select required name="RefJenisKonselorId" id="RefJenisKonselorId"
                                        class="form-select">
                                        <option value="">Pilih Jenis Konselor</option>
                                        @foreach ($data->refJenisKonselor as $jk)
                                        <option {{ isset($data->konselor) &&
                                            $jk->Id==$data->konselor->RefJenisKonselorId ?
                                            'selected':'' }} value="{{ $jk->Id }}">{{ $jk->Nama }}</option>
                                        @endforeach
                                    </select>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label for="" class="col-sm-2 col-form-label">Regional Konseling <sup
                                        class="text-danger">*</sup></label>
                                <div class="col-sm-4">
                                    @if ($disable)

                                    <input readonly type="hidden" class="form-control" name="RefRegionalId"
                                        id="RefRegionalId" value="{{ $data->konselor->RefRegionalId?? '' }}">
                                    <input readonly type="text" class="form-control"
                                        value="{{ $data->konselor->regional->Nama ?? '' }}">
                                    @else
                                    <select required name="RefRegionalId" id="RefRegionalId" class="form-select">
                                        <option value="">Pilih Regional Konseling</option>
                                        @foreach ($data->refRegional as $regional)
                                        <option {{ isset($data->konselor) &&
                                            $regional->Id==$data->konselor->RefRegionalId ?
                                            'selected':'' }} value="{{ $regional->Id }}">{{ $regional->Nama }}</option>
                                        @endforeach
                                    </select>
                                    @endif
                                </div>

                            </div>
                            <div class="form-group row mb-2">
                                <label for="" class="col-sm-2 col-form-label">Lokasi Konseling <sup
                                        class="text-danger">*</sup></label>
                                <div class="col-sm-4">
                                    @if ($disable)


                                    <input readonly type="hidden" class="form-control" name="RefLokasiId"
                                        id="RefLokasiId" value="{{ $data->konselor->RefLokasiId?? '' }}">
                                    <input readonly type="text" class="form-control"
                                        value="{{ $data->konselor->lokasi->Nama ?? '' }}">
                                    @else
                                    <select required name="RefLokasiId" id="RefLokasiId" class="form-select">
                                        <option value="">Pilih Lokasi Konseling</option>
                                        @foreach ($data->refLokasi as $lokasi)
                                        <option style="display: none" data-regional="{{ $lokasi->RefRegionalId }}" {{ isset($data->konselor) && $lokasi->Id==$data->konselor->RefLokasiId ?
                                            'selected':'' }} value="{{ $lokasi->Id }}">{{ $lokasi->Nama }}</option>
                                        @endforeach
                                    </select>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group row mb-2 eksternal">
                        </div>
                        <div class="form-group row mb-2">
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

@include('modal.pegawai-modal')
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
@include('master.konselor.script')
@endpush