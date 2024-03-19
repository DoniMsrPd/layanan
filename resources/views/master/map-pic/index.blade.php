@extends('layouts/contentLayoutMaster')

@section('title', 'Master Map PIC')

@section('button-right')
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        {{-- <form method="GET" id="form-filter">
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
            <label for="staticEmail" class="col-sm-3 col-form-label text-center"></label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="q" name="q" value="{{ request()->q ?? '' }}"
                    placeholder="Cari Berdasarkan Nama ">
            </div>
            <!-- <div class="col-sm-1">
                <input type="submit" class="btn btn-outline-secondary " value="Cari">
            </div> -->

        </div>
        </form> --}}
        <div class="table-responsive">
            <table class="table table-striped table-top">
                <thead>
                    <tr>
                        <th width="15%">Regional</th>
                        <th>PIC</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($data->refRegional as $v)
                    <tr>
                        <td>{{ $v->Nama ?? '' }}
                            @can('master-map-pic.create')
                            <a data-id="{{ $v->Id }}" title="Tambah PIC {{ $v->Nama ?? '' }}"  class="text-primary btnPIC pl-2" data-bs-toggle="modal"
                            data-bs-target="#PegawaiModal">
                            <i data-feather='plus'></i>
                            </a>
                            @endcan
                        </td>
                        <td>
                            @foreach ($v->pic as $rk)
                                {{ $rk->Nip }} {{ $rk->user->name }}
                                <input data-ref_regional_id="{{ $v->Id }}" type="hidden" class="pegawai" value="{{ $rk->Nip }}">

                                @can('master-map-pic.delete')

                                <a data-url="{{ url('master/map-pic') }}/{{ $rk->Id }}"  class="text-danger delete pl-2" title="Hapus Pegawai">
                                    <i data-feather='trash'></i>
                                </a>
                                @endcan
                                <br>
                            @endforeach
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table><br>
            <div class="row">
                <div class="col-10">
                    {{ $data->refRegional->appends($_GET)->links() }}
                </div>
                <div class="col-2 text-right">
                    <b>{{ $data->refRegional->count() }}</b> dari <b>{{ $data->refRegional->total() }}</b> Baris
                </div>
            </div>
        </div>

    </div>
</div>
@include('modal.pegawai-modal')
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
@include('master.map-pic.script')

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