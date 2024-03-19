@extends('layouts/contentLayoutMaster')

@section('title', 'Master Konselor')

@section('button-right')
@if (!auth()->user()->konselor)
<a href="konselor/create" class="btn btn-primary">+ Tambah</a>
@endif
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
            <table class="table table-striped table-top">
                <thead>
                    <tr>
                        <th>Pegawai</th>
                        <th>Jenis Konselor</th>
                        <th>Regional</th>
                        <th>Lokasi</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($data->konselor as $konselor)
                    <tr>
                        <td>{{ $konselor->NIP ??  $konselor->NIK }} <br> {{ $konselor->Nama }}</td>
                        <td>{{ $konselor->jenisKonselor->Nama ?? '' }}</td>
                        <td>{{ $konselor->regional->Nama ?? '' }}</td>
                        <td>{{ $konselor->lokasi->Nama ?? '' }}</td>
                        <td>
                            @if (auth()->user()->can('master-konselor.update') || auth()->user()->konselor)

                            @endif
                            <a href="{{ route('konselor.edit', $konselor->Id) }}" class="text-warning" title="Edit"><i data-feather='edit'></i> </a>
                            @can ('master-konselor.delete')
                            <a data-url="{{ route('konselor.destroy', $konselor->NIP ?? $konselor->NIK ) }}" title="Hapus" class="text-danger delete">
                                <i data-feather='trash-2'></i>
                            </a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table><br>
            <div class="row">
                <div class="col-10">
                    {{ $data->konselor->appends($_GET)->links() }}
                </div>
                <div class="col-2 text-right">
                    <b>{{ $data->konselor->count() }}</b> dari <b>{{ $data->konselor->total() }}</b> Baris
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