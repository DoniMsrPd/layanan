@push('scripts')
<style>
    table#solver-dt tbody td {
        padding: 2px
    }
</style>
<div class="modal fade" id="solver-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Daftar Solver</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-md-12">
                        @if (kdUnitOrgOwner() != '100205000000' && auth()->user()->hasRole('Solver', 'Pejabat Struktural'))
                        @elseif (kdUnitOrgOwner() == '100205000000')
                        <form class="form-filterss" method="GET">
                            <div class="m-checkbox-list">
                                <label class="m-checkbox" style="position: relative; top: 45px; left: 190px; z-index: 11111; width: 200px; float:left;">
                                    <input type="checkbox" name="excludeGroup" id="excludeGroup" value="1" >
                                    Solver diluar Group
                                </label>
                            </div>
                        </form>
                        @else
                        @endif
                    </div>
                </div>
                <table class="table table-bordered table-lg table-v2 table-striped" id="solver-dt" width="100%">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama</th>
                            <th>Unit Org</th>
                            <th>Unit Org Induk</th>
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
        var tableSolver = $('#solver-dt').DataTable({
            processing: true,
            serverSide: true,
            deferLoading: 0,
            ajax: {
                method: 'POST',
                url : "{!! route('setting.group-solver.datatables') !!}",
            },
            columns: [
                {
                    data: 'Nip',
                    name: 'Nip',
                    className: "text-center" ,
                    width:'5%',
                    searchable:false,
                },
                {
                    data: 'NmPeg',
                    name: 'NmPeg' ,
                    searchable:false,
                },
                {
                    data: 'NmUnitOrg',
                    name: 'NmUnitOrg' ,
                    searchable:false,
                },
                {
                    data: 'NmUnitOrgInduk',
                    name: 'NmUnitOrgInduk' ,
                    searchable:false,
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
                    name: 'mobile' ,
                },
            ],
            rowCallback: function( row, data, index ) {
                var status = 'Not exist';

                if(typeof solver !== 'undefined'){
                    for(var i=0; i < solver.length; i++){
                        var name = solver[i];
                        if(name == data.Nip){
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
        tableSolver.on('draw.dt', function () {
            var info = tableSolver.page.info();
            tableSolver.column(0, { search: 'applied', order: 'applied', page: 'applied' }).nodes().each(function (cell, i) {
                cell.innerHTML = i + 1 + info.start;
            });
        });
        @if (isMobile())
            tableSolver.column(0).visible(false);
            tableSolver.column(1).visible(false);
            tableSolver.column(2).visible(false);
            tableSolver.column(3).visible(false);
            tableSolver.column(4).visible(false);
            tableSolver.column(5).visible(true);
        @else
            tableSolver.column(0).visible(true);
            tableSolver.column(1).visible(true);
            tableSolver.column(2).visible(true);
            tableSolver.column(3).visible(true);
            tableSolver.column(4).visible(true);
            tableSolver.column(5).visible(false);
        @endif
        $('#excludeGroup').on('change', function () {
            if($('#tableGroupSolver').length){
                groupSolver = [];
                solver = [];
            }
            $('#tableGroupSolver tbody tr').each(function() {
                groupSolver.push($(this).find(".groupSolver").html())
                groupSolver.join(',')
            });
            $('#tableSolver tbody tr').each(function() {
                solver.push($(this).find(".solver").html())
                solver.join(',')
            });
            excludeGroup = $('#excludeGroup').prop('checked')?1:0;
            $('#solver-dt').DataTable().ajax.url("{!! route('setting.solver.datatables') !!}?groupSolver="+groupSolver+'&excludeGroup='+excludeGroup+'&solver='+solver).load();
        })
    });
</script>

@endpush
