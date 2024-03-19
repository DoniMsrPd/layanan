@extends('core.layouts.master')

@section('content')

<div class="element-wrapper" style="padding-bottom: 20px;">
    <div class="element-box">
        <h5 class="form-header">SERVICE CATALOG <a class="btn btn-primary btn-sm" href="javascript:history.back()"
                style=" float: right;">Kembali</a></h5>
        <div class="row">
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width: 30%;">Kode ITSM</th>
                                <th>Peminjaman</th>
                                <th>Persediaan</th>
                                <th>Perbaikan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <strong>{{ $data->serviceCatalog->Kode }}</strong>
                                    <br>
                                    {{ $data->serviceCatalog->Nama }}
                                </td>
                                <td align="center">
                                    @if($data->serviceCatalog->IsPeminjaman==1)
                                    <i class="os-icon os-icon-checkmark text-success"></i>
                                    @else
                                    <i class="os-icon os-icon-close text-danger"></i>
                                    @endif
                                </td>
                                <td align="center">
                                    @if($data->serviceCatalog->IsPersediaan==1)
                                    <i class="os-icon os-icon-checkmark text-success"></i>
                                    @else
                                    <i class="os-icon os-icon-close text-danger"></i>
                                    @endif
                                </td>

                                <td align="center">
                                    @if($data->serviceCatalog->IsPeerbaikan==1)
                                    <i class="os-icon os-icon-checkmark text-success"></i>
                                    @else
                                    <i class="os-icon os-icon-close text-danger"></i>
                                    @endif
                                </td>

                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="element-wrapper" style="padding-bottom: 20px;">
    <div class="element-box">
        <h5 class="form-header">TEMATIK LAYANAN
            @can('service-catalog.create')
                <a class="btn btn-sm btn-success" href="#" style=" float: right;" data-target="#modalTematik" data-toggle="modal">Tambah</a>
            @endif
        </h5>
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
<div class="element-wrapper">
    <div class="element-box">
        <h5 class="form-header">SLA
            @can('service-catalog-sla.create')
                <a class="btn btn-sm btn-success" style=" float: right;" href="{{ route('setting.service-catalog-detail.create',['service_catalog'=>$data->serviceCatalog->Id]) }}">Tambah</a>
            @endcan
        </h5>
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
    aria-hidden="true" style="z-index: 1045;">
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
                                        @foreach ($data->mstTematik as $tematik)
                                        <option value="{{ $tematik->Id }}">{{ $tematik->Tema }}</option>
                                        @endforeach
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