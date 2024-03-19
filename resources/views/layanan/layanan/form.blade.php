@extends('layouts/contentLayoutMaster')

{{-- @section('title', $data->title) --}}

@section('button-right')
@endsection

@section('content')
<div class="card">
    <h5 class="card-header">
        {{ $data->title }}
            <span class="btn-group" role="group" style=" float: right;">
                <a class="mb-2 mr-2 btn btn-primary btn-sm" onclick="history.back()">Kembali</a>
                @if($data->layanan)
                <a data-id="{{ $data->layanan->Id }}"
                    data-url="{{ url('layanan').'/'.$data->layanan->Id }} "
                    data-redirect="{{ url('/layanan') }}" class="mb-2 mr-2 btn btn-danger btn-sm deleteData"
                    data-title="Data" title="Hapus" title-pos="up"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                    Delete</a>
                @endif
            </span>
        </h5>
    <div class="card-body">
        <form method="POST" action="{{ $data->action }}" enctype="multipart/form-data">
            @csrf
            @method( $data->method )
            <input type="hidden" name="KdUnitOrgOwnerLayanan" value="{{ request()->KdUnitOrgOwnerLayanan }}">
            <div class="element-wrapper" style="padding-bottom: 10px;">
                <div class="element-box">
                    <input type="hidden" name="eskalasi" id="" value="{{ $data->eskalasi ?? 0 }}">
                    <input type="hidden" class="form-control" name="id" id="id" value="{{ $data->layanan->Id ??'' }}"
                        enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group row">
                                <label class="col-form-label col-sm-3" for=""> Pelapor Layanan <sup
                                        class="text-danger">*</sup></label>
                                <div class=" col-sm-8">
                                    <input type="hidden" name="KdUnitOrg" id="KdUnitOrg"
                                        value="{{ $data->layanan->KdUnitOrg ?? auth()->user()->pegawai->KdUnitOrg }}">
                                    <input type="hidden" name="NmUnitOrg" id="NmUnitOrg"
                                        value="{{ $data->layanan->NmUnitOrg ?? auth()->user()->pegawai->NmUnitOrg }}">
                                    <input type="hidden" name="NmUnitOrgInduk" id="NmUnitOrgInduk"
                                        value="{{ $data->layanan->NmUnitOrgInduk ?? auth()->user()->pegawai->NmUnitOrgInduk }}">
                                    <input type="hidden" name="NmPeg" id="NmPeg"
                                        value="{{ $data->layanan->NmPeg ?? auth()->user()->pegawai->NmPeg }}">
                                    <input type="hidden" name="Nip" id="Nip"
                                        value="{{ $data->layanan->Nip ?? auth()->user()->pegawai->Nip }}">
                                    <input style="cursor: pointer;" readonly class="form-control lookup-pegawai"
                                        placeholder="" id="NmPegawai"
                                        value="{{ $data->layanan ? $data->layanan->Nip .' - '.$data->layanan->pelapor->NmPeg : auth()->user()->NIP.' - '.auth()->user()->pegawai->NmPeg }}">
                                    <div id="NmJabatan">{{ isset($data->layanan->Id) ?
                                        $data->layanan->pelapor->NmJabatan :
                                        auth()->user()->pegawai->NmJabatan }}</div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-sm-3" for=""> Satker Pelapor</label>
                                <div class=" col-sm-8">
                                    <div style="padding: 8px" id="NmUnitOrgPelapor">{{
                                        $data->layanan->pelapor->NmUnitOrg ??
                                        auth()->user()->pegawai->NmUnitOrg }}</div>
                                    <div style="padding: 8px" id="NmUnitOrgIndukPelapor">{{ ($data->layanan &&
                                        $data->layanan->pelapor->NmUnitOrgInduk<>$data->layanan->pelapor->NmUnitOrg?
                                            $data->layanan->pelapor->NmUnitOrgInduk :'') ??
                                            (auth()->user()->pegawai->NmUnitOrgInduk<>auth()->user()->pegawai->NmUnitOrg
                                                ?
                                                auth()->user()->pegawai->NmUnitOrgInduk :'') }}</div>
                                </div>
                            </div>
                            @if(request()->KdUnitOrgOwnerLayanan == '100205000000')
                            <div class="form-group row">
                                <label class="col-form-label col-sm-3" for=""></label>
                                <div class="col-sm-6">
                                    <input type="checkbox" id="pegawaiLain" name="pegawaiLain" value="1"
                                        @if(($data->layanan)
                                    &&$data->layanan->Nip!=$data->layanan->NipLayanan) checked @endif> Membuat Pemintaan
                                    Pegawai
                                    Lain <br>
                                </div>
                            </div>
                            @endif
                            <div class="form-group row layanan" style="display: none">
                                <label class="col-form-label col-sm-3" for=""> Penerima Layanan<sup
                                        class="text-danger">*</sup></label>
                                <div class=" col-sm-8">
                                    <div class="input-group">
                                        <input type="hidden" name="KdUnitOrgLayanan" id="KdUnitOrgLayanan"
                                            value="{{ $data->layanan->KdUnitOrgLayanan ?? auth()->user()->pegawai->KdUnitOrg }}">
                                        <input style="cursor: pointer;" readonly class="form-control lookup-pegawai2"
                                            placeholder="" name="NipLayanan" id="NipLayanan"
                                            value="{{ $data->layanan->NipLayanan ??auth()->user()->NIP }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary lookup-pegawai2" type="button"><i
                                                    class="icon-feather-search"></i></button>
                                        </div>
                                    </div>
                                    <div id="NmPegLayanan">{{ isset($data->layanan->Id) ?
                                        $data->layanan->penerima->NmPeg :
                                        auth()->user()->pegawai->NmPeg }}</div>
                                    <div id="NmJabatanLayanan">{{ isset($data->layanan->Id) ?
                                        $data->layanan->penerima->NmJabatan :
                                        auth()->user()->pegawai->NmJabatan }}</div>
                                </div>
                            </div>
                            <div class="form-group row layanan" style="display: none">
                                <label class="col-form-label col-sm-3" for=""> Satker Penerima </label>
                                <div class=" col-sm-8">
                                    <div style="padding: 8px" id="NmUnitOrgLayanan">{{
                                        $data->layanan->penerima->NmUnitOrg ??
                                        auth()->user()->pegawai->NmUnitOrg }}</div>
                                    <div style="padding: 8px" id="NmUnitOrgIndukLayanan">{{ ($data->layanan &&
                                        $data->layanan->penerima->NmUnitOrgInduk<>$data->layanan->penerima->NmUnitOrg?
                                            $data->layanan->penerima->NmUnitOrgInduk :'') ??
                                            (auth()->user()->pegawai->NmUnitOrgInduk<>auth()->user()->pegawai->NmUnitOrg
                                                ?
                                                auth()->user()->pegawai->NmUnitOrgInduk :'') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group row"><label class="col-sm-3 col-form-label">Informasi Layanan <sup
                                        class="text-danger">*</sup></label>
                                <div class="col-sm-9"><textarea required class="form-control" rows="3"
                                        name="PermintaanLayanan">{{ $data->layanan->PermintaanLayanan ??'' }}</textarea>
                                </div>
                            </div>

                            <div class="form-group row"><label class="col-sm-3 col-form-label">Kategori </label>
                                <div class="table-responsive text-nowrap">
                                    <table class="table table-borderless table-hover" id="tableKategori">
                                        <thead style="border-bottom: 1px dashed #ebedf2;">
                                            <tr>
                                                <th width="4%" style="font-size:0.7rem">No</th>
                                                <th style="font-size:0.7rem" width="15%">Nama</th>
                                                <th style="font-size:0.7rem">Keterangan</th>
                                                <th width="16%" style="text-align: center;">
                                                    <button style="padding:3px 3px;margin:0px" type="button"
                                                        data-toggle="modal"
                                                        class="btn btn-success btn-sm lookup-kategori">
                                                        <i data-feather="plus" title="Tambah Kategori"></i>
                                                    </button>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if($data->layanan)
                                            @foreach ($data->layanan->layananKategori as $kategori)
                                            <tr id="row1"> <input type="hidden" name="Kategori[]"
                                                    value="{{ $kategori->MstKategoriId }}">
                                                <td style="font-size:0.8rem">{{ $loop->iteration }}</td>
                                                <td class="kategori" style="display:none">{{ $kategori->MstKategoriId }}
                                                </td>
                                                <td style="font-size:0.8rem">{{ $kategori->mstKategori->Nama }}</td>
                                                <td style="font-size:0.8rem">{{ $kategori->mstKategori->Keterangan }}
                                                </td>
                                                <td class="text-center"><a style="padding:3px 3px;margin:0px"
                                                        class="btn btn-danger btn-sm deleteData"
                                                        data-url="/layanan/kategori/{{ $kategori->Id }}"
                                                        data-title="{{ $kategori->mstKategori->Nama }}"
                                                        data-id="{{ $kategori->Id }}" href="javascript:void(0)"
                                                        title="Hapus"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg></a></td>
                                            </tr>
                                            @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-sm-3" for=""> Nomor Kontak <sup
                                        class="text-danger">*</sup></label>
                                <div class=" col-sm-4"><input class=" form-control" placeholder="Nomor Kontak"
                                        required="" name="NomorKontak"
                                        value="{{ $data->layanan->NomorKontak ?? auth()->user()->pegawai->NoHp }}">
                                </div>
                                <label class="col-form-label col-sm-4" for=""><sup class="text-danger"> * Harap Isi
                                        dengan Nomor
                                        Kontak Aktif</sup></label>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-sm-3" for=""> File Attachment</label>
                                <div class=" col-sm-6 custom-file"><input type="file" class=" form-control custom-file"
                                        placeholder="File Attachment" name="FileAttachment[]" multiple>
                                </div>

                                @if (isset($data->layanan->files) && count($data->layanan->files) > 0)
                                <ul class="pt-1 mb-0 list-file">
                                    @foreach ($data->layanan->files as $key => $file)
                                    <li>
                                        <a href="/core/{{ $file->PathFile }}" class="f-16" target="_blank">
                                            <span class="mdi mdi-file-pdf"></span> {{
                                            \Illuminate\Support\Str::limit($file->NmFileOriginal, 23) }}
                                        </a>
                                        <span style="cursor: pointer;" data-id="{{ $file->Id }}"
                                            data-title="{{ $file->NmFileOriginal }}"
                                            data-url="/core/storage/{{ $file->Id }}"
                                            class="text-danger deleteData float-right"><i
                                                class="icon-feather-trash-2"></i></span>
                                    </li>
                                    @endforeach
                                </ul>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if($data->eskalasi && request()->user()->can('layanan.eskalasi'))
            <div class="element-wrapper" style="padding-bottom: 10px;">
                <div class="element-box">
                    <h5 class="form-header">ESKALASI</h5>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group row">
                                <input type="hidden" name="groupSolver" id="groupSolver"
                                    value="{{ $data->layanan->groupSolver->pluck('MstGroupSolverId')->implode(',') }}">
                                <input type="hidden" id="deleteGroupSolver" name="deleteGroupSolver">
                                <label class="col-form-label col-sm-2" for=""> Group Solver</label>
                                <div class=" col-sm-6">
                                    <table class="table table-borderless table-hover" id="tableGroupSolver">
                                        <thead style="border-bottom: 1px dashed #ebedf2;">
                                            <tr>
                                                <th width="4%" style="font-size:0.7rem">No</th>
                                                <th style="font-size:0.7rem">Nama</th>
                                                <th width="16%" style="text-align: center;">
                                                    <button style="padding:3px 3px;margin:0px" type="button"
                                                        data-toggle="modal"
                                                        class="btn btn-success btn-sm lookup-group-solver">
                                                        <i class="icon-feather-plus"></i>
                                                    </button>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data->layanan->groupSolver as $key => $groupSolver)
                                            <tr>
                                                <td>{{ $key+1 }}</td>
                                                <td class="groupSolver" style="display: none">{{
                                                    $groupSolver->MstGroupSolverId
                                                    }}</td>
                                                <td>{{ $groupSolver->mstGroupSolver->Nama }}</td>
                                                <td class="text-center"><button style='padding:3px 3px;margin:0px'
                                                        class='btn btn-danger btn-sm deleteGroupSolver'
                                                        data-id='{{ $groupSolver->Id }}' href='javascript:void(0)'
                                                        title='Hapus'><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg></button>
                                                </td>
                                            </tr>

                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group row">
                                <input type="hidden" name="solver" id="solver"
                                    value="{{ $data->layanan->solver->pluck('Nip')->implode(',') }}">
                                <input type="hidden" id="deleteSolver" name="deleteSolver">
                                <label class="col-form-label col-sm-2" for=""> Tim Solver</label>
                                <div class=" col-sm-6">
                                    <table class="table table-borderless table-hover" id="tableSolver">
                                        <thead style="border-bottom: 1px dashed #ebedf2;">
                                            <tr>
                                                <th width="4%" style="font-size:0.7rem">No</th>
                                                <th style="font-size:0.7rem">Solver</th>
                                                <th width="16%" style="text-align: center;">
                                                    <button style="padding:3px 3px;margin:0px" type="button"
                                                        data-toggle="modal"
                                                        class="btn btn-success btn-sm lookup-solver">
                                                        <i class="icon-feather-plus"></i>
                                                    </button>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data->layanan->solver as $key => $solver)
                                            <tr>
                                                <td>{{ $key+1 }}</td>
                                                <td class="solver" style="display: none">{{ $solver->Nip }}</td>
                                                <td>{{ $solver->mstSolver->pegawai->NmPeg }}</td>
                                                <td class="text-center"><button style='padding:3px 3px;margin:0px'
                                                        class='btn btn-danger btn-sm deleteSolver'
                                                        data-id='{{ $solver->Id }}' href='javascript:void(0)'
                                                        title='Hapus'><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg></button>
                                                </td>
                                            </tr>

                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <div class="element-wrapper">
                <div class="element-box">
                    <div class="text-center form-buttons-w">
                        <button class="btn btn-primary" type="submit">
                            Submit</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@include('core.modals.pegawai')
@include('core.modals.pegawai2')
@include('layanan.layanan.modal.group_solver')
@include('layanan.layanan.modal.solver')
@include('layanan.layanan.modal.kategori')
@endsection

@section('vendor-style')
<link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/toastr.min.css')) }}">
@endsection
@section('page-style')
<link rel="stylesheet" href="{{ asset(mix('css/base/plugins/extensions/ext-component-toastr.css')) }}">
@endsection

@section('vendor-script')
<script src="{{ asset(mix('vendors/js/extensions/toastr.min.js')) }}"></script>
@endsection
@push('scripts')

@include('core._script-delete')
@include('layanan.layanan._script-form')

@if($data->eskalasi && request()->user()->can('layanan.eskalasi'))
@include('layanan.layanan._script-eskalasi')
@endif

@endpush
@push('css')
<style>
    .ui-datepicker-calendar {
        display: none;
    }
</style>
@endpush
