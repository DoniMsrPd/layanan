<div class="modal fade" id="modalBaAset" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header" id="modal-title-ba">
                Generate BA
            </div>
            <div class="modal-body">
                <form method="POST" action="#" id="formBaAset" enctype="multipart/form-data">
                    <input type="hidden" id="idAset" name="idAset" value="">
                    <input type="hidden" id="jenisBA" name="jenisBA" value="">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group row mb-3">
                                <label class="col-form-label col-sm-3" for="">No BA</label>
                                <div class="col-md-9">
                                    <input class=" form-control" readonly name="NoBA"
                                        value="/BA-PERBAIKAN/X.5/{{ date('m') }}/{{ date('Y') }}">
                                </div>
                            </div>
                            <div class="form-group row mb-1" id="NmPegForm">
                                <label class="col-form-label col-sm-3 pb-0" for="">Nama Pihak Pertama</label>
                                <div class="col-form-label col-md-9 pb-0">
                                    <span id="NmPeg"></span>
                                </div>
                            </div>
                            <div class="form-group row mb-1" id="PegawaiPengembalianForm">
                                <label class="col-form-label col-sm-3 pb-0" for="">Nama Pihak Pertama</label>
                                <div class="col-form-label col-md-9 pb-0">
                                    <div class="input-group">

                                        <input type="hidden" name="KdUnitOrgPengembalian" value="">
                                        <input type="hidden" name="NipPengembalianAset" value="">
                                        <input required style="cursor: pointer;" readonly
                                            class="form-control lookup-pegawai5" placeholder="" id="PegawaiPengembalian"
                                            value="">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary lookup-pegawai5" type="button"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-1">
                                <label class="col-form-label col-sm-3 pb-0" for="">Nama Pihak Kedua</label>
                                <div class="col-form-label col-md-9 pb-0">
                                    <div class="input-group">

                                        <input type="hidden" name="KdUnitOrgPihak2" value="">
                                        <input type="hidden" name="NmUnitOrgPihak2" value="">
                                        <input type="hidden" name="NmJabatanPihak2" value="">
                                        <input type="hidden" name="NipPihak2" value="">
                                        <input required style="cursor: pointer;" readonly
                                            class="form-control lookup-pegawai3" placeholder="" id="NmPegawaiPihak2"
                                            value="">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary lookup-pegawai3" type="button"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group row mb-3">
                                <label class="col-form-label col-sm-3" for="">Tgl BA</label>
                                <div class="col-md-6">
                                    <input type="date" class="form-control datepicker" name="TglBA" id="TglBA"
                                        value="{{ date('Y-m-d') }}">
                                </div>
                            </div>
                            <div class="form-group row mb-1" id="ruangForm">
                                <label class="col-form-label col-sm-3" for="">Ruang</label>
                                <div class="col-md-9">
                                    <input class=" form-control" name="Ruang" value="" id="Ruang">
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
                                            class="form-control lookup-pegawai4" placeholder="" id="Pejabat"
                                            value="">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary lookup-pegawai4" type="button"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group row mb-1" id="keteranganForm">
                                <label class="col-form-label col-sm-1 mr-4" for="">Keterangan</label>
                                <div class="col-form-label col-md-10">
                                    <textarea class="form-control ckeditor" rows="2" id="KeteranganPengembalian" name="KeteranganPengembalian"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">

                            <hr>
                            <table class="table table-borderless table-hover mb-1">
                                <tr>
                                    <th width="30%">No IKN</th>
                                    <th width="30%">Serial Number</th>
                                    <th> Aset</th>
                                </tr>
                                <tr>
                                    <td style="font-size:0.8rem" id="NomorIKN">
                                    </td>
                                    <td style="font-size:0.8rem" id="SerialNumber">
                                    </td>
                                    <td style="font-size:0.8rem" id="NamaAset">
                                    </td>
                                </tr>
                            </table>
                            <div id="dataTambahan">

                            </div>
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