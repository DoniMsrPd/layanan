@php
    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=monitoring.xls");  //File name extension was wrong
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private",false);
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
</head>

<body>
    <table border="1" style="border-collapse: collapse;">
        <thead>
            <th>No</th>
            <th>No Konseli</th>
            <th>Nama Pegawai</th>
            <th>Unit Organisasi</th>
            <th>Jenis Kelamin</th>
            <th>Tanggal Lahir</th>
            <th>Jabatan</th>
            <th>Tanggal Sesi Konseling</th>
            <th>Nama Konseli</th>
            <th> Lokasi ECC</th>
            <th>Nama Psikolog/Konselor/Coach</th>
            <th>Status</th>
            <th>Jenis Konseling</th>
            <th>Durasi Konseling</th>
            <th>Permasalahan</th>
            <th>Sub Masalah</th>
            <th>Pelaksanaan Konseling</th>
            <th>Catatan Psikolog/Konselor/Coach</th>
            <th>Penginput Data</th>
            <th>Catatan PIC</th>
        </thead>
        <tbody>

            @foreach ($datas  as $dt)
                <tr>
                    <td valign="top"> {{ $loop->iteration }}</td>
                    <td valign="top">{{ $dt->NoKonseli }}</td>
                    <td valign="top">{{ $dt->pegawai->NmPeg }}</td>
                    <td valign="top">{{ getNmUnitOrg($dt->KdUnitOrg) }} <br> {{ getNmUnitOrgInduk($dt->KdUnitOrg) }}</td>
                    <td valign="top">{{ $dt->pegawai->NmKelamin }}</td>
                    <td valign="top">{{ dateOutput($dt->pegawai->TglLahir) }}</td>
                    <td valign="top">{{ $dt->pegawai->NmJabatan }}</td>
                    <td valign="top">{{ $dt->jadwalKonseling ? dateOutput($dt->jadwalKonseling->Tanggal): '' }}</td>
                    <td valign="top">{{ $dt->NmPeg }}</td>
                    <td valign="top">{{ $dt->regional->Nama??'' }}</td>
                    <td valign="top">{{ $dt->konselor->Nama ?? '' }}</td>
                    <td valign="top">
                        {{ $dt->tahapan->Nama ?? '' }} <br>
                        @if($dt->AlasanPerubahan) <span class="text-primary">{{ $dt->AlasanPerubahan }}</span> <br> @endif
                        @if($dt->AlasanPenolakan) <span class="text-danger">{{ $dt->AlasanPenolakan }}</span> <br> @endif
                        @if($dt->AlasanPembatalan) <span class="text-warning">{{ $dt->AlasanPembatalan }}</span> <br> @endif
                        <span class="text-info">{{ $dt->statusRekomendasi->Nama ?? '' }}</span>
                    </td>
                    <td valign="top">{{ $dt->status->Nama ?? '' }}</td>
                    <td valign="top">{{ $dt->hasil ? $dt->hasil->Jam.' Jam':'' }}  {{ $dt->hasil ? $dt->hasil->Menit.' Menit':'' }} </td>
                    <td valign="top">{{ $dt->hasil->permasalahan->Nama ?? '' }}</td>
                    <td valign="top">{{ $dt->hasil->subMasalah->Nama ?? '' }}</td>
                    <td valign="top">{{ $dt->hasil ? dateOutput($dt->hasil->TglKonselingRealisasi):'' }}</td>
                    <td valign="top">{{ $dt->hasil->validasiCatatan ?? '' }}</td>
                    <td valign="top">{{ $dt->createdBy->NmPeg ?? '' }}</td>
                    <td valign="top">{{ $dt->CatatanHasilKonseling }} </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>