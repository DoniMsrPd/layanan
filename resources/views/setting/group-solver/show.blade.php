@extends('layouts/contentLayoutMaster')


@section('content')

<div class="card">
    <h5 class="card-header">Solver {{ $data->groupSolver->Nama }}
        <span class="btn-group" role="group" style=" float: right;">
        <a class="btn btn-primary btn-sm" href="{{route('setting.group-solver.index')}}"
            style=" float: right;">Kembali</a>
        @can('solver.create')
        <a class="btn btn-sm btn-success lookup-pegawai mr-3" style=" float: right;margin-left: 10px" href="#">Tambah</a>
        @endcan
        </span>
    </h5>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">

                <div class="table-responsive">
                    <table class="table table-bordered" id="table">
                        <thead>
                            <tr>
                                <th width="12%">No</th>
                                <th>Nama</th>
                                <th>Unit Org</th>
                                <th>Unit Org Induk</th>
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
@include('core.modals.pegawai')
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
            ajax: {
                method: 'POST',
                url : "{!! route('setting.solver.datatables') !!}?mstGroupSolverId={{ $data->groupSolver->Id }}",
            },
            columns: [
                {
                    data: 'Id',
                    className: "text-center" ,
                    width:'5%',
                },
                {
                    data: 'pegawai.NmPeg',
                    render: function(data, type, row, meta) {
                        return `${data} <br> ${row.Nip}`
                    }
                },
                {
                    data: 'pegawai.NmUnitOrg',
                    name: 'pegawai.NmUnitOrg',
                },
                {
                    data: 'pegawai.NmUnitOrgInduk',
                    name: 'pegawai.NmUnitOrgInduk',
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
        $(document).on("click",'.pilih-pegawai',function () {
            $(this).closest('tr').remove();
            nip = $(this).data('nip')
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '{{ route("setting.solver.store") }}',
                type: "POST",
                data: {
                    'mstGroupSolverId'    : '{{ $data->groupSolver->Id }}',
                    nip,
                    '_token'           : csrf_token
                },
                success: function (response) {
                    // $('#pegawai-modal').modal('toggle');
                    if(response.success){
                        $('#table').dataTable().api().ajax.reload();
                        toastr.clear()
                        toastr.success(response.message)
                    }
                },
                error : function () {
                    alert('Terjadi kesalahan, silakan reload');
                },
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