@extends('core.layouts.master')

@section('content')
<div class="element-wrapper">
    <div class="element-box">

        <h5 class="form-header">MASTER TEMATIK
            @can('master-tematik.create')
                <a class="btn btn-sm btn-success" style=" float: right;" href="{{ route('setting.master-tematik.create') }}">Tambah</a>
            @endcan
        </h5>
            <div class="table-responsive">
                <table class="table table-striped table-lightfont" id="table">
                    <thead>
                        <tr>
                            <th width="12%">No</th>
                            <th>Judul Tematik Layanan</th>
                            <th>Deskripsi</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
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
            paging: false,
            ajax: {
                method: 'POST',
                url : "{!! route('setting.master-tematik.datatables') !!}",
            },
            columns: [
                {
                    data: 'Id',
                    className: "text-center" ,
                    width:'10%',
                },
                // { data: 'Id', name: 'Id' },
                {
                    data: 'Tema',
                    name: 'Tema'
                },
                {
                    data: 'Keterangan',
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