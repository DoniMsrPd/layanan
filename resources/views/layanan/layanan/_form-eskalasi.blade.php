<style>
    table#tableGroupSolver th {
        border-top: 0px !important
    }

    table#tableGroupSolver td {
        border-top: 0px !important;
        padding: 2px
    }

    table#tableSolver td {
        border-top: 0px !important;
        padding: 2px
    }

    table#tableSolver th {
        border-top: 0px !important
    }
</style>
<div class="card">
    <div class="card-header">
        <h4 class="card-title">Eskalasi</h4>
    </div>
    <div class="card-content">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group row">
                        <label class="col-form-label col-sm-2" for=""> Group Solver</label>
                        <div class=" col-sm-10">
                            <table class="table table-borderless table-hover" id="tableGroupSolver">
                                <thead style="border-bottom: 1px dashed #ebedf2;">
                                    <tr>
                                        <th width="4%" style="font-size:0.7rem">No</th>
                                        <th style="font-size:0.7rem">Kode</th>
                                        <th width="16%" style="text-align: center;">
                                            @if(!$data->layanan->DeletedAt &&
                                            (request()->user()->can('layanan.eskalasi.all') ||
                                            auth()->user()->hasRole('Pejabat Struktural')))
                                            <button style="padding:3px 3px;margin:0px" type="button" data-toggle="modal"
                                                class="btn btn-success btn-sm lookup-group-solver">
                                                <i data-feather='plus' title="Tambah Group Solver"></i>
                                            </button>
                                            @endif
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    @if (!($data->layanan->KdUnitOrgOwnerLayanan == '103403000000' && auth()->user()->hasRole('Operator')))
                    <div class="form-group row">
                        @if (isMobile())
                        <div class=" col-sm-10">

                            <div class="table-responsive text-nowrap">
                                <table class="table" id="tableSolver">
                                    <thead class="table-light">
                                        <tr>
                                            <th>
                                                Tim Solver

                                                @if(!$data->layanan->DeletedAt &&
                                                (request()->user()->can('layanan.eskalasi.all') ||
                                                auth()->user()->hasRole('Pejabat Struktural')))
                                                <button style="padding:3px 3px;margin:0px;display: none;float: right" type="button"
                                                    data-toggle="modal" class="btn btn-success btn-sm lookup-solver">
                                                    <i data-feather="plus" title="Tambah Solver"></i>
                                                </button>
                                                @endif
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @else
                        <label class="col-form-label col-sm-2" for=""> Tim Solver</label>
                        <div class=" col-sm-10">

                            <div class="table-responsive text-nowrap">
                                <table class="table" id="tableSolver">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="4%" style="font-size:0.7rem">No</th>
                                            <th style="font-size:0.7rem" width="15%">Nip</th>
                                            <th style="font-size:0.7rem">Nama</th>
                                            <th>Catatan</th>
                                            <th width="16%" style="text-align: center;">
                                                @if(!$data->layanan->DeletedAt &&
                                                (request()->user()->can('layanan.eskalasi.all') ||
                                                auth()->user()->hasRole('Pejabat Struktural')))
                                                <button style="padding:3px 3px;margin:0px;display: none" type="button"
                                                    data-toggle="modal" class="btn btn-success btn-sm lookup-solver">
                                                    <i data-feather="plus" title="Tambah Solver"></i>
                                                </button>
                                                @endif
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
