@extends('core.layouts.master')

@section('content')

<div class="element-wrapper">
    <div class="element-box">
        <h5 class="form-header">TYPE ASET {{ $data->jenisAset->Nama }}
            <a class="btn btn-primary btn-sm" href="javascript:history.back()"
                style=" float: right;">Kembali</a>
            @can('type-aset.create')
                <a class="btn btn-sm btn-success" style=" float: right;" href="{{ route('setting.type-aset.create',['jenis_aset'=>$data->jenisAset->Id]) }}">Tambah</a>
            @endcan
        </h5>
        <div class="row">
            <div class="col-md-12">

                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="table">
                        <thead>
                            <tr>
                                <th width="12%">No</th>
                                <th>Type Aset</th>
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
                url : "{!! route('setting.type-aset.datatables') !!}?jenisAsetId={{ $data->jenisAset->Id }}&jenisAsetIdInc={{ $data->jenisAset->IdInc }}",
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
                { data: 'action', name: 'action' , searchable:false, orderable:false, className: "text-center" ,width:'15%'}
            ]
        });
        table.on('draw.dt', function () {
            var info = table.page.info();
            table.column(0, { search: 'applied', order: 'applied', page: 'applied' }).nodes().each(function (cell, i) {
                cell.innerHTML = i + 1 + info.start;
            });
        });


    })

</script>

@include('core._script-delete')
@endpush