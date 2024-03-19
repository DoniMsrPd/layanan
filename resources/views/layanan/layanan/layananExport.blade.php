<?php
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-disposition: attachment; filename=Layanan".date('YmdHis').".xls");
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
            <th>Updated At</th>
            <th>Updated By</th>
            @if(request()->sla)
            <th>NormaWaktu</th>
            <th>Limit</th>
            <th>LamaJamLayanan</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
        <tr>

            <td>{{ $loop->iteration }}</td>
            <td>{{ $item->NoTicketTahun }}</td>
            <td>{{ $item->NoTicketBulan }}</td>
            <td>{{ $item->NoTicketAntrian }}</td>
            <td>{{ strtoupper($item->NoTicketRandom) }}</td>
            <td>{{ $item->NoTicket }}</td>
            <td>{{ $item->ServiceCatalogKode }}</td>
            <td>{{ $item->ServiceCatalogNama }}</td>
            <td>{{ optional($item->sla)->Nama }}</td>
            <td>{{ optional($item->sla)->JamSLA }}</td>
            <td>{{ optional($item->sla)->NormaWaktu }}</td>
            <td>{{ $item->TglLayanan }}</td>
            <td>{{ $item->Nip }}</td>
            <td>{{ $item->NmPeg }}</td>
            <td class="text">{{ $item->KdUnitOrg }}</td>
            <td>{{ $item->NmUnitOrgInduk }}</td>
            <td>{{ $item->NipLayanan }}</td>
            <td class="text">{{ $item->KdUnitOrgLayanan }}</td>
            <td>{{ $item->PrioritasLayanan }}</td>
            <td> {!! strip_tags(nl2br($item->PermintaanLayanan),"<p><br>") !!}</td>
            <td> {!! strip_tags(nl2br($item->KeteranganLayanan),"<p><br>") !!}</td>
            <td class="text">{{ $item->NomorKontak }}</td>
            <td>{{ $item->NipOperatorOpen }}</td>
            <td>{{ $item->NipOperatorClosed }}</td>
            <td>{{ optional($item->status)->Nama }}</td>
            <td>{{ ToDmyHi($item->TglEskalasi) }}</td>
            <td>{{ ToDmyHi($item->TglProgress) }}</td>
            <td>{{ ToDmyHi($item->TglSolved) }}</td>
            <td>{{ ToDmyHi($item->TglClosed) }}</td>
            <td>{{ $item->AllGroupSolver }} </td>
            <td>{{ $item->AllSolver }}</td>
            <td>{{ ToDmyHi($item->CreatedAt) }}</td>
            <td>{{ $item->CreatedBy }}</td>
            <td>{{ ToDmyHi($item->UpdatedAt) }}</td>
            <td>{{ $item->UpdatedBy }}</td>
            @if(request()->sla)
            <td>{{ $item->NormaWaktu }}</td>
            <td>{{ $item->Limit }}</td>
            <td>{{ $item->LamaJamLayanan }}</td>
            @endif
        </tr>
        @endforeach
    </tbody>
</table>
