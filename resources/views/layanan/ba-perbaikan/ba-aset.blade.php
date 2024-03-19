<!DOCTYPE html>
<html>

<head>
    <title>BERITA ACARA  {{ request()->jenis=='terima' ? 'PERBAIKAN' :'PENGEMBALIAN' }} PERANGKAT TI</title>
</head>

<body>
    <style type="text/css">
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 10pt
        }

        tr td {
            vertical-align: top;
        }
        p {
            margin: 0px
        }
    </style>
    <center>
        <h5>BERITA ACARA {{ request()->jenis=='terima' ? 'PERBAIKAN' :'PENGEMBALIAN' }} PERANGKAT TI  <br> No. : {{ request()->jenis=='terima' ? $data->NoBA :$data->NoBAPengembalian }} <br> {{ $data->layanan->NoTicket }}</h5>
    </center>
    <span>
        @php
        $date = \Carbon\Carbon::parse(request()->jenis=='terima'? $data->TglBA:$data->TglKembali)->locale('id_ID');
        @endphp
        Pada hari ini {{ $date->dayName }}, tanggal {{ $date->format('d') }} bulan {{ $date->monthName }} tahun {{
        substr(request()->jenis=='terima'? $data->TglBA:$data->TglKembali,0,4) }} , para pihak sebagaimana disebutkan dibawah ini:
    </span>
    <table class='table table-bordered' width="70%">
        <tr>
            <td width="5%">1</td>
            <td width="25%">Nama</td>
            <td width="5%">:</td>
            <td>{{ request()->jenis=='terima' ? optional($data->layanan->pelapor)->NmPeg : optional($data->pengembali)->NmPeg }}</td>
        </tr>
        <tr>
            <td></td>
            <td>NIP</td>
            <td>:</td>
            <td>{{ request()->jenis=='terima' ? optional($data->layanan->pelapor)->Nip : $data->NipPengembalianAset }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Unit Organisasi</td>
            <td>:</td>
            <td>
                @if (request()->jenis=='terima')
                    {{ optional($data->layanan->pelapor)->NmUnitOrg }} <br> {{ optional($data->layanan->pelapor)->NmUnitOrgInduk }}
                @else
                    {{ optional($data->pengembali)->NmUnitOrg }} <br> {{ optional($data->pengembali)->NmUnitOrgInduk }}
                @endif
            </td>
        </tr>
        <tr>
            <td></td>
            <td>No HP</td>
            <td>:</td>
            <td>{{ $data->layanan->NomorKontak}}</td>
        </tr>
        <tr>
            <td></td>
            <td>Ruang</td>
            <td>:</td>
            <td>{{ $data->Ruang }}</td>
        </tr>
        <tr>
            <td></td>
            <td colspan="3">Untuk selanjutnya disebut sebagai <b>PIHAK PERTAMA</b></td>
        </tr>
    </table> <br>
    <table class='table table-bordered' width="70%">
        <tr>
            <td width="5%">2</td>
            <td width="25%">Nama</td>
            <td width="5%">:</td>
            <td>{{ request()->jenis=='terima' ? $data->pihak2->NmPeg:optional($data->pihak2pengembalian)->NmPeg }}</td>
        </tr>
        <tr>
            <td></td>
            <td>NIP</td>
            <td>:</td>
            <td>{{ request()->jenis=='terima' ? $data->NipPihak2 : $data->NipPengembalianAsetPihak2 }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Jabatan</td>
            <td>:</td>
            <td>{{ request()->jenis=='terima' ? $data->NmJabatanPihak2 : $data->NmJabatanPengembalianPihak2 }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Unit Organisasi</td>
            <td>:</td>
            <td>{{ request()->jenis=='terima' ? $data->NmUnitOrgPihak2 : $data->NmUnitOrgPengembalianPihak2 }} </td>
        </tr>
        <tr>
            <td></td>
            <td colspan="3">
                Untuk selanjutnya disebut sebagai <b>PIHAK KEDUA</b></td>
        </tr>
    </table> <br>
    Bahwa <b>PIHAK KEDUA</b> akan melaksanakan {{ request()->jenis=='terima' ? 'perbaikan' :'pengembalian' }} perangkat TI <b>PIHAK PERTAMA</b> dengan rincian sebagai berikut:
    <br><br>
    <table width="100%" border="1" style="border-collapse: collapse;border-style: solid;
    border-color: black">
        <tr>
            <th>No</th>
            <th>Keterangan Aset</th>
        </tr>
        <tr>
            <td>
                <center>1</center>
            </td>
            <td valign="top">
                <b>
                    <table border="0" width="100%" style="padding: 0;margin: 0">
                        <tr>
                            <th style="text-align: left">No IKN  </th>
                            <th style="text-align: left">SN  </th>
                            <th style="text-align: left">Aset</th>
                        </tr>
                        <tr>
                            <td>
                                @if($data->AsetLayananId)
                                {!! strip_tags(nl2br($data->asetLayanan->NoIkn1),"<p><br>").' <br>'.strip_tags(nl2br($data->asetLayanan->NoIkn2),"<p><br>") !!}
                                @elseif($data->asetSMA)  
                                {{ $data->asetSMA->no_ikn }}
                                @endif
                            </td>
                            <td>
                                @if($data->AsetLayananId)
                                {{ $data->asetLayanan->SerialNumber }} 
                                @elseif($data->asetSMA)  
                                {{ $data->asetSMA->keterangan }}
                                @endif
                            </td>
                            <td>
                                @if($data->AsetLayananId)
                                {{ $data->asetLayanan->JenisAset }} {{ $data->asetLayanan->TypeAset }} {{ $data->asetLayanan->Nama
                                }}
                                @elseif($data->asetSMA)  
                                {{ $data->asetSMA->nm_brg }} {{ $data->asetSMA->nm_lgkp_brg }}
                                @endif
                            </td>
                        </tr>
                    </table>
                </b>
                @if(request()->jenis=='terima')
                Fisik : {{ $data->Fisik }}
                    <br>Kelengkapan : {{ $data->Kelengkapan }}
                    <br>Data : {{ $data->Data }}
                    <br>No Box : {{ $data->NoBox }}
                    <br><span>Keterangan Lain : {!! strip_tags($data->Keterangan) !!}</span>
                @else
                {!! strip_tags(nl2br($data->KeteranganPengembalian),"<p><br>") !!}
                @endif
            </td>
        </tr>
    </table>
        <br><br>
    
    <table width="100%">
        <tr>
            <td width="50%" align="center"><b>PIHAK PERTAMA</b> </td>
            <td width="50%" align="center"><b>PIHAK KEDUA</b> </td>
        </tr>
        <tr>
            <td width="50%" align="center"><br><br><br><br></td>
            <td width="50%" align="center"><br><br><br><br></td>
        </tr>

        <tr>            
            <td width="50%" align="center">{{ optional($data->layanan->pelapor)->NmPeg }} <br> {{ $data->layanan->Nip }}</td>
            <td width="50%" align="center">{{ request()->jenis=='terima' ? $data->pihak2->NmPeg:optional($data->pihak2pengembalian)->NmPeg }} <br>{{ request()->jenis=='terima' ? $data->NipPihak2 : $data->NipPengembalianAsetPihak2 }} </td>
        </tr>
    </table><br><br>
    <table width="100%">
        <tr>
            <td width="33%" align="center"></td>
            <td  align="center">Mengetahui, <br> {{ $data->NmJabatanPejabat }}</td>
            <td width="33%" align="center"></td>
        </tr>
        <tr>
            <td width="33%" align="center"><br><br><br><br></td>
            <td align="center"><br><br><br><br></td>
            <td width="33%" align="center"><br><br><br><br></td>
        </tr>

        <tr>
            <td width="33%" align="center"></td>
            <td  align="center"> {{ $data->NmPegTtdPejabat }} <br> {{ $data->NipTtdPejabat}}</td>
            <td width="33%" align="center"></td>
        </tr>
    </table>
</body>

</html>