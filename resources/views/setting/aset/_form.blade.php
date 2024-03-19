<input type="hidden" class="form-control" name="id" id="id" value="{{ $data->aset->Id ??'' }}">
<div class="row">
    <div class="col-lg-6">
        <div class="form-group row mb-3">
            <label class="col-form-label col-sm-4" for=""> No. IKN <sup class="text-danger">*</sup></label>
            <div class=" col-sm-4"><input class=" form-control" placeholder="No. IKN 1" required=""
                    value="{{ $data->aset->NoIkn1 ??'' }}" name="NoIkn1">
            </div>
            <div class=" col-sm-4"><input class=" form-control" placeholder="No. IKN2" required=""
                    value="{{ $data->aset->NoIkn2 ??'' }}" name="NoIkn2">
            </div>
        </div>
        <div class="form-group row mb-3">
            <label class="col-form-label col-sm-4" for=""> No. Serial Number </label>
            <div class=" col-sm-8"><input class=" form-control" placeholder="No. Serial Number"
                    value="{{ $data->aset->SerialNumber ??'' }}" name="SerialNumber">
            </div>
        </div>
        <div class="form-group row mb-3">
            <label class="col-form-label col-sm-4" for=""> Jenis & Merk<sup class="text-danger">*</sup></label>
            <div class=" col-sm-4">

                <select class="form-control select2" required style="width: 100%" name="RefJnsAsetId" id="RefJnsAsetId">
                    <option value="">Pilih Jenis</option>
                    @foreach ($data->jenisAset as $jenisAset)
                    <option @if(($data->aset) && $data->aset->RefJnsAsetId==$jenisAset->Id) selected @endif
                        data-spesifikasi="{{ $jenisAset->IsSpesifikasi }}" value="{{ $jenisAset->Id }}">{{
                        $jenisAset->Nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class=" col-sm-4">

                <select class="form-control select2" required style="width: 100%" name="RefTypeAsetId"
                    id="RefTypeAsetId">
                    <option value="">Pilih Merk</option>
                    @if(isset($data->aset)&&isset($data->aset->RefTypeAsetId))
                    @foreach ($data->typeAset->where('RefJnsAsetId',$data->aset->RefJnsAsetId) as $typeAset)
                    <option @if(($data->aset) && $data->aset->RefTypeAsetId==$typeAset->Id) selected @endif value="{{
                        $typeAset->Id }}">{{ $typeAset->Nama }}</option>
                    @endforeach
                    @endif
                </select>
            </div>
        </div>
        <div class="form-group row mb-3">
            <label class="col-form-label col-sm-4" for=""> Tipe & Tahun<sup class="text-danger">*</sup></label>
            <div class=" col-sm-4">
                <input class=" form-control" placeholder="Tipe" required="" value="{{ $data->aset->Nama ??'' }}"
                    name="Nama">
            </div>
            <div class=" col-sm-4">

                <select class="form-control " required style="width: 100%" name="Tahun">
                    <option value="">Pilih Tahun</option>
                    @for($i = date("Y"); $i >=date("Y")-30; $i--)

                    <option @if(($data->aset) && $data->aset->Tahun==$i) selected @endif value="{{ $i }}">{{ $i }}
                    </option>
                    @endfor
                </select>
            </div>
        </div>
        <div class="form-group row mb-3">
            <label class="col-form-label col-sm-4" for=""> Harga Perolehan </label>
            <div class=" col-sm-8"><input class=" form-control" placeholder="Harga Perolehan"
                    value="{{ ($data->aset) ? number_format($data->aset->HargaPerolehan,0," .",",") :'' }}"
                    name="HargaPerolehan" id="HargaPerolehan">
            </div>
        </div>
        <div class="form-group row mb-3">
            <label class="col-form-label col-sm-4" for=""> Masa Garansi <sup class="text-danger">*</sup></label>
            <div class="col-sm-8">
                <div class="date-input"><input class="form-control" name="MasaGaransi" id="MasaGaransi"
                        placeholder="Masa Garansi"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">

        <div class="form-group row">
            <label class="col-form-label col-sm-3" for=""> Pengguna </label>
            <div class=" col-sm-8">
                <div class="input-group">
                    <input type="hidden" name="KdUnitOrgPengguna" id="KdUnitOrgPengguna"
                        value="{{ $data->aset->KdUnitOrgPengguna ??'' }}">
                    <input style="cursor: pointer;" readonly class="form-control lookup-pegawai9" placeholder=""
                        name="NipPengguna" id="NipPengguna"
                        value="{{ isset($data->aset->NipPengguna) ? $data->aset->NipPengguna.' '.optional($data->aset->pengguna)->NmPeg : '' }}">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary lookup-pegawai9" type="button">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        </button>
                        <button class="btn btn-danger hapusPegawai" data-url="/setting/aset/deletePengguna/{{ $data->aset->Id ?? '' }}" type="button"><svg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-trash'><polyline points='3 6 5 6 21 6'></polyline><path d='M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2'></path></svg></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 spesifikasi" style="display: none">
        <h5>Spesifikasi</h5>

        <div class="form-group row">
            <label class="col-form-label col-sm-4" for=""> Processor </label>
            <label class="col-form-label col-sm-4" for=""> Hardisk </label>
            <label class="col-form-label col-sm-4" for=""> Memory </label>
        </div>
        <div class="form-group row">
            <div class=" col-sm-4"><input class=" form-control" placeholder="Processor"
                    value="{{ $data->aset->Processor ??'' }}" name="Processor" id="Processor">
            </div>
            <div class=" col-sm-4"><input class=" form-control" placeholder="Hardisk"
                    value="{{ $data->aset->Hdd ??'' }}" name="Hdd" id="Hdd">
            </div>
            <div class=" col-sm-4"><input class=" form-control" placeholder="Memory"
                    value="{{ $data->aset->Memory ??'' }}" name="Memory" id="Memory">
            </div>
        </div>
    </div>
</div>