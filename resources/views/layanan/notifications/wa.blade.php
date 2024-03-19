
Halo, {{$data['user']->pegawai->NmPeg}}

[{!! $data['layanan']->status->Nama ?? '' !!}]
@if(isset($eskalasi)) Eskalasi @else Pengajuan @endif Layanan dengan Nomor Tiket {{ $data['layanan']->NoTicket }} {{ strtoupper($data['layanan']->NoTicketRandom) }}
Informasi Layanan : {{ strip_tags($data['layanan']->PermintaanLayanan) }}
Nip / Nama : {!! strip_tags(nl2br($data['layanan']->pelapor->Nip),"<p><br>") !!}  / {!! strip_tags(nl2br($data['layanan']->pelapor->NmPeg),"<p><br>") !!}
Jabatan : {!! strip_tags(nl2br($data['layanan']->pelapor->NmJabatan),"<p><br>") !!}
Satker : {!! strip_tags(nl2br($data['layanan']->pelapor->NmUnitOrg),"<p><br>") !!}  {!! strip_tags(nl2br($data['layanan']->pelapor->NmUnitOrg<>$data['layanan']->pelapor->NmUnitOrgInduk
    ? $data['layanan']->pelapor->NmUnitOrgInduk :''),"<p><br>") !!}

{!! strip_tags(nl2br($data['message']),"<p><br>") !!}

{{$data['url']}}
{{-- Hormat kami,
Pengelola Aplikasi Persuratan BPK RI <br>
=============================================<br>
Email ini dikirimkan secara otomatis . Mohon untuk tidak membalas (reply) email ini.
Terima kasih. --}}