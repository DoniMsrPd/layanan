<div class="row">
    <div class="col-lg-9">
        <div class="form-group row" @if($data->layanan->StatusLayanan==4&&$data->layanan->CreatedBy==auth()->user()->NIP&&pegawaiBiasa()) @elseif(pegawaiBiasa()) style="display: none" @endif><label class="col-sm-3 col-form-label">Status<sup
                    class="text-danger">*</sup></label>
            <div class="col-sm-4">


                <select class="form-control select2"  style="width: 100%"
                    id="TL-RefStatusLayanan">
                    <option value="">Pilih Status</option>
                    @foreach ($data->refStatusLayanan as $refStatusLayanan)
                    <option value="{{ $refStatusLayanan->Id }}">{{ $refStatusLayanan->Nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class=" col-sm-4">
                @if($data->layanan && !$data->layanan->NoTicket)
                    <span class="text-danger"> Status Closed / Solved hanya bisa diisi setelah No Ticket Terisi </span>
                @endif
            </div>
        </div>
        @if(auth()->user()->hasRole(['Solver LataLati','SuperUser','Admin Proses Bisnis', 'Admin Probis Layanan','Operator']))
        <div class="form-group row">
            <label class="col-form-label col-sm-3" for=""> Layanan Khusus</label>
            @if($data->layanan->serviceCatalog && $data->layanan->serviceCatalog->IsPersediaan)
            <div class=" col-sm-2">
                <input type="checkbox" class="layananKhususCheckbox" data-form="persediaanForm"> Distribusi Persediaan <br>
            </div>
            @endif
            @if($data->layanan->serviceCatalog && $data->layanan->serviceCatalog->IsPerbaikan)
            <div class=" col-sm-2">
                <input type="checkbox" class="layananKhususCheckbox" data-form="perbaikanForm"> Perbaikan Aset TI <br>
            </div>
            @endif
            @if($data->layanan->serviceCatalog && $data->layanan->serviceCatalog->IsPeminjaman)
            <div class=" col-sm-2">
                <input type="checkbox" class="layananKhususCheckbox" data-form="peminjamanForm"> Peminjaman Aset TI <br>
            </div>
            @endif
        </div>
        @endif
        @if($data->layanan->serviceCatalog && $data->layanan->serviceCatalog->IsPersediaan)
            <div class="form-group row mb-4" id="persediaanForm" style="display: none">
                <label class="col-form-label col-sm-3" for=""> Persediaan</label>
                <div class=" col-sm-9">
                    <table class="table table-borderless table-hover" id="tablePersediaan">
                        <thead style="border-bottom: 1px dashed #ebedf2;">
                            <tr>
                                <th width="4%" style="font-size:0.7rem">No</th>
                                <th style="font-size:0.7rem">Nama</th>
                                <th style="font-size:0.7rem">Qty</th>
                                <th style="font-size:0.7rem">Keterangan</th>
                                <th width="16%" style="text-align: center;">
                                    <button style="padding:3px 3px;margin:0px" type="button" data-toggle="modal"
                                        class="btn btn-success btn-sm lookup-persediaan">
                                        <i data-feather="plus" title="Tambah Persediaan"></i>
                                    </button>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
        @if($data->layanan->serviceCatalog && $data->layanan->serviceCatalog->IsPerbaikan)
            <div class="form-group row mb-4" id="perbaikanForm" style="display: none">
                <label class="col-form-label col-sm-3" for=""> Aset TI</label>
                <div class=" col-sm-9">
                    <table class="table table-borderless table-hover" id="tableAset">
                        <thead style="border-bottom: 1px dashed #ebedf2;">
                            <tr>
                                <th style="font-size:0.7rem"  colspan="4">Detail Aset</th>
                                <th style="font-size:0.7rem"></th>
                                <th width="16%" style="text-align: center;">
                                    <button style="padding:3px 3px;margin:0px" type="button" data-toggle="modal"
                                        class="btn btn-success btn-sm lookup-aset">
                                        <i data-feather="plus" title="Tambah Aset"></i>
                                    </button>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
        @if($data->layanan->serviceCatalog && $data->layanan->serviceCatalog->IsPeminjaman)
            <div class="form-group row mb-4" id="peminjamanForm" style="display: none">
                <label class="col-form-label col-sm-3" for=""> Peminjaman Aset</label>
                <div class=" col-sm-9">
                    <table class="table table-borderless table-hover" id="tablePeminjaman">
                        <thead style="border-bottom: 1px dashed #ebedf2;">
                            <tr>
                                <th width="4%" style="font-size:0.7rem">No</th>
                                <th style="font-size:0.7rem">No IKN</th>
                                <th style="font-size:0.7rem">Serial Number</th>
                                <th style="font-size:0.7rem">Aset</th>
                                <th style="font-size:0.7rem">Keterangan</th>
                                <th width="16%" style="text-align: center;">
                                    <button style="padding:3px 3px;margin:0px" type="button" data-toggle="modal"
                                        class="btn btn-success btn-sm lookup-peminjaman">
                                        <i data-feather="plus" title="Tambah Aset"></i>
                                    </button>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
        <div class="form-group row" >
            <label class="col-form-label col-sm-3" for=""> Catatan untuk User</label>
            <div class=" col-sm-8">
                <textarea class="form-control ckeditor" rows="3" id="TLKeterangan"></textarea>
            </div>
            <div class=" col-sm-1">
                <a style="padding:3px 3px;margin:0px" class="btn btn-success btn-sm lookup-template" href="javascript:void(0)" title="Template Penyesuaian"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline></svg></a>
            </div>
        </div>
        <br>
        <div class="form-group row">
            <label class="col-form-label col-sm-3" for=""> File Attachment</label>
            <div class=" col-sm-6 custom-file"><input type="file" class=" form-control custom-file"
                    placeholder="File Attachment" id="TL-FileAttachment" multiple >
            </div>
        </div>
    </div>
</div>
<a type="button" class="btn btn-sm btn-info btn-flat mt-1 save-tl" > <i class="fa fa-check-circle"></i>
    Simpan</a>
<a type="button" class="btn btn-sm btn-danger btn-flat mt-1 ml-1 cancel-tl"> Batal</a>
