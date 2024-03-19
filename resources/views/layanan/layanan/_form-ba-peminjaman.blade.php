<div class="modal fade" id="modalBaPeminjaman" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header" id="modal-title-baPeminjaman">
                Generate BA Peminjaman
            </div>
            <div class="modal-body">
                <form method="POST" action="#" id="formPeminjaman" enctype="multipart/form-data">
                    <input type="hidden" id="id" name="id" value="">
                    <input type="hidden" id="jenisBAPeminjaman" name="jenisBAPeminjaman" value="">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group row mb-3">
                                <label class="col-form-label col-sm-3" for="">No BA</label>
                                <div class="col-md-9">
                                    <input class=" form-control" readonly name="NoBA"
                                        value="">
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label class="col-form-label col-sm-3" for="">Tgl BA</label>
                                <div class="col-md-4">
                                    <input type="date" class="form-control datepicker" name="TglBA"
                                        value="{{ date('Y-m-d') }}">
                                </div>
                            </div>
                            <div class="form-group row mb-1 ruangForm">
                                <label class="col-form-label col-sm-3" for="">Ruang</label>
                                <div class="col-md-9">
                                    <input class=" form-control" name="Ruang" value="" id="Ruang">
                                </div>
                            </div>
                            <div class="form-group row mb-1 mt-1">
                                <label class="col-form-label col-sm-3" for=""></label>
                                <div class="col-sm-6">
                                    <input type="checkbox" id="isPihakLuar" name="isPihakLuar"> Pihak Luar <br>
                                </div>
                            </div>
                            <div class="form-group row mb-1 pihak1luar">
                                <label class="col-form-label col-sm-3" for="">KTP Pihak 1</label>
                                <div class="col-md-9">
                                    <input class=" form-control" name="NipPihak1Luar" value="" id="NipPihak1Luar">
                                </div>
                            </div>
                            <div class="form-group row mb-1 pihak1luar">
                                <label class="col-form-label col-sm-3" for="">Nama Pihak 1</label>
                                <div class="col-md-9">
                                    <input class=" form-control" name="NmPihak1Luar" value="" id="NmPihak1Luar">
                                </div>
                            </div>
                            <div class="form-group row mb-1 pihak1luar">
                                <label class="col-form-label col-sm-3" for="">Instansi Pihak 1</label>
                                <div class="col-md-9">
                                    <input class=" form-control" name="KdUnitOrgPihak1Luar" value="" id="KdUnitOrgPihak1Luar">
                                </div>
                            </div>
                            <div class="form-group row mb-1" id="pihak1dalam">
                                <label class="col-form-label col-sm-3 pb-0" for="">Nama Pihak 1</label>
                                <div class="col-form-label col-md-9 pb-0">
                                    <div class="input-group">

                                        <input type="hidden" name="KdUnitOrgPihak1" value="">
                                        <input type="hidden" name="NmPihak1" value="">
                                        <input type="hidden" name="NipPihak1" value="">
                                        <input required style="cursor: pointer;" readonly
                                            class="form-control lookup-pegawai8" placeholder="" name="PegawaiPihak1"
                                            value="">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary lookup-pegawai8" type="button"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-1">
                                <label class="col-form-label col-sm-3 pb-0" for="">Nama Pihak 2</label>
                                <div class="col-form-label col-md-9 pb-0">
                                    <div class="input-group">

                                        <input type="hidden" name="KdUnitOrgPihak2" value="">
                                        <input type="hidden" name="NmUnitOrgPihak2" value="">
                                        <input type="hidden" name="NmJabatanPihak2" value="">
                                        <input type="hidden" name="NipPihak2" value="">
                                        <input required style="cursor: pointer;" readonly
                                            class="form-control lookup-pegawai6" placeholder="" name="PegawaiPihak2"
                                            value="">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary lookup-pegawai6" type="button"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg></button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row ">
                                <label class="col-form-label col-sm-3 pb-0" for="">Nama Pejabat Tanda Tangan</label>
                                <div class="col-form-label col-md-9 pb-0">
                                    <div class="input-group">
                                        <input type="hidden" name="NmJabatanPejabat" value="">
                                        <input type="hidden" name="NmPegTtdPejabat" value="">
                                        <input type="hidden" name="NipTtdPejabat" value="">
                                        <input required style="cursor: pointer;" readonly
                                            class="form-control lookup-pegawai7" placeholder="" name="Pejabat"
                                            value="">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary lookup-pegawai7" type="button"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">

                            <hr>
                            <table class="table table-borderless table-hover mb-1" id="AsetPeminjaman">
                                <thead>
                                    <tr>
                                        <th width="30%">No IKN</th>
                                        <th width="30%">Serial Number</th>
                                        <th> Aset</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                            <table class="table table-borderless table-hover mb-1" id="AsetPersediaan">
                                <thead>
                                    <tr>
                                        <th width="30%">Nama</th>
                                        <th width="5%">Qty</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="text-center form-buttons-w">

                        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Batal</button>
                        <button class="btn btn-primary btn-sm" type="submit">
                            Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>