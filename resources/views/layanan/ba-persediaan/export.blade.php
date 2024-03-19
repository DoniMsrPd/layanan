<?php
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-disposition: attachment; filename=BA_Persediaan_".date('YmdHis').".xls");
?>
<style>
    td {
        vertical-align: top
    }

    .num {
        mso-number-format: General;
    }

    .text {
        mso-number-format: "\@";
        /*force text*/
    }
</style>
<table style="border-collapse: collapse;" border="1">
    <thead>
        <tr>
            <th>No</th>
            <th>No Ticket Tahun</th>
            <th>No Ticket Bulan</th>
            <th>No Ticket Antrian</th>
            <th>No Ticket Random</th>
            <th>No Ticket</th>
            <th>Kode Service Katalog</th>
            <th>Nama Service Katalog</th>
            <th>Nama</th>
            <th>Jam SLA</th>
            <th>Norma Waktu</th>
            <th>Tgl Layanan</th>
            <th>Nip</th>
            <th>Nama Pegawai</th>
            <th>Nama Unit Organisasi</th>
            <th>Nama Unit Organisasi Induk</th>
            <th>Nip Layanan</th>
            <th>Kode Unit Organisasi Layanan</th>
            <th>Prioritas Layanan</th>
            <th>Permintaan Layanan</th>
            <th>Keterangan Layanan</th>
            <th>Nomor Kontak</th>
            <th>Nip Operator Open</th>
            <th>Nip Operator Closed</th>
            <th>Status Layanan</th>
            <th>Tgl Eskalasi</th>
            <th>Tgl Progress</th>
            <th>Tgl Solved</th>
            <th>Tgl Closed</th>
            <th>Group Solver</th>
            <th>Solver</th>
            <th>Created At</th>
            <th>Created By</th>

            <th>Tgl Distribusi</th>
            <th>Tgl Layanan</th>
            <th>No Ticket</th>
            <th>Nip Layanan</th>
            <th>Nm Peg</th>
            <th>Kd Unit Org</th>
            <th>Nm Unit Org</th>
            <th>Nm Unit Org Induk</th>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>Nama Barang Lengkap</th>
            <th>Keterangan</th>
            <th>No IKN</th>
            <th>Aset</th>
            <th>Created By</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
        <tr>

            <td>{{ $loop->iteration }}</td> 
            <td>{{ $item->layanan->NoTicketTahun }}</td>
            <td>{{ $item->layanan->NoTicketBulan }}</td>
            <td>{{ $item->layanan->NoTicketAntrian }}</td>
            <td>{{ strtoupper($item->layanan->NoTicketRandom) }}</td>
            <td>{{ $item->layanan->NoTicket }}</td>
            <td>{{ $item->layanan->ServiceCatalogKode }}</td>
            <td>{{ $item->layanan->ServiceCatalogNama }}</td>
            <td>{{ optional($item->layanan->sla)->Nama }}</td>
            <td>{{ optional($item->layanan->sla)->JamSLA }}</td>
            <td>{{ optional($item->layanan->sla)->NormaWaktu }}</td>
            <td>{{ $item->layanan->TglLayanan }}</td>
            <td>{{ $item->layanan->Nip }}</td>
            <td>{{ $item->layanan->NmPeg }}</td>
            <td class="text">{{ $item->layanan->KdUnitOrg }}</td>
            <td>{{ $item->layanan->NmUnitOrgInduk }}</td>
            <td>{{ $item->layanan->NipLayanan }}</td>
            <td class="text">{{ $item->layanan->KdUnitOrgLayanan }}</td>
            <td>{{ $item->layanan->PrioritasLayanan }}</td>
            <td> {!! strip_tags(nl2br($item->layanan->PermintaanLayanan),"<p><br>") !!}</td>
            <td> {!! strip_tags(nl2br($item->layanan->KeteranganLayanan),"<p><br>") !!}</td>
            <td class="text">{{ $item->layanan->NomorKontak }}</td>
            <td>{{ $item->layanan->NipOperatorOpen }}</td>
            <td>{{ $item->layanan->NipOperatorClosed }}</td>
            <td>{{ optional($item->layanan->status)->Nama }}</td>
            <td>{{ ToDmyHi($item->layanan->TglEskalasi) }}</td>
            <td>{{ ToDmyHi($item->layanan->TglProgress) }}</td>
            <td>{{ ToDmyHi($item->layanan->TglSolved) }}</td>
            <td>{{ ToDmyHi($item->layanan->TglClosed) }}</td>
            <td>{{ $item->layanan->AllGroupSolver }} </td>
            <td>{{ $item->layanan->AllSolver }}</td>
            <td>{{ ToDmyHi($item->layanan->CreatedAt) }}</td>
            <td>{{ $item->layanan->CreatedBy }}</td>

            <td class="text">{{ ToDmyHi($item->TglDistribusi)  }}</td>
            <td class="text">{{ ToDmy($item->TglLayanan)  }}</td>
            <td class="text">{{ $item->NoTicket }}</td>
            <td class="text">{{ $item->NipLayanan }}</td>
            <td class="text">{{ $item->NmPeg }}</td>
            <td class="text">{{ $item->KdUnitOrg }}</td>
            <td class="text">{{ $item->NmUnitOrg }}</td>
            <td class="text">{{ $item->NmUnitOrgInduk }}</td>
            <td class="text">{{ $item->KdBrg }}</td>
            <td class="text">{{ $item->NmBrg }}</td>
            <td class="text">{{ $item->NmBrgLengkap }}</td>
            <td class="text">{!! strip_tags(nl2br($item->Keterangan),"<p><br>") !!}</td>
            <td class="text">{{ $item->NoIknAset }}</td>
            <td class="text">{{ $item->Aset }}</td>
            <td class="text">{{ $item->CreatedBy }}</td>
        </tr>
        @endforeach
    </tbody>
</table>