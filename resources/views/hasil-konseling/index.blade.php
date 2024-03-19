@extends('layouts/contentLayoutMaster')

@section('title', 'Hasil Konseling')

@section('button-right')

@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <form method="GET" id="form-filter">
            <div class="form-group row mb-2">

                <label for="staticEmail" class="col-sm-6 col-form-label"></label>
                <label for="staticEmail" class="col-sm-2 col-form-label"></label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="q" name="q" value="{{ request()->q ?? '' }}"
                        placeholder="Cari Berdasarkan Nama ">
                </div>
                <!-- <div class="col-sm-1">
                <input type="submit" class="btn btn-outline-secondary " value="Cari">
            </div> -->

            </div>
        </form>

        <div class="table-responsive">
            <table class="table b-table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Pegawai</th>
                        <th>Konseli</th>
                        <th>Unit Organisasi</th>
                        <th>No Konseli</th>
                        <th>Pelaksanaan Konseling</th>
                        <th>Status Konseli</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($data->konseling as $konseling)
                    <tr>
                        <td>{{ $konseling->Nip }} <br> {{ $konseling->pegawai->NmPeg }}

                            @if ($konseling->IdParent)

                            <a target="_blank" href="{{ route('hasil-konseling.show', $konseling->IdParent) }}?konseling=1" class="text-info"
                                title="Informasi"><i data-feather='info'></i></a>
                            @endif
                        </td>
                        <td>{{ $konseling->hubunganKeluarga->Nama ?? '' }} <br> {{ $konseling->RefHubunganKeluargaId<>0? $konseling->NmPeg:'' }}</td>
                        <td>{{ getNmUnitOrg($konseling->KdUnitOrg) }} <br> {{ getNmUnitOrgInduk($konseling->KdUnitOrg) }}</td>
                        <td>{{ $konseling->NoKonseli }}</td>
                        <td>{{ $konseling->pelaksanaan->Nama ?? '' }}<br> {{ $konseling->jadwalKonseling ? dateOutput($konseling->jadwalKonseling->Tanggal): '' }}</td>
                        <td>{{ $konseling->status->Nama ?? '' }}</td>
                        <td>
                            {{ $konseling->tahapan->Nama ?? '' }} <br>
                            @if($konseling->AlasanPerubahan) <span class="text-primary">{{ $konseling->AlasanPerubahan }}</span> <br> @endif
                            @if($konseling->AlasanPenolakan) <span class="text-danger">{{ $konseling->AlasanPenolakan }}</span> <br> @endif
                            @if($konseling->AlasanPembatalan) <span class="text-warning">{{ $konseling->AlasanPembatalan }}</span> <br> @endif
                            <span class="text-info">{{ $konseling->statusRekomendasi->Nama ?? '' }}</span>
                        </td>
                        <td>
                            <a target="_blank" href="{{ route('konseling.index') }}?history=1&nip={{ $konseling->Nip }}" class="text-info"
                                title="History"><i data-feather='file'></i></a>
                            <a href="{{ route('hasil-konseling.show', $konseling->Id) }}" class="text-primary"
                                title="Hasil"><i data-feather='check-square'></i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table><br>
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