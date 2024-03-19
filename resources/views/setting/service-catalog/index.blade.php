@extends('layouts/contentLayoutMaster')
@section('content')
<div class="card">
    <h5 class="card-header">
        SERVICE CATALOG
        @can('service-catalog.create')
        <a class="btn btn-sm btn-success" style=" float: right;"
            href="{{ route('setting.service-catalog.create') }}">Tambah</a>
        @endcan
    </h5>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-lightfont" id="table">
                <thead>
                    <tr>
                        <th width="12%">Kode ITSM</th>
                        <th>Unit Org Owner</th>
                        <th>Nama ITSM</th>
                        <th>Periode Aktif</th>
                        @if (substr($data->KdUnitOrgLayanan, 0, 6) == '100205')
                            <th>Peminjaman</th>
                            <th>Persediaan</th>
                            <th>Perbaikan</th>
                        @endif
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
                url : "{!! route('setting.service-catalog.datatables') !!}",
            },
            columns: [
                {
                    data: 'Kode',
                    name: 'Kode',
                    className: "text-left" ,
                    width:'10%',
                    render: function(data, type, row, meta) {
                        return `<a href="/setting/service-catalog/${row.Id}">${data}</a>`
                    }
                },
                {
                    data: 'owner.NmUnitOrgOwnerLayanan',
                    name: 'owner.NmUnitOrgOwnerLayanan'
                },
                // { data: 'Id', name: 'Id' },
                { data: 'Nama', name: 'Nama' },
                {
                    data: 'TglStart',
                    render: function(data, type, row, meta) {
                        return ` ${moment(row.TglStart). format('DD/MM/YYYY')} s.d ${moment(row.TglEnd). format('DD/MM/YYYY')}`
                    }
                },
                @if (substr($data->KdUnitOrgLayanan, 0, 6) == '100205')
                {
                    data: 'IsPeminjaman',
                    render: function(data) {
                        return `${data==1?'<i class="text-success"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check"><polyline points="20 6 9 17 4 12"></polyline></svg></i>':'<i class="text-danger"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></i>'}`
                    },
                    className: "text-center"
                },
                {
                    data: 'IsPersediaan',
                    render: function(data) {
                        return `${data==1?'<i class="text-success"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check"><polyline points="20 6 9 17 4 12"></polyline></svg></i>':'<i class="text-danger"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></i>'}`
                    },
                    className: "text-center"
                },
                {
                    data: 'IsPerbaikan',
                    render: function(data) {
                        return `${data==1?'<i class="text-success"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check"><polyline points="20 6 9 17 4 12"></polyline></svg></i>':'<i class="text-danger"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></i>'}`
                    },
                    className: "text-center"
                },
                @endif
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
