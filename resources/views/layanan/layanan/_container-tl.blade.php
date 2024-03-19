<div class="col-md-12 col-12">
    <div class="card">
        <div class="card-body">

            <h4 class="form-header tlcontent" @if (request()->merge) style="display:none" @endif>Tindak Lanjut
                <span class="btn-group" role="group" style=" float: right;">
                    @if (!$data->isShow && in_array($data->layanan->StatusLayanan, [4, 5]) && !$data->isOperator)
                    @elseif (auth()->user()->hasRole(['Admin Proses Bisnis', 'Admin Probis Layanan', 'SuperUser']))
                        <a class="btn btn-primary btn-sm add-tl" data-url="/layanan-tl" data-method="POST"
                            style=" float: right;color:white"> + Tindak Lanjut</a>
                    @elseif(
                        $data->layanan->StatusLayanan == 4 &&
                            $data->layanan->CreatedBy == auth()->user()->NIP &&
                            pegawaiBiasa() &&
                            $data->isShow)
                        <a class="btn btn-warning btn-sm add-tl" data-url="/layanan-tl" data-method="POST"
                            style=" float: right;color:black"> + Informasi</a>
                    @else
                        @if (!($data->isOperator && !$data->isSolver))
                            @if (
                                (auth()->user()->can('layanan.tl.create-all') ||
                                    in_array(auth()->user()->NIP, $data->nipSolver) ||
                                    (auth()->user()->NIP == $data->layanan->NipLayanan && $data->isShow)) &&
                                    !$data->layanan->DeletedAt &&
                                    !in_array($data->layanan->StatusLayanan, [5]))
                                <a class="btn btn-primary btn-sm add-tl" data-url="/layanan-tl" data-method="POST"
                                    style=" float: right;color:white"> + Tindak Lanjut</a>
                            @elseif(pegawaiBiasa() && !$data->layanan->DeletedAt && $data->isShow)
                                <a class="btn btn-warning btn-sm add-tl" data-url="/layanan-tl" data-method="POST"
                                    style=" float: right;color:black"> + Informasi</a>
                            @endif
                        @endif
                    @endif
                </span>
            </h4>
        </div>
        <div class="card-content">
            <div class="card-body">

                <div class="tab-content tlcontent" id="log-content">
                    <div class="m-loader m-loader--brand" style="width: 30px; display: inline-block;"></div> Loading . .
                    .
                </div>
                <div class="tracker-editor" style="display: none">
                    <h5 class="mb-4 pb-1"><strong class="title-form-tl">Tambah Tindak Lanjut</strong></h5>
                    @include('layanan.layanan._form-tl')
                </div>
            </div>
        </div>
    </div>
</div>
