@extends('layouts/contentLayoutMaster')

@section('title', 'Jenis Konseling')

@section('button-right')
@can('master-jenis-konseling.create')
<a href="#" class="btn btn-primary " id="addBtn">+ Tambah</a>
@endcan
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table b-table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th width="10%"></th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($data->refJenisKonseling as $v)
                    <tr>
                        <td>{{ $v->Nama }}</td>
                        <td class="text-center">
                            {{-- <a href="{{ route('jenis-konseling.show',$v->Id) }}" class="text-primary"
                                title="Edit"><i data-feather='eye'></i> </a> --}}
                            <a href="#" data-id="{{ $v->Id }}" data-nama="{{ $v->Nama }}" class="text-warning editBtn"
                                title="Edit"><i data-feather='edit'></i> </a>
                            <a data-url="/master/jenis-konseling/{{ $v->Id }}" title="Hapus" class="text-danger delete">
                                <i data-feather='trash-2'></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table><br>
            <div class="row">
                <div class="col-10">
                    {{ $data->refJenisKonseling->appends($_GET)->links() }}
                </div>
                <div class="col-2 text-right">
                    <b>{{ $data->refJenisKonseling->count() }}</b> dari <b>{{ $data->refJenisKonseling->total() }}</b> Baris
                </div>
            </div>
        </div>

    </div>
</div>

<div class="modal fade" id="FormModal" tabindex="-1" aria-labelledby="FormModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="FormModalLabel"></h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" id="formAsetTetap" method="POST">
                @csrf
                @method('POST')
                <div class="modal-body">
                    <input type="hidden" id="Id">
                    <div class="form-group row mb-2 ">
                        <label for="" class="col-sm-2 col-form-label">Nama</label>
                        <div class="col-sm-10">
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="Nama" id="Nama" placeholder="Nama"
                                    value="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" name="tolak">Simpan</button>
                </div>
            </form>
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
@include('master.jenis-konseling.script')

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