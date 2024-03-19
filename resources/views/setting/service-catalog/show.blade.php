@extends('layouts/contentLayoutMaster')

@section('content')


<div class="card">
    <h5 class="card-header">SERVICE CATALOG <a class="btn btn-primary btn-sm" href="javascript:history.back()"
            style=" float: right;">Kembali</a></h5>
    <div class="card-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width: 30%;">Kode ITSM</th>
                                @if (substr($data->KdUnitOrgLayanan, 0, 6) == '100205')
                                <th>Peminjaman</th>
                                <th>Persediaan</th>
                                <th>Perbaikan</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <strong>{{ $data->serviceCatalog->Kode }}</strong>
                                    <br>
                                    {{ $data->serviceCatalog->Nama }}
                                </td>
                                @if (substr($data->KdUnitOrgLayanan, 0, 6) == '100205')
                                <td align="center">
                                    @if($data->serviceCatalog->IsPeminjaman==1)
                                    <i class="text-success"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check"><polyline points="20 6 9 17 4 12"></polyline></svg></i>
                                    @else
                                    <i class="text-danger"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></i>
                                    @endif
                                </td>
                                <td align="center">
                                    @if($data->serviceCatalog->IsPersediaan==1)
                                    <i class="text-success"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check"><polyline points="20 6 9 17 4 12"></polyline></svg></i>
                                    @else
                                    <i class="text-danger"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></i>
                                    @endif
                                </td>

                                <td align="center">
                                    @if($data->serviceCatalog->IsPeerbaikan==1)
                                    <i class="text-success"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check"><polyline points="20 6 9 17 4 12"></polyline></svg></i>
                                    @else
                                    <i class="text-danger"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></i>
                                    @endif
                                </td>
                                @endif
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@if (substr($data->KdUnitOrgLayanan, 0, 6) == '100205')
<div class="card">
    <h5 class="card-header">TEMATIK LAYANAN
        @can('service-catalog.create')
        <a class="btn btn-sm btn-success" id="add-tematik" href="#" style=" float: right;" data-target="#modalTematik"
            data-toggle="modal">Tambah</a>
        @endif
    </h5>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">

                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="tableTematik">
                        <thead>
                            <tr>
                                <th width="12%">No</th>
                                <th>Tematik Layanan</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
</div>

