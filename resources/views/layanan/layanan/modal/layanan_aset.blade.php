@push('scripts')
<style>
    table#layanan-aset-dt tbody td {
        padding: 2px
    }
</style>
<div class="modal fade" id="layanan-aset-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Daftar Aset TI</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- <span id="type"></span> -->
                <table class="table table-bordered table-lg table-v2 table-striped" id="layanan-aset-dt" width="100%">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>No IKN</th>
                            <th width="12%">Serial Number</th>
                            <th>Aset</th>
                            <th width="10%">Aksi</th>
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
        var table = $('#layanan-aset-dt').DataTable({
            processing: true,
            serverSide: true,
            deferLoading: 0,
            ajax: {
                method: 'POST',
                url : "{!! route('layanan.layanan-aset.datatables') !!}",
            },
            columns: [
                {
                    data: 'Id',
                    className: "text-center" ,
                    width:'5%',
                },
                {
                    data: 'NomorIKN',
                    name: 'NomorIKN',
                    width:'20%',
                },
                {
                    data: 'SN',
                    name: 'SN'
                },
                {
                    data: 'NamaBarang',
                    className: "text-right",
                    width:'10%',
                },
                {
                    data: 'pilih',
                    name: 'pilih' ,
                    searchable:false,
                    orderable:false,
                    className: "text-center" ,
                    width:'5%'
                }
            ],
        });
        table.on('draw.dt', function () {
            var info = table.page.info();
            table.column(0, { search: 'applied', order: 'applied', page: 'applied' }).nodes().each(function (cell, i) {
                cell.innerHTML = i + 1 + info.start;
            });
        });

        var dataArr = [];
    });
</script>

@endpush