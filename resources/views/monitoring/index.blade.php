@extends('layouts/contentLayoutMaster')

@section('title', 'Monitoring')

@section('button-right')
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <form method="GET" id="form-filter">
            <div class="form-group row mb-2">

                <label for="staticEmail" class="col-sm-1 col-form-label">Status</label>
                <div class="col-sm-2">
                    <select name="tahapan" id="tahapan" class="select2">
                        <option value="">Semua Status</option>
                        @foreach ($data->refTahapan as $tahapan)
                            <option value="{{ $tahapan->Id }}" {{ $tahapan->Id==request()->tahapan? 'selected':'' }}>{{ $tahapan->Nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-2">
                    <select name="Tgl" id="Tgl" class="select2">
                        <option value="TglKonselingUsulan">Tgl Konseling Usulan</option>
                        <option value="TglRujukan">Tgl Rujukan</option>
                        <option value="CreatedAt">Tgl Created</option>
                    </select>
                </div>
                <div class="col-sm-2">
                    <input type="text" class="form-control flatpickr filter" name="TglAwal" id="TglAwal"
                        placeholder="TglAwal" value="{{ request()->TglAwal }}">
                </div>
                <div class="col-sm-2">
                    <input type="text" class="form-control flatpickr filter" name="TglAkhir" id="TglAkhir"
                        placeholder="TglAkhir" value="{{ request()->TglAkhir }}">
                </div>
                <div class="col-sm-3">
                    <div class="input-group input-group-merge">
                    <input type="text" class="form-control" id="q" name="q" value="{{ request()->q ?? '' }}"
                        placeholder="Cari Berdasarkan Nama ">
                        <a href="#" class="btn btn-info btn-sm " id="cari">Cari</a>
                        <a href="#" class="btn btn-success btn-sm " id="excel">Excel</a>
                    </div>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table b-table table-striped table-hover">
                <thead>
                    <tr>
                        <th>No Konseli</th>
                        <th>Pegawai</th>
                        <th>Unit Organisasi</th>
                        <th>Konseli</th>
                        <th>Status Konseli</th>
                        <th>Pelaksanaan Konseling</th>
                        <th>Regional</th>
                        <th>Konselor</th>
                        <th>File</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($data->konseling as $konseling)
                    <tr>
                        <td>{{ dateOutput($konseling->CreatedAt) }} <br>{{ $konseling->NoKonseli }}</td>
                        <td>{{ $konseling->Nip }} <br> {{ $konseling->pegawai->NmPeg }} </td>
                        <td>{{ getNmUnitOrg($konseling->KdUnitOrg) }} <br> {{ getNmUnitOrgInduk($konseling->KdUnitOrg) }}</td>
                        <td>{{ $konseling->hubunganKeluarga->Nama ?? '' }} <br> {{ $konseling->RefHubunganKeluargaId<>0? $konseling->NmPeg:'' }}</td>
                        <td>{{ $konseling->status->Nama ?? '' }}</td>
                        <td>{{ $konseling->pelaksanaan->Nama ?? '' }}</td>
                        <td>{{ $konseling->regional->Nama ?? '' }}</td>
                        <td>{{ $konseling->konselor->Nama ?? '' }} <br> {{ $konseling->jadwalKonseling ? dateOutput($konseling->jadwalKonseling->Tanggal): '' }}</td>
                        <td>

                            @if ($konseling->FormIntakeAt)
                            <a href="{{ route('konseling.view-form-intake', $konseling->Id) }}" title="Intake"
                                title="Kirim"><i data-feather='file'></i> </a>
                            @endif
                            @foreach ($konseling->files as $file)
                            <a href="{{ route('file',$file->Id) }}" title="ND" style="color: green"><i data-feather='file'></i> </a>
                            @endforeach
                            @if (isset($konseling->hasilKonseling))
                                @foreach ($konseling->hasilKonseling as $hk)
                                @if ($hk->files->first())
                                <a href="{{ route('file',$hk->files->first()->Id) }}" title="Hasil Konseli" style="color: purple"><i data-feather='file'></i> </a>
                                @endif
                                @endforeach
                            @endif
                            <br>{{ $konseling->NDRujukan }}
                        </td>
                        <td>
                            {{ $konseling->tahapan->Nama ?? '' }} <br>
                            @if($konseling->AlasanPerubahan) <span class="text-primary">{{ $konseling->AlasanPerubahan }}</span> <br> @endif
                            @if($konseling->AlasanPenolakan) <span class="text-danger">{{ $konseling->AlasanPenolakan }}</span> <br> @endif
                            @if($konseling->AlasanPembatalan) <span class="text-warning">{{ $konseling->AlasanPembatalan }}</span> <br> @endif
                            <span class="text-info">{{ $konseling->statusRekomendasi->Nama ?? '' }}</span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('hasil-konseling.show', $konseling->Id) }}?monitoring=1" class="text-info"
                                title="Hasil"><i data-feather='eye'></i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <br>
            <div class="row">
                <div class="col-10">
                    {{ $data->konseling->appends($_GET)->links() }}
                </div>
                <div class="col-2 text-right">
                    <b>{{ $data->konseling->count() }}</b> dari <b>{{ $data->konseling->total() }}</b> Baris
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
@endsection

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
    $("#cari").on('click', function() {
        $(this).closest('form').submit()
    })
    $("#excel").on('click', function() {
        param = $("#form-filter").serialize();
        window.open("{{ url('/monitoring/excel') }}?"+param,"_self");
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