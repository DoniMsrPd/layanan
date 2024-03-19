@extends('core.layouts.master')

@section('content')
<div class="element-wrapper">
    <div class="element-box">

        <h5 class="form-header">
            Form Pengembalian Aset <br>
            {{-- {{ $data->peminjaman->NoBA }} {{ $data->peminjaman->layanan->NoTicket }} --}}

            <span class="btn-group" role="group" style=" float: right;">
                <a class="mb-2 mr-2 btn btn-primary btn-sm" href="{{ route('ba-peminjaman.index') }}">Kembali</a>
            </span>
        </h5>

        <table  width="100%">
            @if($data->peminjaman->NipPihak1)
            <tr>
                <td width="10%">Nama</td>
                <td width="2%">:</td>
                <td>{{ $data->peminjaman->NmPihak1 }}</td>
            </tr>
            <tr>
                <td>NIP</td>
                <td>:</td>
                <td>{{ $data->peminjaman->NipPihak1 }}</td>
            </tr>
            <tr>
                <td>Unit Organisasi</td>
                <td>:</td>
                <td>
                    {{ $data->peminjaman->pihak1->NmUnitOrg }} <br> {{ $data->peminjaman->pihak1->NmUnitOrgInduk }}</td>
            </tr>
            @else
            <tr>
                <td width="10%">Nama</td>
                <td width="2%">:</td>
                <td>{{ $data->peminjaman->NmPihak1Luar }}</td>
            </tr>
            <tr>
                <td>KTP</td>
                <td>:</td>
                <td>{{ $data->peminjaman->NipPihak1Luar }}</td>
            </tr>
            <tr>
                <td>Instansi</td>
                <td>:</td>
                <td>{{ $data->peminjaman->KdUnitOrgPihak1Luar }}</td>
            </tr>

            @endif
            <tr>
                <td>Ruang</td>
                <td>:</td>
                <td>{{ $data->peminjaman->Ruang }}</td>
            </tr>
        </table> <br><br>

        <form method="POST" action="#" id="pengembalianForm" enctype="multipart/form-data">
            <input type="hidden" name="peminjamanId" value="{{ $data->peminjaman->Id }}">
            <table width="100%" class="table">
                <tr>
                    <th width="2%">No</th>
                    <th width="15%">Nama Aset</th>
                    <th>Keterangan Peminjaman</th>
                    <th width="30%">Keterangan Pengembalian</th>
                    <th width="2%"></th>
                </tr>
                @foreach ($data->peminjaman->peminjamanDetail as $item)
                <tr>
                    <td>
                        <center>{{ $loop->iteration }}</center>
                    </td>
                    <td>
                        {{ $item->asetLayanan->JenisAset }} {{ $item->asetLayanan->TypeAset }} {{
                        $item->asetLayanan->Nama }} <br> {{ $item->NomorIKN }}
                    </td>
                    <td>{!! strip_tags(nl2br($item->KeteranganPeminjaman),"<p><br>") !!}</td>
                    @if(!$item->PengembalianId)
                    <td><textarea class='form-control ckeditor' id="keteranganPengembalian{{ $loop->iteration }}" readonly rows='2' name='keteranganPengembalian[]'></textarea>
                    </td>
                    <td class="text-center">
                        <input type="hidden" name="peminjamanDetailId[]" class="peminjamanDetailId" value="">
                        <input type="checkbox" class="peminjamanDetailIdCheckbox" value="{{ $item->Id }}" data-iteration="{{ $loop->iteration }}">
                    </td>
                    @else
                    <td>{!! strip_tags(nl2br($item->KeteranganPengembalian),"<p><br>") !!}</td>
                    <td></td>
                    @endif
                </tr>

                @endforeach
            </table>
            @if($data->peminjaman->peminjamanDetailBelumDikembalikan->count()>0)
            <div class="text-center form-buttons-w">
                <button class="btn btn-primary btn-sm" type="submit">
                    Submit</button>
            </div>
            @endif
        </form>
    </div>
