<input type="hidden" name="eskalasi" id="" value="{{ $data->eskalasi ?? 0 }}">
<input type="hidden" class="form-control" name="id" id="id" value="{{ $data->layanan->Id ??'' }}"
    enctype="multipart/form-data">
<input type="hidden" name="kembali" value="{{ request()->kembali }}">
<div class="row col-12">
    @if($data->showForm)

        <div class="col-lg-6">
            <div class="form-group row"><label class="col-sm-3 col-form-label">Layanan <sup
                        class="text-danger">*</sup></label>
                <div class="col-sm-8 col-form-label">
                    @if($data->showForm)
                    <select class="form-control" @if(isset($data->layanan->JenisLayanan)) disabled
                        @endif required style="width: 100%" name="JenisLayanan"
                        id="JenisLayanan">
                        <option value="">Pilih Layanan</option>
                        @foreach ($data->refJenisLayanan as $refJenisLayanan)
                        <option @if(($data->layanan) && $data->layanan->JenisLayanan==$refJenisLayanan->Id || count($data->refJenisLayanan) == 1)
                            selected @endif
                            value="{{ $refJenisLayanan->Id }}">{{
                            $refJenisLayanan->Nama }}
                        </option>
                        @endforeach
                    </select>
                    @else
                    {{ optional($data->layanan->jenis)->Nama }}
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-sm-3" for=""> Kode ITSM<sup class="text-danger">*</sup></label>
                <div class=" col-sm-8 col-form-label">
                    <div class="input-group">
                        @if($data->showForm)
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary lookup-service-catalog" type="button"><i data-feather="file"></i></button>
                            <input type="hidden" name="ServiceCatalogId" id="ServiceCatalogId"
                                value="{{ $data->layanan->ServiceCatalogId ??'' }}">
                            <input type="hidden" name="ServiceCatalogKode" id="ServiceCatalogKode"
                                value="{{ $data->layanan->ServiceCatalogKode ??'' }}">
                            <input type="hidden" name="ServiceCatalogNama" id="ServiceCatalogNama"
                                value="{{ $data->layanan->ServiceCatalogNama ??'' }}">
                            <span style="padding-left:10px" id="ServiceCatalog">{{ $data->layanan ?
                                optional($data->layanan->serviceCatalog)->Kode.'
                                '.optional($data->layanan->serviceCatalog)->Nama:'' }}</span>
                        </div>

                        @else
                        {{ optional($data->layanan->serviceCatalog)->Kode.'
                        '.optional($data->layanan->serviceCatalog)->Nama }}
                        @endif
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-form-label col-sm-3" for=""> SLA<sup class="text-danger">*</sup></label>
                <div class=" col-sm-8 col-form-label">
                    @if($data->showForm)
                    <div class="input-group">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary lookup-sla" type="button"><i data-feather="file"></i></button>
                        </div>
                        <input type="hidden" name="ServiceCatalogDetailId" id="ServiceCatalogDetailId"
                            value="{{ $data->layanan->ServiceCatalogDetailId ??'' }}">
                        <span style="padding-left: 10px" id="ServiceCatalogDetail">{{ $data->layanan->sla->Nama
                            ??'' }}</span>
                    </div>

                    @else
                    <span>{{ $data->layanan->sla->Nama ??'' }}</span>
                    @endif
                </div>
            </div>
            <div class="form-group row">
                <label class="col-form-label col-sm-3" for=""> Nomor Kontak <sup class="text-danger">*</sup></label>
                <div class=" col-sm-4 col-form-label">
                    @if($data->showForm)
                    <input class=" form-control" placeholder="Nomor Kontak" required="" name="NomorKontak" id="NomorKontak"
                        value="{{ $data->layanan->NomorKontak ?? auth()->user()->pegawai->NoHp }}">
                    @else
                    {{ $data->layanan->NomorKontak ?? auth()->user()->pegawai->NoHp }}
                    @endif
                </div>
                @if($data->showForm)
                <label class="col-form-label col-sm-4" for=""><sup class="text-danger"> * Harap Isi dengan Nomor Kontak
                        Aktif</sup></label>
                @endif

            </div>
            <div class="form-group row">
                <label class="col-form-label col-sm-3" for=""> File Attachment</label>
                <div class=" col-sm-6 @if($data->showForm) custom-file @else col-form-label @endif">
                    @if($data->showForm)
                    <input type="file" class=" form-control custom-file" placeholder="File Attachment"
                        name="FileAttachment[]" multiple>
                    @else
                    @if (isset($data->layanan->files) && count($data->layanan->files) > 0)
                    <ul class="pt-1 mb-0 list-file">
                        @foreach ($data->layanan->files as $key => $file)
                        <li>
                            <a href="/core/{{ $file->PathFile }}" class="f-16" target="_blank">
                                <span class="mdi mdi-file-pdf"></span> {{
                                \Illuminate\Support\Str::limit($file->NmFileOriginal, 30) }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                    @endif

                    @if (isset($data->layanan->filesOld) && count($data->layanan->filesOld) > 0)
                    <ul class="pt-1 mb-0 list-file">
                        @foreach ($data->layanan->filesOld as $key => $file)
                        <li>
                            <a href="/core/{{ $file->PathFile }}&NmFile={{ $file->NmFile }}" class="f-16" target="_blank">
                                <span class="mdi mdi-file-pdf"></span> {{
                                \Illuminate\Support\Str::limit($file->NmFileOriginal, 30) }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                    @endif
                </div>
            </div>
            @if($data->showForm)
            <div class="form-group row">
                <label class="col-form-label col-sm-3" for=""></label>
                <div class=" col-sm-6 col-form-label">
                    @if (isset($data->layanan->files) && count($data->layanan->files) > 0)
                    <ul class="pt-1 mb-0 list-file">
                        @foreach ($data->layanan->files as $key => $file)
                        <li>
                            <a href="/core/{{ $file->PathFile }}" class="f-16" target="_blank">
                                <span class="mdi mdi-file-pdf"></span> {{
                                \Illuminate\Support\Str::limit($file->NmFileOriginal, 30) }}
                            </a>
                            <span style="cursor: pointer;" data-id="{{ $file->Id }}"
                                data-title="{{ $file->NmFileOriginal }}" data-url="/core/storage/{{ $file->Id }}"
                                class="text-danger deleteData float-right"><i class="icon-feather-trash-2"></i></span>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                    @if (isset($data->layanan->filesOld) && count($data->layanan->filesOld) > 0)
                    <ul class="pt-1 mb-0 list-file">
                        @foreach ($data->layanan->filesOld as $key => $file)
                        <li>
                            <a href="/core/{{ $file->PathFile }}&NmFile={{ $file->NmFile }}" class="f-16" target="_blank">
                                <span class="mdi mdi-file-pdf"></span> {{
                                \Illuminate\Support\Str::limit($file->NmFileOriginal, 30) }}
                            </a>
                            <span style="cursor: pointer;" data-id="{{ $file->Id }}"
                                data-title="{{ $file->NmFileOriginal }}" data-url="/core/storage/{{ $file->Id }}"
                                class="text-danger deleteData float-right"><i class="icon-feather-trash-2"></i></span>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </div>
            </div>
            @endif
            <div class="form-group row">
                <label class="col-form-label col-sm-3" for="">Operator</label>
                <div class=" col-sm-7 col-form-label">
                    {{ $data->layanan->NipOperatorOpen ?? auth()->user()->NIP }} - {{ isset($data->layanan->NipOperatorOpen)
                    ?
                    $data->layanan->operatorOpen->NmPeg : auth()->user()->pegawai->NmPeg }} - {{ $data->layanan ? ToDmyHi($data->layanan->CreatedAt) : '' }}
                </div>
            </div>
            @if($data->showForm)
            <div class="form-group row">
                <label class="col-form-label col-sm-3" for=""></label>
                <div class="col-sm-6 col-form-label">
                    <input type="checkbox" id="NotifikasiEmail" name="NotifikasiEmail" value="1" @if(($data->layanan)
                    &&$data->layanan->NotifikasiEmail || ($data->layanan && $data->layanan->NoTicket==null) ) checked @endif> User
                    Akan dikirim
                    Notifikasi Email
                    Layanan <br>
                </div>
            </div>
            @endif
            <div class="form-group row" style="display: none">
                <label class="col-form-label col-sm-3" for=""> Nota Dinas</label>
                <div class=" col-sm-6 col-form-label">
                    @if($data->showForm)
                    <input class=" form-control" placeholder="Nota Dinas" name="NotaDinas"
                        value="{{ $data->layanan->NotaDinas ?? '' }}">
                    @else
                    <span>{{ $data->layanan->NotaDinas ?? '' }}</span>
                    @endif
                </div>
            </div>
            <div class="form-group row" style="display: none">
                <label class="col-form-label col-sm-3" for=""> File Nota Dinas</label>
                <div class=" col-sm-6 @if($data->showForm) custom-file @else col-form-label @endif">
                    @if($data->showForm)
                    <input type="file" class=" form-control custom-file" placeholder="File Nota Dinas"
                        name="FileNotaDinas[]" multiple>
                    @else
                    @if (isset($data->layanan->filesNotaDinas) && count($data->layanan->filesNotaDinas) > 0)
                    <ul class="pt-1 mb-0 list-file">
                        @foreach ($data->layanan->filesNotaDinas as $key => $file)
                        <li>
                            <a href="/core/{{ $file->PathFile }}" class="f-16" target="_blank">
                                <span class="mdi mdi-file-pdf"></span> {{
                                \Illuminate\Support\Str::limit($file->NmFileOriginal, 30) }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                    @if (isset($data->layanan->filesNotaDinasOld) && count($data->layanan->filesNotaDinasOld) > 0)
                    <ul class="pt-1 mb-0 list-file">
                        @foreach ($data->layanan->filesNotaDinasOld as $key => $file)
                        <li>
                            <a href="/core/{{ $file->PathFile }}" class="f-16" target="_blank">
                                <span class="mdi mdi-file-pdf"></span> {{
                                \Illuminate\Support\Str::limit($file->NmFileOriginal, 30) }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                    @endif
                </div>
            </div>

            @if($data->showForm)
            <div class="form-group row" style="display: none">
                <label class="col-form-label col-sm-3" for=""></label>
                <div class=" col-sm-6 col-form-label">
                    @if (isset($data->layanan->filesNotaDinas) && count($data->layanan->filesNotaDinas) > 0)
                    <ul class="pt-1 mb-0 list-file">
                        @foreach ($data->layanan->filesNotaDinas as $key => $file)
                        <li>
                            <a href="/core/{{ $file->PathFile }}" class="f-16" target="_blank">
                                <span class="mdi mdi-file-pdf"></span> {{
                                \Illuminate\Support\Str::limit($file->NmFileOriginal, 30) }}
                            </a>
                            <span style="cursor: pointer;" data-id="{{ $file->Id }}"
                                data-title="{{ $file->NmFileOriginal }}" data-url="/core/storage/{{ $file->Id }}"
                                class="text-danger deleteData float-right"><i class="icon-feather-trash-2"></i></span>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                    @if (isset($data->layanan->filesNotaDinasOld) && count($data->layanan->filesNotaDinasOld) > 0)
                    <ul class="pt-1 mb-0 list-file">
                        @foreach ($data->layanan->filesNotaDinasOld as $key => $file)
                        <li>
                            <a href="/core/{{ $file->PathFile }}" class="f-16" target="_blank">
                                <span class="mdi mdi-file-pdf"></span> {{
                                \Illuminate\Support\Str::limit($file->NmFileOriginal, 30) }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </div>
            </div>
            @endif
            <div class="form-group row">
                <label class="col-form-label col-sm-3" for=""> Pelapor Layanan <sup class="text-danger">*</sup></label>
                <div class=" col-sm-8 col-form-label">

                    @if($data->showForm)
                    <div class="input-group">
                        <input type="hidden" name="KdUnitOrg" id="KdUnitOrg"
                            value="{{ $data->layanan->KdUnitOrg ?? auth()->user()->pegawai->KdUnitOrg }}">
                        <input type="hidden" name="NmUnitOrg" id="NmUnitOrg"
                            value="{{ $data->layanan->NmUnitOrg ?? auth()->user()->pegawai->NmUnitOrg }}">
                        <input type="hidden" name="NmUnitOrgInduk" id="NmUnitOrgInduk"
                            value="{{ $data->layanan->NmUnitOrgInduk ?? auth()->user()->pegawai->NmUnitOrgInduk }}">
                        <input type="hidden" name="NmPeg" id="NmPeg"
                            value="{{ $data->layanan->NmPeg ?? auth()->user()->pegawai->NmPeg }}">
                        <input type="hidden" name="Nip" id="Nip"
                            value="{{ $data->layanan->Nip ?? auth()->user()->pegawai->Nip }}">
                        <input style="cursor: pointer;" readonly class="form-control lookup-pegawai" placeholder=""
                            id="NmPegawai"
                            value="{{ $data->layanan ? $data->layanan->Nip .'-'.$data->layanan->pelapor->NmPeg : auth()->user()->NIP.'-'.auth()->user()->pegawai->NmPeg }}">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary lookup-pegawai" type="button"><i
                                data-feather='search'></i></i></button>
                            <button class="btn btn-success lookup-layanan ml-3" type="button"><i
                                    data-feather="share" title="Histori Layanan"></i></button>
                        </div>
                    </div>
                    @else
                    {{ $data->layanan->Nip .'-'.$data->layanan->pelapor->NmPeg }}
                    @endif
                    <div id="NmJabatan">{{ isset($data->layanan->Id) ? $data->layanan->pelapor->NmJabatan :
                        auth()->user()->pegawai->NmJabatan }}</div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-form-label col-sm-3" for=""> Satker Pelapor</label>
                <div class=" col-sm-8 col-form-label">
                    <div style="padding: 8px" id="NmUnitOrgPelapor">{{ $data->layanan->pelapor->NmUnitOrg ??
                        auth()->user()->pegawai->NmUnitOrg }}</div>
                    <div style="padding: 8px" id="NmUnitOrgIndukPelapor">
                        @if($data->layanan && isset($data->layanan->pelapor) && $data->layanan->pelapor->NmUnitOrgInduk )
                        @if($data->layanan->pelapor->NmUnitOrgInduk!=$data->layanan->pelapor->NmUnitOrg)
                        {{ $data->layanan->pelapor->NmUnitOrgInduk }}
                        @endif
                        @else
                        {{ auth()->user()->pegawai->NmUnitOrgInduk }}
                        @endif
                    </div>
                </div>
            </div>
            @if($data->showForm && ($data->layanan ? $data->layanan->KdUnitOrgOwnerLayanan == '100205000000' : false))
            <div class="form-group row">
                <label class="col-form-label col-sm-3" for=""></label>
                <div class="col-sm-6 col-form-label">
                    <input type="checkbox" id="pegawaiLain" name="pegawaiLain" value="1" @if(($data->layanan)
                    &&$data->layanan->Nip!=$data->layanan->NipLayanan) checked @endif>
                    Membuat Permintaan Pegawai
                    Lain <br>
                </div>
            </div>
            @endif
            <div class="form-group row layanan" style="display: none">
                <label class="col-form-label col-sm-3" for=""> Penerima Layanan<sup class="text-danger">*</sup></label>
                <div class=" col-sm-8 col-form-label">

                    @if($data->showForm)
                    <div class="input-group">
                        <input type="hidden" name="KdUnitOrgLayanan" id="KdUnitOrgLayanan"
                            value="{{ $data->layanan->KdUnitOrgLayanan ?? auth()->user()->pegawai->KdUnitOrg }}">
                        <input type="hidden" name="NipLayanan" id="NipLayanan"
                            value="{{ $data->layanan->NipLayanan ?? auth()->user()->pegawai->KdUnitOrg }}">
                        <input style="cursor: pointer;" readonly class="form-control lookup-pegawai2" placeholder=""
                            id="NmPegLayanan"
                            value="{{ $data->layanan ? $data->layanan->NipLayanan.'-'.optional($data->layanan->penerima)->NmPeg : auth()->user()->NIP .'-'.auth()->user()->pegawai->NmPeg}}">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary lookup-pegawai2" type="button"><i
                                    class="icon-feather-search"></i></button>
                        </div>
                    </div>
                    @else
                    {{ $data->layanan->NipLayanan.'-'.optional($data->layanan->penerima)->NmPeg }}
                    @endif
                    <div id="NmJabatanLayanan">{{ isset($data->layanan->Id) ?
                        optional($data->layanan->penerima)->NmJabatan :
                        auth()->user()->pegawai->NmJabatan }}</div>
                </div>
            </div>
            <div class="form-group row layanan" style="display: none">
                <label class="col-form-label col-sm-3" for=""> Satker Penerima </label>
                <div class=" col-sm-8 col-form-label">
                    <div id="NmUnitOrgLayanan">{{ $data->layanan->penerima->NmUnitOrg ??
                        auth()->user()->pegawai->NmUnitOrg }}</div>
                    <div id="NmUnitOrgIndukLayanan">

                        @if( $data->layanan && isset($data->layanan->penerima) &&$data->layanan->penerima->NmUnitOrgInduk )
                        @if($data->layanan->penerima->NmUnitOrgInduk!=$data->layanan->penerima->NmUnitOrg)
                        {{ $data->layanan->penerima->NmUnitOrgInduk }}
                        @endif
                        @else
                        {{ auth()->user()->pegawai->NmUnitOrgInduk }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group row"><label class="col-sm-3 col-form-label">Prioritas <sup
                        class="text-danger">*</sup></label>
                <div class="col-sm-9 col-form-label">
                    @if($data->showForm)
                    <select required class="form-control" style="width: 100%" name="Prioritas" id="Prioritas">
                        <option value="">Pilih Prioritas</option>
                        @foreach ($data->refPrioritas as $refPrioritas)
                        <option @if(($data->layanan) && $data->layanan->PrioritasLayanan==$refPrioritas->Id)
                            selected @endif
                            value="{{ $refPrioritas->Id }}">{{
                            $refPrioritas->Id }}
                        </option>
                        @endforeach
                    </select>
                    @else
                    {{ optional($data->layanan->prioritas)->Id }}
                    @endif
                </div>
            </div>
            @if($data->showForm)
            <div class="form-group row"><label class="col-sm-3 col-form-label">Informasi Layanan <sup
                        class="text-danger">*</sup></label>
                <div class="col-sm-9 col-form-label">

                    <textarea required class="form-control ckeditor" rows="3"
                        name="PermintaanLayanan">{{ isset($data->layanan->PermintaanLayanan) ? nl2br($data->layanan->PermintaanLayanan) : '' }}</textarea>

                </div>
            </div>
            @endif

            @if (isMobile())
            <div class="form-group row">
                <div class="col-sm-9 col-form-label">
                    <div class="table-responsive text-nowrap">
                        <table class="table" id="tableKategori">
                            <thead class="table-light">
                                <tr>
                                    <th style="font-size:0.7rem">
                                        Kategori
                                        @if($data->showForm)
                                            <button style="padding:3px 3px;margin:0px;float: right;" type="button" data-toggle="modal"
                                                class="btn btn-success btn-sm lookup-kategori">
                                                <i data-feather='plus' title="Tambah Kategori"></i>
                                            </button>
                                        @endif
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">

                                @if($data->layanan)
                                    @foreach ($data->layanan->layananKategori as $kategori)
                                    <tr id="row1">
                                        <input type="hidden" name="Kategori[]" value="{{ $kategori->MstKategoriId }}">
                                        <td class="kategori" style="display:none">{{ $kategori->MstKategoriId }}</td>
                                        <td style="font-size:0.8rem">
                                            {{ $kategori->mstKategori->Nama }} <br> {{ $kategori->mstKategori->Keterangan }}

                                            @if($data->showForm)
                                            <br>
                                            <a style="padding:3px 3px;margin:0px"
                                                    class="btn btn-danger btn-sm deleteData"
                                                    data-url="/layanan/kategori/{{ $kategori->Id }}"
                                                    data-title="{{ $kategori->mstKategori->Nama }}"
                                                    data-id="{{ $kategori->Id }}" href="javascript:void(0)"
                                                    title="Hapus"><i data-feather="trash"></i></a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @else
            <div class="form-group row"><label class="col-sm-3 col-form-label">Kategori</label>
                <div class="col-sm-9 col-form-label">
                    <div class="table-responsive text-nowrap">
                        <table class="table" id="tableKategori">
                            <thead class="table-light">
                                <tr>
                                    <th width="4%" style="font-size:0.7rem">No</th>
                                    <th style="font-size:0.7rem" width="15%">Nama</th>
                                    <th style="font-size:0.7rem">Keterangan</th>
                                    @if($data->showForm)
                                    <th width="16%" style="text-align: center;">
                                        <button style="padding:3px 3px;margin:0px" type="button" data-toggle="modal"
                                            class="btn btn-success btn-sm lookup-kategori">
                                            <i data-feather='plus' title="Tambah Kategori"></i>
                                        </button>
                                    </th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">

                                @if($data->layanan)
                                    @foreach ($data->layanan->layananKategori as $kategori)
                                    <tr id="row1"> <input type="hidden" name="Kategori[]"
                                            value="{{ $kategori->MstKategoriId }}">
                                        <td >{{ $loop->iteration }}</td>
                                        <td class="kategori" style="display:none">{{ $kategori->MstKategoriId }}</td>
                                        <td >{{ $kategori->mstKategori->Nama }}</td>
                                        <td >{{ $kategori->mstKategori->Keterangan }}</td>
                                        @if($data->showForm)
                                        <td class="text-center"><a style="padding:3px 3px;margin:0px"
                                                class="btn btn-danger btn-sm deleteData"
                                                data-url="/layanan/kategori/{{ $kategori->Id }}"
                                                data-title="{{ $kategori->mstKategori->Nama }}"
                                                data-id="{{ $kategori->Id }}" href="javascript:void(0)"
                                                title="Hapus"><i data-feather="trash"></i></a></td>
                                        @endif
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
            @if($data->showForm)
            <div class="form-group row"><label class="col-sm-3 col-form-label">Catatan Lain </label>
                <div class="col-sm-9 col-form-label">
                    <textarea class="form-control" rows="3"
                        name="KeteranganLayanan">{{ $data->layanan->KeteranganLayanan ??'' }}</textarea>
                </div>
            </div>
            @endif
        </div>
    @else

    <div class="col-lg-6">
        <div class="form-group row"><label class="col-sm-3 col-form-label">Layanan <sup
                    class="text-danger">*</sup></label>
            <div class="col-sm-8 col-form-label">
                @if($data->showForm)
                <select class="form-control" @if(isset($data->layanan->JenisLayanan)) disabled
                    @endif required style="width: 100%" name="JenisLayanan"
                    id="JenisLayanan">
                    <option value="">Pilih Layanan</option>
                    @foreach ($data->refJenisLayanan as $refJenisLayanan)
                    <option @if(($data->layanan) && $data->layanan->JenisLayanan==$refJenisLayanan->Id)
                        selected @endif
                        value="{{ $refJenisLayanan->Id }}">{{
                        $refJenisLayanan->Nama }}
                    </option>
                    @endforeach
                </select>
                @else
                {{ optional($data->layanan->jenis)->Nama }}
                @endif
            </div>
        </div>

        <div class="form-group row">
            <label class="col-form-label col-sm-3" for=""> Kode ITSM<sup class="text-danger">*</sup></label>
            <div class=" col-sm-8 col-form-label">
                <div class="input-group">
                    @if(!$data->layanan->ServiceCatalogDetailId)
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary lookup-service-catalog" type="button"><i data-feather="file"></i></button>
                        <input type="hidden" name="ServiceCatalogId" id="ServiceCatalogId"
                            value="{{ $data->layanan->ServiceCatalogId ??'' }}">
                        <input type="hidden" name="ServiceCatalogKode" id="ServiceCatalogKode"
                            value="{{ $data->layanan->ServiceCatalogKode ??'' }}">
                        <input type="hidden" name="ServiceCatalogNama" id="ServiceCatalogNama"
                            value="{{ $data->layanan->ServiceCatalogNama ??'' }}">
                        <span style="padding-left:10px" id="ServiceCatalog">{{ $data->layanan ?
                            optional($data->layanan->serviceCatalog)->Kode.'
                            '.optional($data->layanan->serviceCatalog)->Nama:'' }}</span>
                    </div>

                    @else
                    <input type="hidden" name="ServiceCatalogId" id="ServiceCatalogId"
                        value="{{ $data->layanan->ServiceCatalogId ??'' }}">
                    <input type="hidden" name="ServiceCatalogKode" id="ServiceCatalogKode"
                        value="{{ $data->layanan->ServiceCatalogKode ??'' }}">
                    <input type="hidden" name="ServiceCatalogNama" id="ServiceCatalogNama"
                        value="{{ $data->layanan->ServiceCatalogNama ??'' }}">
                    {{ optional($data->layanan->serviceCatalog)->Kode.'
                    '.optional($data->layanan->serviceCatalog)->Nama }}
                    @endif
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-sm-3" for=""> SLA<sup class="text-danger">*</sup></label>
            <div class=" col-sm-8 col-form-label">
                @if(!$data->layanan->ServiceCatalogDetailId)
                <div class="input-group">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary lookup-sla" type="button"><i data-feather="file"></i></button>
                    </div>
                    <input type="hidden" name="ServiceCatalogDetailId" id="ServiceCatalogDetailId"
                        value="{{ $data->layanan->ServiceCatalogDetailId ??'' }}">
                    <span style="padding-left: 10px" id="ServiceCatalogDetail">{{ $data->layanan->sla->Nama
                        ??'' }}</span>
                </div>

                @else
                <input type="hidden" name="ServiceCatalogDetailId" id="ServiceCatalogDetailId"
                    value="{{ $data->layanan->ServiceCatalogDetailId ??'' }}">
                <span>{{ $data->layanan->sla->Nama ??'' }}</span>
                @endif
            </div>
        </div>
        <div class="form-group row"><label class="col-sm-3 col-form-label">Prioritas <sup
                    class="text-danger">*</sup></label>
            <div class="col-sm-9 col-form-label">
                @if($data->showForm)
                <select required class="form-control" style="width: 100%" name="Prioritas" id="Prioritas">
                    <option value="">Pilih Prioritas</option>
                    @foreach ($data->refPrioritas as $refPrioritas)
                    <option @if(($data->layanan) && $data->layanan->PrioritasLayanan==$refPrioritas->Id)
                        selected @endif
                        value="{{ $refPrioritas->Id }}">{{
                        $refPrioritas->Id }}
                    </option>
                    @endforeach
                </select>
                @else
                {{ optional($data->layanan->prioritas)->Id }}
                @endif
            </div>
        </div>

        @if (isMobile())
        <div class="form-group row">
            <div class="col-sm-9 col-form-label">

                <table class="table" id="tableKategori">
                    <thead class="table-light">
                        <tr>
                            <th style="font-size:0.7rem">
                                Kategori
                                @if($data->showForm)
                                    <button style="padding:3px 3px;margin:0px;float: right;" type="button" data-toggle="modal"
                                        class="btn btn-success btn-sm lookup-kategori">
                                        <i data-feather='plus' title="Tambah Kategori"></i>
                                    </button>
                                @endif
                            </th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">

                        @if($data->layanan)
                            @foreach ($data->layanan->layananKategori as $kategori)
                            <tr id="row1">
                                <input type="hidden" name="Kategori[]" value="{{ $kategori->MstKategoriId }}">
                                <td class="kategori" style="display:none">{{ $kategori->MstKategoriId }}</td>
                                <td style="font-size:0.8rem">
                                    {{ $kategori->mstKategori->Nama }} <br> {{ $kategori->mstKategori->Keterangan }}

                                    @if($data->showForm)
                                    <br>
                                    <a style="padding:3px 3px;margin:0px"
                                            class="btn btn-danger btn-sm deleteData"
                                            data-url="/layanan/kategori/{{ $kategori->Id }}"
                                            data-title="{{ $kategori->mstKategori->Nama }}"
                                            data-id="{{ $kategori->Id }}" href="javascript:void(0)"
                                            title="Hapus"><i data-feather="trash"></i></a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <div class="form-group row"><label class="col-sm-3 col-form-label">Kategori</label>
            <div class="col-sm-9 col-form-label">

                <table class="table table-borderless table-hover" id="tableKategori">
                    <thead style="border-bottom: 1px dashed #ebedf2;">
                        <tr>
                            <th width="4%" style="font-size:0.7rem">No</th>
                            <th style="font-size:0.7rem" width="15%">Nama</th>
                            <th style="font-size:0.7rem">Keterangan</th>
                            @if($data->showForm)
                            <th width="16%" style="text-align: center;">
                                <button style="padding:3px 3px;margin:0px" type="button" data-toggle="modal"
                                    class="btn btn-success btn-sm lookup-kategori">
                                    <i class="icon-feather-plus" title="Tambah Kategori"></i>
                                </button>
                            </th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @if($data->layanan)
                            @foreach ($data->layanan->layananKategori as $kategori)
                            <tr id="row1"> <input type="hidden" name="Kategori[]"
                                    value="{{ $kategori->MstKategoriId }}">
                                <td>{{ $loop->iteration }}</td>
                                <td class="kategori" style="display:none">{{ $kategori->MstKategoriId }}</td>
                                <td>{{ $kategori->mstKategori->Nama }}</td>
                                <td>{{ $kategori->mstKategori->Keterangan }}</td>
                                @if($data->showForm)
                                <td class="text-center"><a style="padding:3px 3px;margin:0px"
                                        class="btn btn-danger btn-sm deleteData"
                                        data-url="/layanan/kategori/{{ $kategori->Id }}"
                                        data-title="{{ $kategori->mstKategori->Nama }}"
                                        data-id="{{ $kategori->Id }}" href="javascript:void(0)"
                                        title="Hapus"><i class="icon-feather-trash-2"></i></a></td>
                                @endif
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <div class="form-group row">
            <label class="col-form-label col-sm-3" for=""> File Attachment</label>
            <div class=" col-sm-6 @if($data->showForm) custom-file @else col-form-label @endif">
                @if($data->showForm)
                <input type="file" class=" form-control custom-file" placeholder="File Attachment"
                    name="FileAttachment[]" multiple>
                @else
                @if (isset($data->layanan->files) && count($data->layanan->files) > 0)
                <ul class="pt-1 mb-0 list-file">
                    @foreach ($data->layanan->files as $key => $file)
                    <li>
                        <a href="/core/{{ $file->PathFile }}" class="f-16" target="_blank">
                            <span class="mdi mdi-file-pdf"></span> {{
                            \Illuminate\Support\Str::limit($file->NmFileOriginal, 30) }}
                        </a>
                    </li>
                    @endforeach
                </ul>
                @endif
                @if (isset($data->layanan->filesOld) && count($data->layanan->filesOld) > 0)
                <ul class="pt-1 mb-0 list-file">
                    @foreach ($data->layanan->filesOld as $key => $file)
                    <li>
                        <a href="/core/{{ $file->PathFile }}&NmFile={{ $file->NmFile }}" class="f-16" target="_blank">
                            <span class="mdi mdi-file-pdf"></span> {{
                            \Illuminate\Support\Str::limit($file->NmFileOriginal, 30) }}
                        </a>
                    </li>
                    @endforeach
                </ul>
                @endif
                @if (isset($data->layanan->filesNotaDinasOld) && count($data->layanan->filesNotaDinasOld) > 0)
                <ul class="pt-1 mb-0 list-file">
                    @foreach ($data->layanan->filesNotaDinasOld as $key => $file)
                    <li>
                        <a href="/core/{{ $file->PathFile }}" class="f-16" target="_blank">
                            <span class="mdi mdi-file-pdf"></span> {{
                            \Illuminate\Support\Str::limit($file->NmFileOriginal, 30) }}
                        </a>
                    </li>
                    @endforeach
                </ul>
                @endif
                @endif
            </div>
        </div>
        @if($data->showForm)
        <div class="form-group row">
            <label class="col-form-label col-sm-3" for=""></label>
            <div class=" col-sm-6 col-form-label">
                @if (isset($data->layanan->files) && count($data->layanan->files) > 0)
                <ul class="pt-1 mb-0 list-file">
                    @foreach ($data->layanan->files as $key => $file)
                    <li>
                        <a href="/core/{{ $file->PathFile }}" class="f-16" target="_blank">
                            <span class="mdi mdi-file-pdf"></span> {{
                            \Illuminate\Support\Str::limit($file->NmFileOriginal, 30) }}
                        </a>
                        <span style="cursor: pointer;" data-id="{{ $file->Id }}"
                            data-title="{{ $file->NmFileOriginal }}" data-url="/core/storage/{{ $file->Id }}"
                            class="text-danger deleteData float-right"><i class="icon-feather-trash-2"></i></span>
                    </li>
                    @endforeach
                </ul>
                @endif

                @if (isset($data->layanan->filesOld) && count($data->layanan->filesOld) > 0)
                <ul class="pt-1 mb-0 list-file">
                    @foreach ($data->layanan->filesOld as $key => $file)
                    <li>
                        <a href="/core/{{ $file->PathFile }}&NmFile={{ $file->NmFile }}" class="f-16" target="_blank">
                            <span class="mdi mdi-file-pdf"></span> {{
                            \Illuminate\Support\Str::limit($file->NmFileOriginal, 30) }}
                        </a>
                        <span style="cursor: pointer;" data-id="{{ $file->Id }}"
                            data-title="{{ $file->NmFileOriginal }}" data-url="/core/storage/{{ $file->Id }}"
                            class="text-danger deleteData float-right"><i class="icon-feather-trash-2"></i></span>
                    </li>
                    @endforeach
                </ul>
                @endif
            </div>
        </div>
        @endif
    </div>
    <div class="col-lg-6">
        <div class="form-group row">
            <label class="col-form-label col-sm-3 align-self-start" for=""> Pelapor Layanan <sup class="text-danger">*</sup></label>
            <div class=" col-sm-8 col-form-label align-self-start">

                @if($data->showForm)
                <div class="input-group">
                    <input type="hidden" name="KdUnitOrg" id="KdUnitOrg"
                        value="{{ $data->layanan->KdUnitOrg ?? auth()->user()->pegawai->KdUnitOrg }}">
                    <input type="hidden" name="NmUnitOrg" id="NmUnitOrg"
                        value="{{ $data->layanan->NmUnitOrg ?? auth()->user()->pegawai->NmUnitOrg }}">
                    <input type="hidden" name="NmUnitOrgInduk" id="NmUnitOrgInduk"
                        value="{{ $data->layanan->NmUnitOrgInduk ?? auth()->user()->pegawai->NmUnitOrgInduk }}">
                    <input type="hidden" name="NmPeg" id="NmPeg"
                        value="{{ $data->layanan->NmPeg ?? auth()->user()->pegawai->NmPeg }}">
                    <input type="hidden" name="Nip" id="Nip"
                        value="{{ $data->layanan->Nip ?? auth()->user()->pegawai->Nip }}">
                    <input style="cursor: pointer;" readonly class="form-control lookup-pegawai" placeholder=""
                        id="NmPegawai"
                        value="{{ $data->layanan ? $data->layanan->Nip .'-'.$data->layanan->pelapor->NmPeg : auth()->user()->NIP.'-'.auth()->user()->pegawai->NmPeg }}">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary lookup-pegawai" type="button"><i
                            data-feather='search'></i></button>
                        <button class="btn btn-success lookup-layanan ml-3" type="button"><i
                                class="icon-share" title="Histori Layanan"></i></button>
                    </div>
                </div>
                @else
                {{ $data->layanan->Nip .'-'.$data->layanan->pelapor->NmPeg }}
                @endif
                <div id="NmJabatan">{{ isset($data->layanan->Id) ? $data->layanan->pelapor->NmJabatan :
                    auth()->user()->pegawai->NmJabatan }}</div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-sm-3 align-self-start" for=""> Satker Pelapor</label>
            <div class=" col-sm-8 col-form-label align-self-start">
                <div id="NmUnitOrgPelapor">{{ $data->layanan->pelapor->NmUnitOrg ??
                    auth()->user()->pegawai->NmUnitOrg }}</div>
                <div id="NmUnitOrgIndukPelapor">
                    @if($data->layanan && isset($data->layanan->pelapor) &&  $data->layanan->pelapor->NmUnitOrgInduk )
                    @if($data->layanan->pelapor->NmUnitOrgInduk!=$data->layanan->pelapor->NmUnitOrg)
                    {{ $data->layanan->pelapor->NmUnitOrgInduk }}
                    @endif
                    @else
                    {{ auth()->user()->pegawai->NmUnitOrgInduk }}
                    @endif
                </div>
            </div>
        </div>
        <div class="form-group row layanan" style="display: none">
            <label class="col-form-label col-sm-3 align-self-start" for=""> Penerima Layanan<sup class="text-danger">*</sup></label>
            <div class=" col-sm-8 col-form-label align-self-start">
                {{ $data->layanan->NipLayanan.'-'.optional($data->layanan->penerima)->NmPeg }}
                <div id="NmJabatanLayanan">{{ isset($data->layanan->Id) ?
                    optional($data->layanan->penerima)->NmJabatan :
                    auth()->user()->pegawai->NmJabatan }}</div>
            </div>
        </div>
        <div class="form-group row layanan" style="display: none">
            <label class="col-form-label col-sm-3 align-self-start" for=""> Satker Penerima </label>
            <div class=" col-sm-8 col-form-label align-self-start">
                <div id="NmUnitOrgLayanan">{{ $data->layanan->penerima->NmUnitOrg ??
                    auth()->user()->pegawai->NmUnitOrg }}</div>
                <div id="NmUnitOrgIndukLayanan">

                    @if( $data->layanan && isset($data->layanan->penerima) && $data->layanan->penerima->NmUnitOrgInduk )
                    @if($data->layanan->penerima->NmUnitOrgInduk!=$data->layanan->penerima->NmUnitOrg)
                    {{ $data->layanan->penerima->NmUnitOrgInduk }}
                    @endif
                    @else
                    {{ auth()->user()->pegawai->NmUnitOrgInduk }}
                    @endif
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-sm-3 align-self-start" for=""> Nomor Kontak <sup class="text-danger">*</sup></label>
            <div class=" col-sm-9 col-form-label align-self-start">
                @if($data->showForm)
                <input class=" form-control" placeholder="Nomor Kontak" required="" name="NomorKontak" id="NomorKontak"
                    value="{{ $data->layanan->NomorKontak ?? auth()->user()->pegawai->NoHp }}">
                @else
                {{ $data->layanan->NomorKontak ?? auth()->user()->pegawai->NoHp }} {{ $data->layanan->pelapor->Email  }}
                @endif
            </div>
            @if($data->showForm)
            <label class="col-form-label col-sm-4" for=""><sup class="text-danger"> * Harap Isi dengan Nomor Kontak
                    Aktif</sup></label>
            @endif

        </div>
        <div class="form-group row">
            <label class="col-form-label col-sm-3 align-self-start" for="">Dibuat Pada</label>
            <div class="col-sm-7 col-form-label align-self-start">
                {{ ToDmyHi($data->layanan->CreatedAt) }}
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-sm-3 align-self-start" for="">Operator</label>
            <div class=" col-sm-7 col-form-label align-self-start">
                {{ $data->layanan->NipOperatorOpen ?? auth()->user()->NIP }} - {{ isset($data->layanan->NipOperatorOpen)
                ?
                $data->layanan->operatorOpen->NmPeg : auth()->user()->pegawai->NmPeg }}
            </div>
        </div>
    </div>
    @endif
    @if(request()->pending)
    <div class="col-lg-12">

        <div class="text-center form-buttons-w">

            <input type="hidden" name="pending" id="pending"
            value="{{ request()->pending }}">
            <a class="btn btn-success" href="{{ route('layanan.index') }}?pending=1">
                Kembali</a>
            <button class="btn btn-primary" type="submit">
                Submit</button>
        </div>
    </div>
    @endif
</div>
