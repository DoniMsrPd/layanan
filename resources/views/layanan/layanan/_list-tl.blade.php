@if ($data->tl->count() > 0)
<style>
    .tab-content .avatar {
        float: left;
        margin-right: 10px;
    }

    .tab-content .avatar img {
        width: 40px;
        border-radius: 50%;
        height: 40px;
    }
</style>
@foreach ($data->tl as $tl)
<div class="avatar">
    <img href="https://sisdm.bpk.go.id/photo/{{ $tl->Nip }}/sm.jpg" target="_blank"
        src="https://sisdm.bpk.go.id/photo/{{ $tl->Nip }}/sm.jpg">
</div>
<div class="body">
    <div class="card mb-3">
        <div class="card-header">
            <b>{{ optional($tl->pegawai)->NmPeg }}</b>, <span class="text-detail" style="display: unset; !important">
                Diperbaharui {{ ToDmyHi($tl->UpdatedAt??$tl->CreatedAt) }} </span>

            <span @if(request()->merge) style="display:none" @endif>
                @if((($data->kdUnit == '100205000000' && auth()->user()->can('layanan.tl.update-all'))||(auth()->user()->NIP==$tl->CreatedBy))&&!$tl->layanan->DeletedAt)
                <i class="float-right edit-tl btn btn-sm btn-primary" data-id="{{ $tl->Id }}" data-title="Edit"
                    title="Edit TindakLanjut" data-url="/layanan-tl/{{ $tl->Id }}" data-method="PATCH">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                </i>
                @endif
                @if((($data->kdUnit == '100205000000' && auth()->user()->can('layanan.tl.update-all'))||(auth()->user()->NIP==$tl->CreatedBy))&&!$tl->layanan->DeletedAt)
                <i class="float-right hapus-logs btn btn-sm btn-danger deleteData"
                    data-layanan_id="{{ $tl->LayananId ?? '' }}" data-id="{{ $tl->Id }}" data-title="TindakLanjut"
                    title="Hapus TindakLanjut" data-url="/layanan-tl/{{ $tl->Id }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                </i>
                @endif
            </span>
        </div>
        <div class="card-body">
            <div class="ur-kegitan">
                @foreach ($tl->status as $status)
                @php $statusAwal = $status->statusAwal->where('KdUnitOrgOwnerLayanan', $tl->layanan->KdUnitOrgOwnerLayanan)->where('Id', $status->RefStatusLayananIdAwal)->first() @endphp
                @php $statusAkhir = $status->statusAwal->where('KdUnitOrgOwnerLayanan', $tl->layanan->KdUnitOrgOwnerLayanan)->where('Id', $status->RefStatusLayananIdAkhir)->first() @endphp
                {{-- {{ $statusAwal }} --}}
                @if (!pegawaiBiasa())
                Status changed @if($statusAwal) from @endif {{ $statusAwal->Nama }} to {{ $statusAkhir->Nama }}
                @endif
                <br>
                @endforeach

                <br>
                @if($tl->layanan->serviceCatalog && $tl->layanan->serviceCatalog->IsPersediaan &&
                $tl->layananPersediaan->count() >0 )

                Persediaan <br>
                <div class="row">
                    <div class="col-md-12">

                        <table class="table table-borderless table-hover" width="100%">
                            <thead style="border-bottom: 1px dashed #ebedf2;">
                                <tr>
                                    <th width="2%" style="font-size:0.7rem">No</th>
                                    <th  style="font-size:0.7rem">Nama</th>
                                    <th width="15%" style="font-size:0.7rem">Qty</th>
                                    <th width="15%" style="font-size:0.7rem">Keterangan</th>
                                    <th width="5%" style="text-align: center;">
                                        @if(auth()->user()->can('ba-persediaan.create'))
                                        <span class="btn-group" role="group">
                                            @if($tl->persediaanDistribusi)
                                            <a data-jenis='persediaan' style="color: white"
                                                class="btn btn-primary btn-sm formBaPeminjaman text-white" title="Edit BA Persediaan"
                                                data-title="Generate BA Persediaan" data-id="{{ $tl->Id }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                            </a>
                                            <a href="{{ route('ba-persediaan.download',['persediaanDistribusi'=>$tl->Id ]) }}"
                                                class="btn btn-warning btn-sm" title="Download BA Persediaan"
                                                target="_blank">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                                            </a>
                                            @else
                                            <a data-jenis='persediaan' style="color: white"
                                                class="btn btn-primary btn-sm formBaPeminjaman text-white"
                                                data-title="Generate BA Persediaan" data-id="{{ $tl->Id }}" title="Generate BA Persediaan"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-refresh-ccw"><polyline points="1 4 1 10 7 10"></polyline><polyline points="23 20 23 14 17 14"></polyline><path d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15"></path></svg></a>
                                            @endif
                                        </span>
                                        @endif
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tl->layananPersediaan as $key => $item)
                                <tr>
                                    <td  style="font-size:0.8rem">{{ $key+1 }}</td>
                                    <td style="font-size:0.8rem">{{ $item->mstPersediaan->KdBrg }} {{
                                        $item->mstPersediaan->NmBrg }} {{ $item->mstPersediaan->NmBrgLengkap }}
                                        <br>
                                        @if(!($item->AsetLayananId||$item->AsetSMAId))
                                        <a class="mb-2 mr-2 btn btn-primary btn-sm text-white mappingPersediaan" title="Mapping" data-id="{{ $item->Id }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-folder-plus"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path><line x1="12" y1="11" x2="12" y2="17"></line><line x1="9" y1="14" x2="15" y2="14"></line></svg>
                                        </a>
                                        @else
                                        No IKN :  {!! strip_tags(nl2br($item->NomorIKN),"<p><br>") !!} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  SN : {!! strip_tags(nl2br($item->SN),"<p><br>")!!} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  Aset : {!! strip_tags(nl2br($item->NmBrg),"<p><br>") !!} &nbsp;&nbsp;
                                        <a style="padding:3px 3px;margin:0px" class="btn btn-danger btn-sm deleteData"
                                            data-id="{{ $item->Id }}" href="javascript:void(0)"
                                            data-layanan_id="{{ $tl->LayananId ?? '' }}"
                                            data-mapping="1"
                                            data-url="/layanan-aset/{{ $item->Id }}" title="Hapus"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg></a>
                                        @endif
                                    </td>
                                    <td  style="font-size:0.8rem">{{ $item->Qty  }}</td>
                                    <td  style="font-size:0.8rem">{!! strip_tags(nl2br($item->Keterangan),"<p><br>") !!}</td>
                                    <td  class="text-center">
                                        @if((auth()->user()->can('layanan.tl.delete-all')||(auth()->user()->NIP==$item->createdBy))&&!$tl->layanan->DeletedAt)
                                        <a style="padding:3px 3px;margin:0px" class="btn btn-danger btn-sm deleteData"
                                            data-id="{{ $item->Id }}" href="javascript:void(0)"
                                            data-title="{{ $item->mstPersediaan->KdBrg  }}"
                                            data-url="/layanan-tl/persediaan/{{ $item->Id }}" title="Hapus"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg></a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <br>
                @endif
                @if($tl->layanan->serviceCatalog && $tl->layanan->serviceCatalog->IsPerbaikan &&
                $tl->layananAset->count() >0 )

                Aset TI <br>
                <div class="row">
                    <div class="col-md-12">

                        <table class="table table-borderless table-hover" width="100%">
                            <thead style="border-bottom: 1px dashed #ebedf2;">
                                <tr>
                                    <th style="font-size:0.7rem"  colspan="4">Detail Aset</th>
                                    <th width="20%" style="font-size:0.7rem"></th>
                                    <th width="5%" style="text-align: center;"></th>
                                    <th width="5%" style="text-align: center;"></th>
                                    <th width="5%" style="text-align: center;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tl->layananAset as $key => $item)
                                <tr>
                                    <td colspan="4" style="font-size:0.8rem ;  vertical-align:top">
                                        No IKN :  {!! strip_tags(nl2br($item->NomorIKN),"<p><br>") !!} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  SN : {!! strip_tags(nl2br($item->SN),"<p><br>")!!} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  Aset : {!!  strip_tags(nl2br($item->NamaBarang),"<p><br>") !!} <br><br>
                                         Fisik : {{ $item->Fisik }}<br>
                                         Kelengkapan : {{ $item->Kelengkapan }} <br>
                                         Data : {{ $item->Data }}<br>
                                         No Box : {{ $item->NoBox }}
                                    </td>
                                    <td  style="font-size:0.8rem ;  vertical-align:top">Keterangan Aset Lain <br>{!! strip_tags(nl2br($item->Keterangan),"<p><br>") !!}</td>
                                    <td  class="text-center" style=" vertical-align:top">
                                        @if((auth()->user()->can('layanan.tl.delete-all')||(auth()->user()->NIP==$item->createdBy))&&!$tl->layanan->DeletedAt)
                                        <a style="padding:3px 3px;margin:0px" class="btn btn-danger btn-sm deleteData"
                                            data-id="{{ $item->Id }}" href="javascript:void(0)"
                                            data-title="{{ $item->asetLayanan->Nama ?? $item->asetSma->nm_lgkp_brg }}"
                                            data-url="/layanan-tl/aset/{{ $item->Id }}" title="Hapus"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg></a>
                                        @endif
                                    </td>
                                    <td style=" vertical-align:top">
                                        <span class="btn-group" role="group">
                                            @if($item->NoBA)
                                            <a style="color: white" class="btn btn-primary btn-sm formBaAset text-white"
                                                data-jenis="terima" data-title="Generate BA Perbaikan" title="Edit BA Perbaikan"
                                                data-id="{{ $item->Id }}"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></a>
                                            <a href="{{ route('ba-perbaikan.download',['layananAset'=>$item->Id ]) }}?jenis=terima"
                                                class="btn btn-warning btn-sm" title="Download BA Perbaikan"
                                                target="_blank">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                                            </a>
                                            @else
                                            <a style="color: white" class="btn btn-primary btn-sm formBaAset text-white"
                                                data-jenis="terima" data-title="Generate BA Perbaikan" title="Generate BA Perbaikan"
                                                data-id="{{ $item->Id }}"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-refresh-ccw"><polyline points="1 4 1 10 7 10"></polyline><polyline points="23 20 23 14 17 14"></polyline><path d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15"></path></svg></a>
                                            @endif
                                        </span>
                                    </td>
                                    <td  style=" vertical-align:top">
                                        <span class="btn-group" role="group">
                                            @if($item->NoBAPengembalian)
                                            <a style="color: white" class="btn btn-primary btn-sm formBaAset text-white"
                                                data-jenis="ambil" data-title="Generate BA Pengambilan" title="Edit BA Pengembalian"
                                                data-id="{{ $item->Id }}"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></a>
                                            <a href="{{ route('ba-perbaikan.download',['layananAset'=>$item->Id ]) }}?jenis=ambil"
                                                class="btn btn-warning btn-sm" title="Download BA Pengembalian"
                                                target="_blank">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                                            </a>
                                            @else
                                            <a style="color: white" class="btn btn-primary btn-sm formBaAset text-white"
                                                data-jenis="ambil" data-title="Generate BA Pengambilan" title="Generate BA Pengembalian"
                                                data-id="{{ $item->Id }}"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-refresh-ccw"><polyline points="1 4 1 10 7 10"></polyline><polyline points="23 20 23 14 17 14"></polyline><path d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15"></path></svg></a>
                                            @endif
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <br>
                @endif
                @if($tl->layanan->serviceCatalog && $tl->layanan->serviceCatalog->IsPeminjaman &&
                $tl->layananPeminjaman && $tl->layananPeminjaman->peminjamanDetail->count() >0)

                Peminjaman Aset <br>
                <div class="row">
                    <div class="col-md-12">

                        <table class="table table-borderless table-hover">
                            <thead style="border-bottom: 1px dashed #ebedf2;">
                                <tr>
                                    <th width="4%" style="font-size:0.7rem">No</th>
                                    <th width="25%" style="font-size:0.7rem">No IKN</th>
                                    <th width="20%" style="font-size:0.7rem">Serial Number</th>
                                    <th width="35%" style="font-size:0.7rem">Nama</th>
                                    <th style="font-size:0.7rem">Keterangan</th>
                                    <th width="5%" style="text-align: center;">
                                        @if(auth()->user()->can('ba-peminjaman.create'))
                                        <span class="btn-group" role="group">
                                            @if($tl->layananPeminjaman->NoBA)
                                            <a data-jenis='pinjam' style="color: white"
                                                class="btn btn-primary btn-sm formBaPeminjaman text-white" title="Edit BA Peminjaman"
                                                data-title="Generate BA Peminjaman"
                                                data-id="{{ $tl->layananPeminjaman->Id }}"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></a>
                                            <a href="{{ route('ba-peminjaman.download',['peminjaman'=>$tl->layananPeminjaman->Id ]) }}?jenis=pinjam"
                                                class="btn btn-warning btn-sm" title="Download BA Peminjaman"
                                                target="_blank">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                                            </a>
                                            @else
                                            <a data-jenis='pinjam' style="color: white"
                                                class="btn btn-primary btn-sm formBaPeminjaman text-white"
                                                data-title="Generate BA Peminjaman" title="Generate BA Peminjaman"
                                                data-id="{{ $tl->layananPeminjaman->Id }}"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-refresh-ccw"><polyline points="1 4 1 10 7 10"></polyline><polyline points="23 20 23 14 17 14"></polyline><path d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15"></path></svg></a>
                                            @endif
                                        </span>
                                        @endif

                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tl->layananPeminjaman->peminjamanDetail as $key => $item)
                                <tr>
                                    <td style="font-size:0.8rem">{{ $key+1 }}</td>
                                    <td style="font-size:0.8rem"> {!! strip_tags(nl2br($item->NomorIKN),"<p><br>") !!}</td>
                                    <td style="font-size:0.8rem"> {!! strip_tags(nl2br($item->SN),"<p><br>") !!}</td>
                                    <td style="font-size:0.8rem"> {!! strip_tags(nl2br($item->NamaBarang),"<p><br>") !!}</td>
                                    <td style="font-size:0.8rem">{!! strip_tags(nl2br($item->KeteranganPeminjaman),"<p><br>") !!}</td>
                                    <td class="text-center">
                                        @if((auth()->user()->can('layanan.tl.delete-all')||(auth()->user()->NIP==$item->createdBy))&&!$tl->layanan->DeletedAt)
                                        <a style="padding:3px 3px;margin:0px" class="btn btn-danger btn-sm deleteData"
                                            data-id="{{ $item->Id }}" href="javascript:void(0)"
                                            data-title="{{ $item->asetLayanan->Nama}}"
                                            data-url="/layanan-tl/peminjaman-detail/{{ $item->Id }}" title="Hapus"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg></a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <br>
                @endif
                {!! strip_tags(nl2br($tl->Keterangan),"<p><br>") !!}
            </div>
            @if ($tl->files->count() > 0)
            <hr>
            <p><strong>File</strong></p>
            <ul class="pt-1 mb-0 list-file" style="padding: 20px; width: 50%;">
                @foreach ($tl->files as $key => $file)
                <li>
                    <a href="/core/{{ $file->PathFile }}" class="f-16" target="_blank">
                        <span class="mdi mdi-file-pdf"></span> {{
                        \Illuminate\Support\Str::limit($file->NmFileOriginal, 60) }}
                    </a>
                    @if((auth()->user()->can('layanan.tl.delete-all')||(auth()->user()->NIP==$file->createdBy))&&!$tl->layanan->DeletedAt)
                    <span style="cursor: pointer;" data-id="{{ $file->Id }}" data-title="{{ $file->NmFileOriginal }}"
                        data-url="/core/storage/{{ $file->Id }}" class="text-danger deleteData float-right"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg></span>
                    @endif
                </li>
                @endforeach
            </ul>
            @endif
            @if ($tl->filesOld->count() > 0)
            <hr>
            <p><strong>File</strong></p>
            <ul class="pt-1 mb-0 list-file" style="padding: 20px; width: 50%;">
                @foreach ($tl->filesOld as $key => $file)
                <li>
                    <a href="/core/{{ $file->PathFile }}&NmFile={{ $file->NmFile }}" class="f-16" target="_blank">
                        <span class="mdi mdi-file-pdf"></span> {{
                        \Illuminate\Support\Str::limit($file->NmFileOriginal, 60) }}
                    </a>
                    @if((auth()->user()->can('layanan.tl.delete-all')||(auth()->user()->NIP==$file->createdBy))&&!$tl->layanan->DeletedAt)
                    <span style="cursor: pointer;" data-id="{{ $file->Id }}" data-title="{{ $file->NmFileOriginal }}"
                        data-url="/core/storage/{{ $file->Id }}" class="text-danger deleteData float-right"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg></span>
                    @endif
                </li>
                @endforeach
            </ul>
            @endif

        </div>

    </div>
</div>
@endforeach
@else
<div class="alert alert-info" role="alert"><strong>Info !</strong> Belum ada Tindak Lanjut.</div>
@endif
