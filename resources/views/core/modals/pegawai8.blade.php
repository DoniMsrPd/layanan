@push('scripts')
<style>
    table#pegawai-dt8 tbody td {
        padding: 2px
    }
</style>
<div class="modal fade" id="pegawai-modal8" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Daftar Pegawai</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- <span id="type"></span> -->
                <table class="table table-bordered table-lg table-v2" id="pegawai-dt8" width="100%">
                    <thead>
                        <tr>
                            <!-- <th>No</th> -->
                            <!-- <th>NIP</th> -->
                            <th>Nama</th>
                            <th>Unit Org</th>
                            <th>Unit Org Induk</th>
                            <th>SNip</th>
                            <th>SNama</th>
                            <th></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    var NipSelected;
    $(function() {
        var table1 = $('#pegawai-dt8').DataTable({
            processing: true,
            serverSide: true,
            deferLoading: 0,
            ajax: '{!! route('core.pegawai.datatables') !!}',
            columns: [
                { data: 'NIPNM_PEG', name: 'NIPNM_PEG' },
                { data: 'NmUnitOrg', name: 'NmUnitOrg' },
                { data: 'NmUnitOrgInduk', name: 'NmUnitOrgInduk' },
                //untuk pencarian
                { data: 'Nip', name: 'Nip', visible: false},
                { data: 'NmPeg', name: 'NmPeg', visible: false},
                { data: 'pilih8', name: 'pilih8' , searchable:false, orderable:false, className: "text-center" }
            ],
            rowCallback: function( row, data, index ) {
                var status = 'Not exist';

                if(typeof NipSelected !== 'undefined'){
                    for(var i=0; i < NipSelected.length; i++){
                        var name = NipSelected[i];
                        if(name == data.Nip){
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

        var dataArr = [];

        $(document).on("click",'.lookup-pegawai8',function () {
            $('#pegawai-dt8').DataTable().ajax.url("{!! route('core.pegawai.datatables') !!}").load();
            $('#pegawai-modal8').modal('toggle');
            $('#modalBaPeminjaman').modal('toggle');
        });
    });
</script>

@endpush