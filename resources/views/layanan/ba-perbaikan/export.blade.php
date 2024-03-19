<?php
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-disposition: attachment; filename=BA_Perbaikan_".date('YmdHis').".xls");
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
            <th rowspan="2">No</th>
            <th rowspan="2">No Ticket Tahun</th>
            <th rowspan="2">No Ticket Bulan</th>
            <th rowspan="2">No Ticket Antrian</th>
            <th rowspan="2">No Ticket Random</th>
            <th rowspan="2">No Ticket</th>
            <th rowspan="2">Kode Service Katalog</th>
            <th rowspan="2">Nama Service Katalog</th>
            <th rowspan="2">Nama</th>
            <th rowspan="2">Jam SLA</th>
            <th rowspan="2">Norma Waktu</th>
            <th rowspan="2">Tgl Layanan</th>
            <th rowspan="2">Nip</th>
            <th rowspan="2">Nama Pegawai</th>
            <th rowspan="2">Nama Unit Organisasi</th>
            <th rowspan="2">Nama Unit Organisasi Induk</th>
            <th rowspan="2">Nip Layanan</th>
            <th rowspan="2">Kode Unit Organisasi Layanan</th>
            <th rowspan="2">Prioritas Layanan</th>
            <th rowspan="2">Permintaan Layanan</th>
            <th rowspan="2">Keterangan Layanan</th>
            <th rowspan="2">Nomor Kontak</th>
            <th rowspan="2">Nip Operator Open</th>
            <th rowspan="2">Nip Operator Closed</th>
            <th rowspan="2">Status Layanan</th>
            <th rowspan="2">Tgl Eskalasi</th>
            <th rowspan="2">Tgl Progress</th>
            <th rowspan="2">Tgl Solved</th>
            <th rowspan="2">Tgl Closed</th>
            <th rowspan="2">Group Solver</th>
            <th rowspan="2">Solver</th>
            <th rowspan="2">Created At</th>
            <th rowspan="2">Created By</th>
            @if(request()->sla)
            <th rowspan="2">NormaWaktu</th>
            <th rowspan="2">Limit</th>
            <th rowspan="2">LamaJamLayanan</th>
            @endif
            <th colspan="7">Perbaikan</th>
            <th colspan="6">Pengembalian</th>
        </tr>
        <tr>
            
            <th>No BA</th>
            <th>Tgl BA</th>
            <th>Ruang</th>
            <th>Nip Pihak 1</th>
            <th>Nama Pihak 1</th>
            <th>Nip Pihak 2</th>
            <th>Nama Pihak 2</th>

            <th>No BA</th>
            <th>Tgl BA</th>
            <th>Nip Pihak 1</th>
            <th>Nama Pihak 1</th>
            <th>Nip Pihak 2</th>
            <th>Nama Pihak 2</th>
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
            @if(request()->sla)
            <td>{{ $item->NormaWaktu }}</td>
            <td>{{ $item->Limit }}</td>
            <td>{{ $item->LamaJamLayanan }}</td>
            @endif
            <td>{{ $item->NoBA }}</td>
            <td>{{ ToDmyHi($item->TglBA) }}</td>
            <td>{{ $item->Ruang }}</td>
            <td>{{ $item->layanan->Nip }}</td>
            <td>{{ $item->layanan->NmPeg }}</td>
            <td>{{ $item->NipPihak2 }}</td>
            <td>{{ optional($item->layanan->pihak2)->NmPeg }}</td>
            <td>{{ $item->NoBAPengembalian }}</td>
            <td>{{ ToDmyHi($item->TglKembali) }}</td>
            <td>{{ $item->NipPengembalianAset }}</td>
            <td>{{ optional($item->layanan->pengembali)->NmPeg }}</td>
            <td>{{ optional($item->layanan->pihak2pengembalian)->Nip }}</td>
            <td>{{ optional($item->layanan->pihak2pengembalian)->NmPeg }}</td>
        </tr>
        @endforeach
    </tbody>
</table>