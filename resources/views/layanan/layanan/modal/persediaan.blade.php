@push('scripts')
<style>
    table#persediaan-dt tbody td {
        padding: 2px
    }
</style>
<div class="modal fade" id="persediaan-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Daftar Kode Persediaan</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- <span id="type"></span> -->
                <table class="table table-bordered table-lg table-v2 table-striped" id="persediaan-dt" width="100%">
                    <thead>
                        <tr>
                            <th width="12%">No</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Nama Barang Lengkap</th>
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
        var table = $('#persediaan-dt').DataTable({
            processing: true,
            serverSide: true,
            deferLoading: 0,
            ajax: {
                method: 'POST',
                url : "{!! route('setting.persediaan.datatables') !!}",
            },
            columns: [
                {
                    data: 'Id',
                    className: "text-center" ,
                    width:'5%',
                },
                {
                    data: 'KdBrg',
                    name: 'KdBrg',
                    width:'10%',
                },
                {
                    data: 'NmBrg',
                    name: 'NmBrg',
                    width:'20%',
                },
                {
                    data: 'NmBrgLengkap',
                    name: 'NmBrgLengkap'
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
            rowCallback: function( row, data, index ) {
                var status = 'Not exist';

                if(typeof persediaan !== 'undefined'){
                    for(var i=0; i < persediaan.length; i++){
                        var name = persediaan[i];
                        if(name == data.Id){
                            console.log(name + '  ---' +data.Id);
                            status = 'Exist';
                            $('td', row).closest('tr').css('background-color', '#f2dede');
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