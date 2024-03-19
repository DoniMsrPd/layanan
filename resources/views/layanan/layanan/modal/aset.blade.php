@push('scripts')
<style>
    table#aset-dt tbody td {
        padding: 2px
    }
</style>
<div class="modal fade" id="aset-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Daftar Aset TI</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <a class="btn btn-primary btn-sm tambahAset text-white" style=" float: right;">Tambah Aset</a>
                <div class="row">
                    <div class="col-md-12">

                        <form class="form-filterss" method="GET">
                            <div class="m-checkbox-list">
                                <label class="m-checkbox" style="position: relative; top: 45px; left: 190px; z-index: 11111; width: 200px; float:left;">
                                    <input type="checkbox" name="asetSMA" id="asetSMA" value="1" >
                                    Aset SMA
                                </label>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- <span id="type"></span> -->
                <table class="table table-bordered table-lg table-v2 table-striped" id="aset-dt" width="100%">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="5%">No IKN</th>
                            <th width="5%">Serial Number</th>
                            <th width="65%">Aset</th>
                            <th width="10%">Pengguna</th>
                            <th width="10%">Aksi</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    var tableAset ;

    function loadDatatableAset(column){

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        tableAset = $('#aset-dt').DataTable({
            processing: true,
            serverSide: true,
            deferLoading: 0,
            ajax: {
                method: 'POST',
                url : "{!! route('setting.aset.datatables') !!}",
            },
            columns: column,
            rowCallback: function( row, data, index ) {
                var status = 'Not exist';

                if(typeof aset !== 'undefined'){
                    for(var i=0; i < aset.length; i++){
                        var name = aset[i];
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
        tableAset.on('draw.dt', function () {
            var info = tableAset.page.info();
            tableAset.column(0, { search: 'applied', order: 'applied', page: 'applied' }).nodes().each(function (cell, i) {
                cell.innerHTML = i + 1 + info.start;
            });
        });
    }
    $(function() {
        $('.tambahAset').on('click', function () {
            $('#modalFormAsetTI').modal('toggle');
            $('#aset-modal').modal('toggle');
        })

        $('#asetSMA').on('change', function () {
            aset = [];
            $('#tableAset tbody tr').each(function() {
                aset.push($(this).find(".aset").html())
                aset.join(',')
            });
            asetSMA = $('#asetSMA').prop('checked')?1:0;
            if(asetSMA){
                tableAset.destroy()
                column = [
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
                            data: 'sn',
                            width:'5%',
                            searchable:false
                        },
                        {
                            data: 'aset',
                            width:'65%',
                            searchable:false
                        },
                        {
                            width:'10%',
                            data: 'NipPengguna',
                            render: function(data, type, row, meta) {
                                return `${row.NipPengguna??(row.pengguna?.Nip ?? '-')} <br> ${row.pengguna?.NmPeg??'-'}`
                            }  ,
                            searchable:false
                        },
                        { data: 'pilih', name: 'pilih' , searchable:false, orderable:false, className: "text-center" ,width:'10%'},
                        {
                            data: 'pengguna.NmPeg',
                            render: function(data, type, row, meta) {
                                return `${row.pengguna?.NmPeg??'-'}`
                            }, searchable:true ,visible:false
                        },
                        {
                            data: 'pengguna.Nip',
                            render: function(data, type, row, meta) {
                                return `${row.pengguna?.Nip??'-'}`
                            }, searchable:true ,visible:false
                        },
                        { data: 'keterangan', name: 'keterangan',visible:false,searchable:true },
                        { data: 'no_ikn1', name: 'no_ikn1',visible:false,searchable:true },
                        { data: 'no_ikn2', name: 'no_ikn2',visible:false,searchable:true }
                ]
            } else {
                tableAset.destroy()
                column = [
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
                            data: 'sn',
                            width:'5%',
                            searchable:false
                        },
                        {
                            data: 'aset',
                            width:'65%',
                            searchable:false
                        },
                        {
                            width:'10%',
                            data: 'NipPengguna',
                            render: function(data, type, row, meta) {
                                return `${row.NipPengguna??(row.pengguna?.Nip ?? '-')} <br> ${row.pengguna?.NmPeg??'-'}`
                            }  ,
                            searchable:false
                        },
                        { data: 'pilih', name: 'pilih' , searchable:false, orderable:false, className: "text-center" ,width:'10%'},
                        {
                            data: 'pengguna.NmPeg',
                            render: function(data, type, row, meta) {
                                return `${row.pengguna?.NmPeg??'-'}`
                            }, searchable:true ,visible:false
                        },
                        { data: 'SerialNumber', name: 'SerialNumber',visible:false,searchable:true },
                        { data: 'NipPengguna', name: 'NipPengguna',visible:false,searchable:true },
                        { data: 'NoIkn1', name: 'NoIkn1',visible:false,searchable:true },
                        { data: 'NoIkn2', name: 'NoIkn2',visible:false,searchable:true }
                ]
            }
            loadDatatableAset(column);
            $('#aset-dt').DataTable().ajax.url("{!! route('setting.aset.datatables') !!}?asetSMA="+asetSMA).load();
        })
    });
</script>

@endpush