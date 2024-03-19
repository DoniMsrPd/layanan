@push('scripts')
<style>
    table#layanan-dt tbody td {
        padding: 2px
    }
</style>
<div class="modal fade" id="layanan-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Daftar Layanan</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-lg table-v2 table-striped" id="layanan-dt" width="100%">
                    <thead>
                        <tr>
                            <th>No Tiket</th>
                            <th>Tanggal</th>
                            <th>Deskripsi</th>
                            <th>Status</th>
                            <th>Operator</th>
                            <th>Solver</th>
                            <th>Updated At</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var table = $('#layanan-dt').DataTable({
            processing: true,
            serverSide: true,
            deferLoading: 0,
            ajax: {
                method: 'POST',
                url : "{!! route('layanan.datatables') !!}",
            },
            columns: [
                // { data: 'Id', name: 'Id' },
                {
                    data: 'NoTiket',
                    name: 'NoTiket' ,
                    width:'5%',
                    render: function(data, type, row, meta) {
                        let url = `/layanan/${row.Id}/eskalasi?merge=1&layananBaru={{ $data->layanan->Id??'' }}`
                        return `<a href="${url}"> ${row.NoTiket??''}<br>${row.NoTicketRandom?.toUpperCase()} </a>`
                    }  ,
                },
                {
                    data: 'TglLayanan',
                    width:'4%',
                    searchable:false
                },
                {
                    data: 'PermintaanLayanan',
                    width:'35%',
                },
                {
                    data: 'status',
                    searchable:false,
                    render: function(data, type, row, meta) {
                        return `${row.status?.Nama??'-'}`
                    }  ,
                    width:'5%',
                },
                {
                    data: 'operator_open',
                    render: function(data, type, row, meta) {
                        return `${row.NipOperatorOpen??'-'} <br> ${row.operator_open?.NmPeg??'-'}`
                    }  ,
                    searchable:false,
                    width:'10%',
                },
                {
                    data: 'Id',
                    render: function(data, type, row, meta) {
                        return `<span style="color:blue">${row.AllGroupSolver??'-'} </span><br><span style="color:green"> ${row.AllSolver??'-'}</span>`
                    }  ,
                    searchable:false,
                    width:'10%',
                },
                {
                    data: 'UpdatedAt',
                    searchable:false,
                    width:'5%',
                },
                {
                    data: 'NmPeg',
                    visible:false,
                },
                {
                    data: 'NoTicketRandom',
                    visible:false,
                },
                {
                    data: 'NmUnitOrg',
                    visible:false,
                },
                {
                    data: 'NmUnitOrgInduk',
                    visible:false,
                },
            ],
        });

        var dataArr = [];
    });
</script>

@endpush