@extends('layouts/contentLayoutMaster')
@section('content')

<div class="card">
    <h5 class="card-header">Template Penyelesaian

        @can('template-penyelesaian.create')
        <a class="btn btn-sm btn-success " style=" float: right;"
            href="{{ route('setting.template-penyelesaian.create') }}">Tambah</a>
        @endcan
    </h5>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-lightfont" id="table">
                <thead>
                    <tr>
                        <th width="12%">No</th>
                        <th>Nama</th>
                        <th>Template</th>
                        <th>Aksi</th>
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
    var table;
    $(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        table = $('#table').DataTable({
            processing: true,
            serverSide: true,
            paging: false,
            ajax: {
                method: 'POST',
                url : "{!! route('setting.template-penyelesaian.datatables') !!}",
            },
            columns: [
                {
                    data: 'Id',
                    className: "text-center" ,
                    width:'10%',
                },
                {
                    data: 'Nama',
                    name: 'Nama',
                },
                { data: 'Template', name: 'Template' },
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