@extends('layouts/contentLayoutMaster')

@section('title', 'Master Jadwal Konseling')

@section('button-right')
<a href="jadwal-konseling/create" class="btn btn-primary">+ Tambah</a>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <form method="GET" id="form-filter">
        <div class="form-group row mb-2" id="formSearch">

            <div class="col-sm-2">
                <input type="text" class="form-control flatpickr" name="TanggalAwal" id="TanggalAwal" placeholder="Tanggal Awal"
                    value="{{ request()->TanggalAwal ?? null }}">
            </div>
            <label for="staticEmail" class="col-sm-1 col-form-label text-center">s/d</label>
            <div class="col-sm-2">
                <input type="text" class="form-control flatpickr" name="TanggalAkhir" id="TanggalAkhir" placeholder="Tanggal Akhir"
                    value="{{ request()->TanggalAkhir ?? null }}">
            </div>
            <div class="col-sm-2">
                <select required name="RefRegionalId" id="RefRegionalId" class="form-select">
                    <option value="">Pilih Regional Konseling</option>
                    @foreach ($data->refRegional as $regional)
                    <option {{ $regional->Id == request()->RefRegionalId?
                        'selected':'' }} value="{{ $regional->Id }}">{{ $regional->Nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-2">

                <select required name="RefLokasiId" id="RefLokasiId" class="form-select">
                    <option value="">Pilih Lokasi Konseling</option>
                    @foreach ($data->refLokasi as $lokasi)
                    <option {!! request()->RefRegionalId<>null&&request()->RefRegionalId==$lokasi->RefRegionalId ? '':'style="display:none"' !!}
                    {{ $lokasi->Id == request()->RefLokasiId ?
                        'selected':'' }} value="{{ $lokasi->Id }}">{{ $lokasi->Nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-2">
                <input type="text" class="form-control" id="q" name="q" value="{{ request()->q ?? '' }}"
                    placeholder="Cari Berdasarkan Nama ">
            </div>
            <!-- <div class="col-sm-1">
                <input type="submit" class="btn btn-outline-secondary " value="Cari">
            </div> -->

        </div>
        </form>
        <div class="table-responsive">
            <table class="table table-striped table-top">
                <thead>
                    <tr>
                        <th>Regional</th>
                        <th>Lokasi</th>
                        <th>Tanggal</th>
                        <th>Konselor / Psikolog</th>
                        <th>Sesi</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($data->jadwalKonseling as $v)
                    <tr>
                        <td>{{ $v->regional->Nama ?? '' }}</td>
                        <td>{{ $v->lokasi->Nama ?? '' }}</td>
                        <td>{{ dateOutput($v->Tanggal) }}</td>
                        <td>{{ $v->Konselor->NIP ?? $v->Konselor->NIK ?? ''}} <br> {{ $v->Konselor->Nama ?? '' }}</td>
                        <td>{{ $v->JamMulai }} s/d {{ $v->JamSelesai }}</td>
                        <td>
                            <a href="{{ route('jadwal-konseling.edit', $v->Id) }}" class="text-warning" title="Edit"><i
                                    data-feather='edit'></i> </a>
                            <a data-url="{{ route('jadwal-konseling.destroy', $v->Id ) }}" title="Hapus"
                                class="text-danger delete">
                                <i data-feather='trash-2'></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table><br>
            <div class="row">
                <div class="col-10">
                    {{ $data->jadwalKonseling->appends($_GET)->links() }}
                </div>
                <div class="col-2 text-right">
                    <b>{{ $data->jadwalKonseling->count() }}</b> dari <b>{{ $data->jadwalKonseling->total() }}</b> Baris
                </div>
            </div>
        </div>

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
    $(".select2").select2({
        width: "400px",
        allowClear: true,
        placeholder: 'Pilih peran'
    });

    $('#q').on('keyup', function(e) {
        if (e.key === 'Enter' || e.keyCode === 13) {
            $(this).closest('form').submit()
        }
    })
    $( "#TanggalAwal,#TanggalAkhir,#RefRegionalId,#RefLokasiId" ).change(function() {
        $(this).closest('form').submit()
    })
</script>

@include('system.layouts._delete')

@endpush
@push('css')
<style>
    .table-responsive {
        overflow-y: hidden;
    }

    .table td {
        vertical-align: top;
        font-size: 14px;
    }
</style>
@endpush