</div>
@include('layanan.layanan._form-ba-peminjaman')
@include('core.modals.pegawai6')
@include('core.modals.pegawai7')
@include('core.modals.pegawai8')
@endsection
@push('scripts')
<script>
    $(function() {
        $(document).on("change",'#isPihakLuar',function () {
            if($(this).is(":checked")){
                $('.pihak1luar').show()
                $('#pihak1dalam').hide()
            } else {
                $('#pihak1dalam').show()
                $('.pihak1luar').hide()
            }
        });
        $(document).on("change",'.peminjamanDetailIdCheckbox',function () {
            id = $(this).data('iteration')
            if($(this).is(":checked")){
                CKEDITOR.instances['keteranganPengembalian'+id].setReadOnly(false);
                $(this).closest('.text-center').find(".peminjamanDetailId").val($(this).val())
            } else {
                CKEDITOR.instances['keteranganPengembalian'+id].setReadOnly(true);
                $(this).closest('.text-center').find(".peminjamanDetailId").val(null)
            }
        });
        $(document).on("submit",'#pengembalianForm',function (event) {
            event.preventDefault();
            if($(".peminjamanDetailIdCheckbox:checked").length==0){
                alert('Pilih Aset yang akan dikembalikan')
                return false
            }
            $('#modal-title-baPeminjaman').html('Generate BA Pengembalian')
            var _token = $('meta[name="csrf-token"]').attr('content');
            console.log($('#pengembalianForm').serialize());
            $.ajax({
                url: '{{ route('ba-peminjaman.getDetailPengembalian') }}',
                type: "GET",
                data: $('#pengembalianForm').serialize(),
                beforeSend: function (response){
                    $('#loadingAnimation').addClass('lds-dual-ring');
                },
                success: function (response) {
                    $('#formPeminjaman [name="NoBA"]').val(response.data.noBa)
                    TglBA = '{{ date('Y-m-d') }}'
                    peminjaman=response.data.peminjaman
                    $('#formPeminjaman [name="Ruang"]').val(peminjaman.Ruang)
                    KdUnitOrgPihak2 = '{{ auth()->user()->pegawai->KdUnitOrg }}'
                    NmUnitOrgPihak2 = '{{ auth()->user()->pegawai->NmUnitOrg }}'
                    NmJabatanPihak2 = '{{ auth()->user()->pegawai->NmJabatan }}'
                    NipPihak2 = '{{ auth()->user()->NIP }} '
                    NmPegawaiPihak2 = '{{ auth()->user()->NIP }} - {{ auth()->user()->pegawai->NmPeg }}'
                    NmUnitOrgPihak2 = '{{ auth()->user()->pegawai->NmUnitOrg }}'
                    NmJabatanPihak2 = '{{ auth()->user()->pegawai->NmJabatan }}'
                    $('#formPeminjaman [name="KdUnitOrgPihak2"]').val(KdUnitOrgPihak2)
                    $('#formPeminjaman [name="NmUnitOrgPihak2"]').val(NmUnitOrgPihak2)
                    $('#formPeminjaman [name="NmJabatanPihak2"]').val(NmJabatanPihak2)
                    $('#formPeminjaman [name="NipPihak2"]').val(NipPihak2)
                    $('#formPeminjaman [name="PegawaiPihak2"]').val(NmPegawaiPihak2)
                    $('#NmUnitOrgPihak2').html(NmUnitOrgPihak2)
                    $('#NmJabatanPihak2').html(NmJabatanPihak2)
                    // pejabat
                    $('#formPeminjaman [name="NmJabatanPejabat"]').val('Kepala {{ pejabatTTD()->NmUnitOrg }}')
                    $('#formPeminjaman [name="NmPegTtdPejabat"]').val('{{ pejabatTTD()->NmPeg }}')
                    $('#formPeminjaman [name="NipTtdPejabat"]').val('{{ pejabatTTD()->Nip }}')
                    $('#formPeminjaman [name="Pejabat"]').val('{{ pejabatTTD()->Nip }} - {{ pejabatTTD()->NmPeg }}')
                        // pihak 1 dalam
                    $('#formPeminjaman [name="KdUnitOrgPihak1"]').val(peminjaman.KdUnitOrgPihak1 ?? peminjaman.layanan.pelapor.KdUnitOrg)
                    $('#formPeminjaman [name="NmPihak1"]').val(peminjaman.NmPihak1 ?? peminjaman.layanan.pelapor.Nip)
                    $('#formPeminjaman [name="NipPihak1"]').val(peminjaman.NipPengembalianAset ?? peminjaman.layanan.pelapor.Nip)
                    $('#formPeminjaman [name="PegawaiPihak1"]').val( peminjaman.NipPihak1 ? peminjaman.NipPihak1+' - '+peminjaman.NmPihak1 :peminjaman.layanan.pelapor.Nip+' - '+peminjaman.layanan.pelapor.NmPeg)
                    $("#AsetPeminjaman tbody").html(null)
                    peminjamanDetail = response.data.pengembalianDetail
                    for (const property in peminjamanDetail) {
                        let count = parseInt(property)+parseInt(1);
                        let PeminjamanId = peminjamanDetail[property].PeminjamanId
                        let Id = peminjamanDetail[property].Id
                        let AsetLayananId = peminjamanDetail[property].AsetLayananId
                        let KeteranganPeminjaman = peminjamanDetail[property].KeteranganPeminjaman??''
                        let NomorIKN = peminjamanDetail[property].NomorIKN
                        let SN = peminjamanDetail[property].SN ??''
                        let NamaBarang = peminjamanDetail[property].NamaBarang
                        var tablePic = "<tr>\
                                            <td style='font-size:0.8rem'>" + NomorIKN + "</td>\
                                            <td style='font-size:0.8rem'>" + SN + "</td>\
                                            <td style='font-size:0.8rem'>" + NamaBarang + "</td>\
                                            <td style='font-size:0.8rem'>"+KeteranganPeminjaman +"</td>\
                                        </tr>";
                        $("#AsetPeminjaman tbody").append(tablePic)
                    }
                    $('.pihak1luar').hide()
                    $('#modalBaPeminjaman').modal('toggle');
                    $('#loadingAnimation').removeClass('lds-dual-ring');

                },
                error : function () {
                    alert('Terjadi kesalahan, silakan reload');
                }
            })
        });
    })
</script>

@include('ba-peminjaman._script')
@include('core._script-delete')
@endpush