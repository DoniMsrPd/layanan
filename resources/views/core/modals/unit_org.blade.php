@push('scripts')
<style>

    table#table-org tbody td{
        padding: 2px
    }
</style>
<input type="hidden" id="mode">
<div class="modal fade" id="unit-org-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-lgg" style=" width:1300px;"role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Daftar Unit Organisasi</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <br>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-lg table-v2 table-striped" id="table-org" width="100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Unit Org</th>
                                <th>Nama Unit Org</th>
                                <th>Nama Unit Org Induk</th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
<script>
    var unitOrgSelected;
    var tableOrg;
    $(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        tableOrg = $('#table-org').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            deferLoading: 0,
            ajax: {
                method: 'POST',
                url : "{{  url('/core/spgunitorg/datatables') }}?",
            },
            columns: [
                { data: 'KdUnitOrg', name: 'KdUnitOrg', className: "text-center"},
                { data: 'KdUnitOrg', name: 'KdUnitOrg'},
                { data: 'NmUnitOrg', name: 'NmUnitOrg'},
                { data: 'NmUnitOrgInduk', name: 'NmUnitOrgInduk'},
                { data: 'pilih', name: 'pilih', className: "text-center"}
            ],
            aLengthMenu: [[10,20, 50, 75, -1], [10,20, 50, 75, "Semua"]],
            iDisplayLength: 10,
            rowCallback: function( row, data, index ) {

                var status = 'Not exist';

                if(typeof unitOrgSelected !== 'undefined'){
                    for(var i=0; i < unitOrgSelected.length; i++){
                        var name = unitOrgSelected[i];
                        if(name == data.KdUnitOrg){
                            status = 'Exist';
                            $('td', row).closest('tr').css('display', 'none');
                            $('td', row).find('.pick').prop('checked',true);
                            $('td', row).find('button').attr('disabled',true);
                            $('td', row).find('button').css('cursor','no-drop');
                            $('#checkall').prop('checked',false);
                            break;
                        }
                    }
                }
            }
        });
        tableOrg.on('draw.dt', function () {
            var info = tableOrg.page.info();
            tableOrg.column(0, { search: 'applied', order: 'applied', page: 'applied' }).nodes().each(function (cell, i) {
                cell.innerHTML = i + 1 + info.start;
            });
        });
    });

    $(document).on("click",'.lookup-unit-org',function () {
        (typeof table !== 'undefined') ? unitOrgSelected = table.rows().ids().toArray():'';
        ($('#unitOrgSelected').length ==1) ? unitOrgSelected = $('#unitOrgSelected').val().split(","):'';
        $('#table-org').DataTable().ajax.url("{{  url('/core/spgunitorg/datatables') }}").load();
        $('#unit-org-modal').modal('toggle');
    })
</script>
@endpush
