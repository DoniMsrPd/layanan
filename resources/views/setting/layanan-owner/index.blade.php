@extends('layouts/contentLayoutMaster')
@section('content')
<div class="card">
    <h5 class="card-header">
        LAYANAN OWNER
        @can('master-layanan-owner.create')
        <a class="btn btn-sm btn-success" style=" float: right;"
            href="{{ route('setting.layanan-owner.create') }}">Tambah</a>
        @endcan
    </h5>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-lightfont" id="table">
                <thead>
                    <tr>
                        <th width="12%">Kode Unit Org Owner</th>
                        <th>Nama Unit Org Owner</th>
                        <th>Icon</th>
                        <th>Tanggal End</th>
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
                url : "{!! route('setting.layanan-owner.datatables') !!}",
            },
            columns: [
                {
                    data: 'KdUnitOrgOwnerLayanan',
                    name: 'KdUnitOrgOwnerLayanan',
                    className: "text-left" ,
                    width:'10%',
                    render: function(data, type, row) {
                        if (data) {
                            return `<a href="/setting/ref-status-layanan/${data}">${data}</a>`
                        } else{
                            return '';
                        }
                    },
                },
                {
                    data: 'NmUnitOrgOwnerLayanan',
                    name: 'NmUnitOrgOwnerLayanan',
                },
                // { data: 'Id', name: 'Id' },
                {   data: 'PathIcon',
                    name: 'PathIcon' ,
                    render: function(data, type, row, meta) {
                        if (row.PathIcon) {
                            return `<img src="${row.PathIcon}" width="20px" width="50px">`
                        } else{
                            return '';
                        }
                    }},
                {
                    data: 'TglEnd',
                    render: function(data, type, row, meta) {
                        return `${moment(row.TglEnd). format('DD/MM/YYYY')}`
                    }
                },
                { data: 'action', name: 'action' , searchable:false, orderable:false, className: "text-center" ,width:'15%'},
                {
                    data: 'KdUnitOrgOwnerLayanan',
                    name: 'KdUnitOrgOwnerLayanan',
                    visible:false
                },
            ],
            order: [[0, 'asc']]
        });
    })
</script>

@include('core._script-delete')
@endpush

@section('vendor-script')
<script src="{{ asset(mix('vendors/js/extensions/moment.min.js')) }}"></script>
@endsection
