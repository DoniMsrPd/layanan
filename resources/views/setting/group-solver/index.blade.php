@extends('layouts/contentLayoutMaster')

@section('content')
<div class="card">
    <h5 class="card-header">GROUP SOLVER

        @can('group-solver.create')
        <a class="btn btn-sm btn-success " style=" float: right;"
            href="{{ route('setting.group-solver.create') }}">Tambah</a>
        @endcan
    </h5>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-lightfont" id="table">
                <thead>
                    <tr>
                        <th width="12%">No</th>
                        <th>Unit Org Owner</th>
                        <th>Kode</th>
                        <th>Nama Unit Org</th>
                        <th>Kode Unit Org</th>
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
                url : "{!! route('setting.group-solver.datatables') !!}",
            },
            columns: [
                {
                    data: 'Id',
                    className: "text-center" ,
                    width:'10%',
                },
                {
                    data: 'owner.NmUnitOrgOwnerLayanan',
                    name: 'owner.NmUnitOrgOwnerLayanan'
                },
                { data: 'Kode', name: 'Kode' },
                {
                    data: 'Nama',
                    name: 'Nama',
                    render: function(data, type, row, meta) {
                        return `<a href="/setting/group-solver/${row.Id}">${data}`
                    }
                },
                {
                    data: 'Id',
                    name: 'Id'
                },
                { data: 'action', name: 'action' , searchable:false, orderable:false, className: "text-center" ,width:'15%'},
                {
                    data: 'KdUnitOrgOwnerLayanan',
                    name: 'KdUnitOrgOwnerLayanan',
                    visible:false
                },
            ],
            order: [[6, 'asc']]
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
@section('vendor-style')
<link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/toastr.min.css')) }}">
@endsection
@section('page-style')
<link rel="stylesheet" href="{{ asset(mix('css/base/plugins/extensions/ext-component-toastr.css')) }}">
@endsection

@section('vendor-script')
<script src="{{ asset(mix('vendors/js/extensions/toastr.min.js')) }}"></script>
@endsection