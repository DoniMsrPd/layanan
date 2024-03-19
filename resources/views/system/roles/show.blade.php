@extends('layouts/contentLayoutMaster')

@section('content')

<div class="card">
    <div class="card-body">
        <h5 class="form-header">Detail Role : {{ $role->name }}
            <a class="btn btn-primary btn-sm" href="{{ url('/core/role') }}" style=" float: right;">Kembali</a>
            @can('role.read')
                <button style="float: right;margin-right:5px" title="Tambah Pegawai" type="button" data-toggle="modal"
                    data-target="#pegawai-modal" class="btn btn-success btn-sm lookup-pegawai">
                    Tambah
                </button>
            @endcan
        </h5>
        <table class="table table-striped- table-bordered table-hover table-checkable" id="userRoleTbl">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>NIP</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th width="13%"></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
@endsection
@include('core.modals.pegawai')

@push('scripts')
<script>
    var tbl;
    $(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        tbl = $('#userRoleTbl').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('core.role.datatablesPegawai') !!}?role={{$role->name}}',
            columns: [
                { data: 'id', name: 'id' },
                { data: 'NIP', name: 'NIP' },
                { data: 'pegawai.NmPeg', name: 'pegawai.NmPeg' },
                { data: 'pegawai.Email', name: 'pegawai.Email' },
                { data: 'action', name: 'action' , searchable:false, orderable:false, className: "text-center" }
            ]
        });
        // console.log(nipallpic);

        // $('#checkstaf').trigger('change');

        @cannot('role.read')
            var column = tbl.column(4);
            column.visible( ! column.visible() );
        @endcannot
    });
    $(document).on('click', '.pilih-pegawai', function (e) {
            nip = $(this).data('nip');
            var url = "{{route('core.role.tambah.user')}}";

            $.ajax({
                url: url,
                type: "POST",
                data: {
                    'nip': nip,
                    'role': '{{$role->name}}',
                    '_token': '{{ csrf_token() }}'
                },
                success: function(data) {
                    Swal.fire({
                        // title: 'Anda Menghapus user ?',
                        text: data.text,
                        type: data.type,
                    });
                    location.reload();

                }

            });
            // $('#pegawai-modal').modal('hide');
    });
$(document).on('click', '#deleteRole', function() {
        var role = '{{$role->name}}';
        var nip = $(this).data('nip');
        var name = $(this).data('nama');
        var url = "{{route('core.role.hapus.user')}}";
        // alert(role_id+' '+model_id);
        Swal.fire({
            title: 'Anda Menghapus user ?',
            text: "Konfirmasi Penghapusan user " + name,
            type: 'warning',
            showCancelButton: true,
            cancelButtonColor: '#d33',
            confirmButtonColor: '#3085d6',
            confirmButtonText: '<i class="fa fa-check-circle"></i> Ya, Hapus ini',
            cancelButtonText: '<i class="fa fa-times-circle"></i> Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        'role': role,
                        'nip': nip,
                        '_token': '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        Swal.fire({
                            // title: 'Anda Menghapus user ?',
                            text: data.text,
                            type: data.type,
                        });
                        location.reload();

                    }

                });
            }
        })

    })
    $( document ).ready(function() {
        $('#sideMenu').addClass('compact-side-menu');
    });
</script>
@endpush
