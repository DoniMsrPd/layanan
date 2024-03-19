@push('scripts')
<style>
    table#kategori-dt tbody td {
        padding: 2px
    }
</style>
<div class="modal fade" id="kategori-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Daftar Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- <span id="type"></span> -->
                <table class="table table-bordered table-lg table-v2" id="kategori-dt" width="100%">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="10%">Nama</th>
                            <th>Keterangan</th>
                            <th></th>
                            <th width="5%"></th>
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
        var tableKategori = $('#kategori-dt').DataTable({
            processing: true,
            serverSide: true,
            deferLoading: 0,
            ajax: {
                method: 'POST',
                url : "{!! route('setting.kategori.datatables') !!}",
            },
            columns: [
                {
                    data: 'Id',
                    name: 'Id',
                    className: "text-center" ,
                    width:'5%',
                    visible:false
                },
                {
                    width:'5%',
                    data: 'Nama',
                    name: 'Nama',
                    visible:false
                },
                {
                    data: 'Keterangan',
                    name: 'Keterangan',
                    visible:false
                },
                {
                    data: 'mobile',
                    name: 'mobile',
                    visible:false
                },
                {
                    data: 'pilih',
                    name: 'pilih' ,
                    searchable:false,
                    orderable:false,
                    className: "text-center" ,
                    width:'5%',
                    visible:false
                }
            ],
            rowCallback: function( row, data, index ) {
                var status = 'Not exist';

                if(typeof kategori !== 'undefined'){
                    for(var i=0; i < kategori.length; i++){
                        var name = kategori[i];
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
        tableKategori.on('draw.dt', function () {
            var info = tableKategori.page.info();
            tableKategori.column(0, { search: 'applied', order: 'applied', page: 'applied' }).nodes().each(function (cell, i) {
                cell.innerHTML = i + 1 + info.start;
            });
        });

        var dataArr = [];
        @if (isMobile())
            tableKategori.column(0).visible(false);
            tableKategori.column(1).visible(false);
            tableKategori.column(2).visible(false);
            tableKategori.column(3).visible(true);
            tableKategori.column(4).visible(false);

        @else

            tableKategori.column(0).visible(true);
            tableKategori.column(1).visible(true);
            tableKategori.column(2).visible(true);
            tableKategori.column(3).visible(false);
            tableKategori.column(4).visible(true);
        @endif
    });
</script>

@endpush