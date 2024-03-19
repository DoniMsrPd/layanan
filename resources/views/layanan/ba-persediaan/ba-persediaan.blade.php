<!DOCTYPE html>
<html>

<head>
    <title>BERITA ACARA  DISTRIBUSI PERSEDIAAN PERANGKAT TI</title>
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
    </style>
    <center>
        <h5>BERITA ACARA DISTRIBUSI PERSEDIAAN PERANGKAT TI  <br> No. : {{ $data->NoBA }} <br> {{ $data->layanan->NoTicket }}</h5>
    </center>
    <span>
        @php
        $date = \Carbon\Carbon::parse($data->TglBA)->locale('id_ID');
        @endphp
        Pada hari ini {{ $date->dayName }}, tanggal {{ $date->format('d') }} bulan {{ $date->monthName }} tahun {{
        terbilang(substr($data->TglBA,0,4)) }} , para pihak sebagaimana disebutkan dibawah ini:
    </span>
    <table class='table table-bordered' width="100%">
        @if($data->NipPihak1)
        <tr>
            <td width="5%">1</td>
            <td width="25%">Nama</td>
            <td width="5%">:</td>
            <td>{{ $data->NmPihak1 }}</td>
        </tr>
        <tr>
            <td></td>
            <td>NIP</td>
            <td>:</td>
            <td>{{ $data->NipPihak1 }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Unit Organisasi</td>
            <td>:</td>
            <td>
                {{ $data->pihak1->NmUnitOrg }} <br> {{ $data->pihak1->NmUnitOrgInduk }}</td>
        </tr>
        @else
        <tr>
            <td width="5%">1</td>
            <td width="25%">Nama</td>
            <td width="5%">:</td>
            <td>{{ $data->NmPihak1Luar }}</td>
        </tr>
        <tr>
            <td></td>
            <td>KTP</td>
            <td>:</td>
            <td>{{ $data->NipPihak1Luar }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Instansi</td>
            <td>:</td>
            <td>{{ $data->KdUnitOrgPihak1Luar }}</td>
        </tr>

        @endif
        <tr>
            <td></td>
            <td colspan="3">Untuk selanjutnya disebut sebagai <b>PIHAK PERTAMA</b></td>
        </tr>
    </table> <br>
    <table class='table table-bordered' width="100%">
        <tr>
            <td width="5%">2</td>
            <td width="25%">Nama</td>
            <td width="5%">:</td>
            <td>{{ $data->pihak2->NmPeg }}</td>
        </tr>
        <tr>
            <td></td>
            <td>NIP</td>
            <td>:</td>
            <td>{{ $data->NipPihak2 }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Jabatan</td>
            <td>:</td>
            <td>{{ $data->NmJabatanPihak2 }}</td>
        </tr>
        <tr>
            <td></td>
            <td>Unit Organisasi</td>
            <td>:</td>
            <td>{{ $data->NmUnitOrgPihak2 }} </td>
        </tr>
        <tr>
            <td></td>
            <td colspan="3">
                Untuk selanjutnya disebut sebagai <b>PIHAK KEDUA</b></td>
        </tr>
    </table> <br>
    Bahwa <b>PIHAK PERTAMA</b> menerima Persediaan perangkat TI dari <b>PIHAK KEDUA</b> dengan rincian sebagai berikut:
    <br><br>
    <table width="100%" border="1" style="border-collapse: collapse;border-style: solid;
    border-color: black:padding:5px">
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Qty</th>
            <th>Keterangan</th>
        </tr>
        @foreach ($data->persediaan as $item)
        <tr>
                
            <td valign="top">
                <center>{{ $loop->iteration }}</center>
            </td>
            <td valign="top">{!! strip_tags(nl2br($item->NamaBarang),"<p><br>") !!}</td>
            <td valign="top" align="center">{!! strip_tags(nl2br($item->Qty),"<p><br>") !!}</td>
            <td>{!! strip_tags(nl2br($item->Keterangan),"<p><br>") !!}</td>
        </tr>
        @endforeach
    </table> <br>
    <table width="100%">
        <tr>
            <td width="50%" align="center"><b>PIHAK KEDUA</b> </td>
            <td width="50%" align="center"><b>PIHAK PERTAMA</b> </td>
        </tr>
        <tr>
            <td width="50%" align="center"><br><br><br><br></td>
            <td width="50%" align="center"><br><br><br><br></td>
        </tr>

        <tr>
            <td width="50%" align="center">{{  $data->pihak2->NmPeg }} <br>{{ $data->NipPihak2 }} </td>
            <td width="50%" align="center">{{ $data->layanan->pelapor->NmPeg }} <br> {{ $data->layanan->Nip }}</td>
        </tr>
    </table>
    <br><br>
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