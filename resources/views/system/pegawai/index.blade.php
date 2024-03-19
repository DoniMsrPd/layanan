@extends('layouts/contentLayoutMaster')

{{-- @section('title', 'Users') --}}

@section('content-button')
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <p class="card-text float-left relative top-8">
            Daftar Pegawai
            @can('user.update')
            <a class="btn btn-sm btn-success" style=" float: right;" href="{{ route('core.pegawai.create') }}">Tambah</a>
            @endcan
        </p>

        <div class="table-responsive">
            <table class="table table-striped table-lightfont" id="pegawai-tbl">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIP</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>User Roles</th>
                        @canany(['user.update', 'user.read-scope'])
                            <th>Role</th>
                            @can('user.update')
                                <th>Aksi</th>
                            @endcan
                        @endcan
                    </tr>
                </thead>
            </table>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function() {
    var table = $('#pegawai-tbl').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('core.userdatatables') !!}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'NIP', name: 'NIP' },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'roles', name: 'roles',searchable:false, },
            @canany(['user.update', 'user.read-scope'])
                {
                    data: 'roles',
                    name: 'roles' ,
                    render: function(data, type, row, meta) {
                        return `<a class="mb-2 mr-2 btn btn-sm btn-warning" title="Edit Role" title-pos="up" href="{{ url('/core/pegawai/assignrole')}}/${row.id}"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings">
              <circle cx="12" cy="12" r="3"></circle>
              <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z">
              </path>
            </svg></a>`
                    }
                },
                @can('user.update')
                    { data: 'action', name: 'action',searchable:false, },
                @endcan
            @endcanany
        ],
    });
    table.on( 'click', 'button', function () {
         var data = table.row( $(this).parents('tr') ).data();
         window.location.href = "{{ url('core/pegawai/assignrole')}}/"+data.id;
    } );

});
</script>

@include('system.layouts._delete')

@endpush
