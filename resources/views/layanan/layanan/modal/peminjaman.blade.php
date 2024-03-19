@push('scripts')
<style>
    table#peminjaman-dt tbody td {
        padding: 2px
    }
</style>
<div class="modal fade" id="peminjaman-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Daftar Aset TI</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- <span id="type"></span> -->
                <table class="table table-bordered table-lg table-v2 table-striped" id="peminjaman-dt" width="100%">
                    <thead>
                        <tr>
                            <th width="5%">>No</th>
                            <th width="5%">>No IKN</th>
                            <th width="70%">Aset</th>
                            <th width="10%">Pengguna</th>
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
        var table = $('#peminjaman-dt').DataTable({
            processing: true,
            serverSide: true,
            deferLoading: 0,
            ajax: {
                method: 'POST',
                url : "{!! route('setting.aset.datatables') !!}?peminjaman=1",
            },
            columns: [
                {
                    data: 'Id',
                    className: "text-center" ,
                    width:'5%',
                    searchable:false
                },
                // { data: 'Id', name: 'Id' },
                {
                    width:'5%',
                    data: 'NoIkn1',
                    name: 'NoIkn1' ,
                    render: function(data, type, row, meta) {
                        return `${row.NoIkn1??row.no_ikn1} <br> ${row.NoIkn2??row.no_ikn2}`
                    },
                    searchable:false
                },
                {
                    data: 'aset',
                    width:'70%',
                    searchable:false
                },
                {
                    width:'10%',
                    data: 'NipPengguna',
                    render: function(data, type, row, meta) {
                        return `${row.NipPengguna??'-'} <br> ${row.pengguna?.NmPeg??'-'}`
                    }  ,
                    searchable:false
                },
                { data: 'pilihPeminjaman', name: 'pilihPeminjaman' , searchable:false, orderable:false, className: "text-center" ,width:'10%'}
            ],
            rowCallback: function( row, data, index ) {
                var status = 'Not exist';

                if(typeof peminjaman !== 'undefined'){
                    for(var i=0; i < peminjaman.length; i++){
                        var name = peminjaman[i];
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
    });
</script>

@endpush