@extends('layouts/contentLayoutMaster')

@section('title', 'Konseling')

@section('button-right')
@if(auth()->user()->can('konseling.create')&&request()->history==null)
<a href="/konseling/create" class="btn btn-primary">+ Tambah</a>
@endif
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <form method="GET" id="form-filter">
            <div class="form-group row mb-2">
                <input type="hidden" name="history" value="{{ request()->history }}">
                <input type="hidden" name="nip" value="{{ request()->nip }}">
                <label for="staticEmail" class="col-sm-1 col-form-label">Status</label>
                <div class="col-sm-2">
                    <select name="tahapan" id="tahapan" class="select2">
                        <option value="">Semua Status</option>
                        @foreach ($data->refTahapan as $tahapan)
                            <option value="{{ $tahapan->Id }}" {{ $tahapan->Id==request()->tahapan? 'selected':'' }}>{{ $tahapan->Nama }}</option>
                        @endforeach
                    </select>
                </div>
                <label for="staticEmail" class="col-sm-5 col-form-label"></label>
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
                        <th>No Konseli</th>
                        <th>Pegawai</th>
                        <th>Konseli</th>
                        <th>Unit Organisasi</th>
                        <th>Status Konseli</th>
                        <th>Pelaksanaan Konseling</th>
                        <th>Regional</th>
                        <th>Konselor</th>
                        <th>File </th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($data->konseling as $konseling)
                    <tr>
                        <td>
                            {{ dateOutput($konseling->CreatedAt) }} <br>{{ $konseling->NoKonseli }}
                            @if ($konseling->IdParent)

                            <a target="_blank" href="{{ route('hasil-konseling.show', $konseling->IdParent) }}?konseling=1" class="text-info"
                                title="Informasi"><i data-feather='info'></i></a>
                            @endif
                        </td>
                        <td>{{ $konseling->Nip }} <br> {{ $konseling->pegawai->NmPeg }} </td>
                        <td>{{ $konseling->hubunganKeluarga->Nama ?? '' }} <br> {{ $konseling->RefHubunganKeluargaId<>0? $konseling->NmPeg:'' }}</td>
                        <td>{{ getNmUnitOrg($konseling->KdUnitOrg) }} <br> {{ getNmUnitOrgInduk($konseling->KdUnitOrg) }}</td>
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

                            <a href="{{ route('hasil-konseling.show', $konseling->Id) }}?konseling=1" class="text-info"
                                title="Hasil"><i data-feather='eye'></i></a>

                            @if (request()->history==null)
                                {{-- sesi lanjutan --}}
                                @if ($konseling->RefStatusRekomendasiId==4&&$konseling->TindakLanjut==null&&auth()->user()->hasRole('Staf Konseling'))
                                <a href="/konseling/create?IdLama={{ $konseling->Id }}" class="text-warning"
                                    title="Konseling Lanjutan"><i data-feather='send'></i> </a>
                                @elseif ($konseling->RefTahapanId==5&&$konseling->Nip==auth()->user()->NIP)
                                    <a href="/konseling/create?IdLama={{ $konseling->Id }}" class="text-warning"
                                        title="Konseling Lanjutan"><i data-feather='send'></i> </a>
                                @endif
                                {{-- kirim form   --}}
                                @if ($konseling->FormIntakeAt && $konseling->RefTahapanId==0)
                                <a href="{{ route('konseling.kirim', $konseling->Id) }}" class="text-info"
                                    title="Kirim"><i data-feather='send'></i> </a>
                                @endif
                                {{-- create form intake --}}
                                @if ($konseling->FormIntakeAt==null&&$konseling->IdParent==null&& $konseling->RefTahapanId==1)
                                <a href="{{ route('konseling.create-form-intake', $konseling->Id) }}" class="text-secondary"
                                    title="Create Form Intake"><i data-feather='clipboard'></i> </a>
                                @endif


                                @can('konseling.verifikasi')
                                <a href="{{ route('konseling.verifikasi', $konseling->Id) }}?verifikasi=1" class="text-primary"
                                    title="Verifikasi"><i data-feather='check-square'></i></a>
                                @else
                                    @if (($konseling->RefTahapanId==0 && auth()->user()->roles->count()==1) || (auth()->user()->roles->count() <>1))
                                        <a href="{{ route('konseling.edit', $konseling->Id) }}" class="text-warning" title="Edit"><i
                                            data-feather='edit'></i> </a>
                                    @endif
                                @endcan

                                @if (($konseling->RefTahapanId==0 && auth()->user()->roles->count()==1) || (auth()->user()->roles->count() <>1 && $konseling->RefTahapanId==0))
                                    <a data-url="/konseling/{{ $konseling->Id }}" title="Hapus" class="text-danger delete">
                                        <i data-feather='trash-2'></i>
                                    </a>
                                @endif
                            @endif
                        </td>
                    </tr>
                    @endforeach

                    @if ($data->konseling->count()==0&&request()->history==1)
                    <tr>
                        <th colspan="10" class="text-center">Anda tidak berhak</th>
                    </tr>
                    @endif
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
    $("#tahapan").on('change', function() {
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