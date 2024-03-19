@push('scripts')
<style>
    table#table-serviceCatalog tbody td {
        padding: 2px
    }
</style>
<div class="modal fade" id="serviceCatalog-Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Daftar Service Catalog</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- <span id="type"></span> -->
                <table class="table table-bordered table-lg table-v2 table-striped" id="table-serviceCatalog" width="100%">
                    <thead>
                        <tr>
                            <th width="12%">Kode ITSM</th>
                            <th>Nama ITSM</th>
                            <th width="10%">Aksi</th>
                            <th></th>
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
        var tableServiceCatalog = $('#table-serviceCatalog').DataTable({
            processing: true,
            serverSide: true,
            deferLoading: 0,
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
                        return `${data}`
                    }
                },
                // { data: 'Id', name: 'Id' },
                { data: 'Nama', name: 'Nama' },
                { data: 'pilih', name: 'pilih' , searchable:false, orderable:false, className: "text-center" ,width:'15%'},
                { data: 'mobile', name: 'mobile' },
            ],
        });

        var dataArr = [];
        @if (isMobile())
            tableServiceCatalog.column(0).visible(false);
            tableServiceCatalog.column(1).visible(false);
            tableServiceCatalog.column(2).visible(false);
            tableServiceCatalog.column(3).visible(true);

        @else
            tableServiceCatalog.column(0).visible(true);
            tableServiceCatalog.column(1).visible(true);
            tableServiceCatalog.column(2).visible(true);
            tableServiceCatalog.column(3).visible(false);
        @endif

        $(document).on("click",'.lookup-service-catalog',function () {
            $('#table-serviceCatalog').DataTable().ajax.url("{!! route('setting.service-catalog.datatables') !!}?aktif=1").load();
            $('#serviceCatalog-Modal').modal('toggle');
        });
    });
</script>

@endpush