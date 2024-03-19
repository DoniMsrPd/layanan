@push('scripts')
<style>
    table#group-solver-dt tbody td {
        padding: 2px
    }
</style>
<div class="modal fade" id="group-solver-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Daftar Group Solver</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- <span id="type"></span> -->
                <table class="table table-bordered table-lg table-v2 table-striped" id="group-solver-dt" width="100%">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="10%">Kode</th>
                            <th>Nama</th>
                            <th width="5%"></th>
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
        var tableGroupSolver = $('#group-solver-dt').DataTable({
            processing: true,
            serverSide: true,
            deferLoading: 0,
            ajax: {
                method: 'POST',
                url : "{!! route('setting.group-solver.datatables') !!}",
            },
            columns: [
                {
                    data: 'Id',
                    name: 'Id',
                    className: "text-center" ,
                    width:'5%',
                },
                {
                    width:'5%',
                    data: 'Kode',
                    name: 'Kode'
                },
                {
                    data: 'Nama',
                    name: 'Nama'
                },
                {
                    data: 'pilih',
                    name: 'pilih' ,
                    searchable:false,
                    orderable:false,
                    className: "text-center" ,
                    width:'5%'
                },
                {
                    data: 'mobile',
                    name: 'mobile'
                },
            ],
            rowCallback: function( row, data, index ) {
                var status = 'Not exist';

                if(typeof groupSolver !== 'undefined'){
                    for(var i=0; i < groupSolver.length; i++){
                        var name = groupSolver[i];
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
        tableGroupSolver.on('draw.dt', function () {
            var info = tableGroupSolver.page.info();
            tableGroupSolver.column(0, { search: 'applied', order: 'applied', page: 'applied' }).nodes().each(function (cell, i) {
                cell.innerHTML = i + 1 + info.start;
            });
        });

        var dataArr = [];
        @if (isMobile())
            tableGroupSolver.column(0).visible(false);
            tableGroupSolver.column(1).visible(false);
            tableGroupSolver.column(2).visible(false);
            tableGroupSolver.column(3).visible(false);
            tableGroupSolver.column(4).visible(true);
        @else
            tableGroupSolver.column(0).visible(true);
            tableGroupSolver.column(1).visible(true);
            tableGroupSolver.column(2).visible(true);
            tableGroupSolver.column(3).visible(true);
            tableGroupSolver.column(4).visible(false);
        @endif
    });
</script>

@endpush