<div class="card">
    <h5 class="card-header">SLA
        @can('service-catalog-sla.create')
        <a class="btn btn-sm btn-success" style=" float: right;"
            href="{{ route('setting.service-catalog-detail.create',['service_catalog'=>$data->serviceCatalog->Id]) }}">Tambah</a>
        @endcan
    </h5>

    <div class="card-body">
        <div class="row">
            <div class="col-md-12">

                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="table">
                        <thead>
                            <tr>
                                <th width="12%">No</th>
                                <th>Nama SLA</th>
                                <th>Norma Waktu</th>
                                <th>SLA Waktu (Jam)</th>
                                <th>SLA Limit (Jam)</th>
                                <th>Jenis Perhitungan</th>
                                <th>Jenis Layanan</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="modal fade" id="modalTematik" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                Tambah Tematik Layanan
            </div>
            <div class="modal-body">
                <form method="POST" action="#" id="formTematik" enctype="multipart/form-data">
                    <input type="hidden" id="serviceCatalogId" value="{{ $data->serviceCatalog->Id }}">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group row ">
                                <label class="col-form-label col-sm-3" for=""> Nama Tematik <sup
                                        class="text-danger">*</sup></label>
                                <div class="col-md-9">
                                    <select class="form-control select2" required style="width: 75%" id="mstTematikId">
                                        <option value="">Pilih Tematik</option>
                                        {{-- @foreach ($data->mstTematik as $tematik)
                                        <option value="{{ $tematik->Id }}">{{ $tematik->Tema }}</option>
                                        @endforeach --}}
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-left form-buttons-w">
                        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Batal</button>
                        <button class="btn btn-primary btn-sm" type="submit">
                            Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    $(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                method: 'POST',
                url : "{!! route('setting.service-catalog-detail.datatables') !!}?serviceCatalogId={{ $data->serviceCatalog->Id }}&serviceCatalogIdInc={{ $data->serviceCatalog->IdInc }}",
            },
            columns: [
                {
                    data: 'Id',
                    className: "text-center" ,
                    width:'5%',
                },
                {
                    data: 'Nama'
                },
                {
                    data: 'NormaWaktu'
                },
                {
                    data: 'Waktu'
                },
                {
                    data: 'Limit'
                },
                {
                    data: 'JenisPerhitungan',
                    render: function(data) {
                        return `${data==1?'Hari Kalender':'Hari Kerja'}`
                    }
                },
                {
                    data: 'JenisLayanan',
                    render: function(data) {
                        return `${data=='r'?'Request':'Incident'}`
                    }
                },
                { data: 'action', name: 'action' , searchable:false, orderable:false, className: "text-center" ,width:'15%'}
            ]
        });
        table.on('draw.dt', function () {
            var info = table.page.info();
            table.column(0, { search: 'applied', order: 'applied', page: 'applied' }).nodes().each(function (cell, i) {
                cell.innerHTML = i + 1 + info.start;
            });
        });

        var tableTematik = $('#tableTematik').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                method: 'POST',
                url : "{!! route('setting.service-catalog-tematik.datatables') !!}?serviceCatalogId={{ $data->serviceCatalog->Id }}",
            },
            columns: [
                {
                    data: 'Id',
                    className: "text-center" ,
                    width:'5%',
                },
                {
                    data: 'Tema'
                },
                { data: 'action', name: 'action' , searchable:false, orderable:false, className: "text-center" ,width:'15%'}
            ]
        });
        tableTematik.on('draw.dt', function () {
            var info = tableTematik.page.info();
            tableTematik.column(0, { search: 'applied', order: 'applied', page: 'applied' }).nodes().each(function (cell, i) {
                cell.innerHTML = i + 1 + info.start;
            });
        });


    })

    $(document).ready(function(){
        selectTematik = () => {
            $.ajax({
                    url: "{{ url('/setting/master-tematik/select') }}",
                    type: "GET",
                    data: {
                        serviceCatalogId:'{{ $data->serviceCatalog->Id }}'
                    },
                    beforeSend: function(data) {
                        $("#mstTematikId").attr('disabled', true)
                    },
                    success: function(data) {
                        $("#mstTematikId").attr('disabled', false)
                        $("#mstTematikId").html(data)
                        $("#mstTematikId").select2()
                    },
                    error: function() {
                        alert('Terjadi kesalahan, silakan reload');
                    }
                })
        }

        selectTematik();
        $('#add-tematik').on('click', function(e){
            $('#modalTematik').modal('toggle');
        })
        $('#formTematik').on('submit', function(e){
            e.preventDefault();
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '{{ route("setting.service-catalog-tematik.store") }}',
                type: "POST",
                data: {
                    'serviceCatalogId'    : $('#serviceCatalogId').val(),
                    'mstTematikId'    : $('#mstTematikId').val(),
                    '_token'           : csrf_token
                },
                success: function (response) {
                    $('#modalTematik').modal('toggle');
                    if(response.success){
                        $('#tableTematik').dataTable().api().ajax.reload();
                        toastr.clear()
                        toastr.success(response.message)
                        selectTematik ()
                    }
                },
                error : function () {
                    alert('Terjadi kesalahan, silakan reload');
                },
            });
        })
    });
</script>

@include('core._script-delete')
@endpush

@section('vendor-style')
<link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/toastr.min.css')) }}">
@endsection
@section('page-style')
<link rel="stylesheet" href="{{ asset(mix('css/base/plugins/extensions/ext-component-toastr.css')) }}">
@endsection

@section('vendor-script')
<script src="{{ asset(mix('vendors/js/extensions/toastr.min.js')) }}"></script>
@endsection
