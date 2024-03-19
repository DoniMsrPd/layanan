<style>
    th,
    td {
        padding: 5px;
    }
</style>

Halo, {{$user->pegawai->NmPeg}} <br>

@if(isset($eskalasi)) Eskalasi @else Pengajuan @endif Layanan dengan Nomor Tiket {{ $layanan->NoTicket }} {{ strtoupper($layanan->NoTicketRandom) }}<br>
<table border="0" cellspacing="0" cellpadding="0" width="100%">
    <tr>
        <td width="20%" valign="top">Informasi Layanan</td>
        <td align="center" width="3%" valign="top">:</td>
        <td valign="top">{!! strip_tags(nl2br($layanan->PermintaanLayanan),"<p><br>") !!} </td>
    </tr>
    <tr>
        <td valign="top">Nip / Nama</td>
        <td align="center" valign="top">:</td>
        <td valign="top">{!! strip_tags(nl2br($layanan->pelapor->Nip),"<p><br>") !!}/ {!! strip_tags(nl2br($layanan->pelapor->NmPeg),"<p><br>") !!} </td>
    </tr>
    <tr>
        <td valign="top">Jabatan</td>
        <td align="center" valign="top">:</td>
        <td valign="top">{!! strip_tags(nl2br($layanan->pelapor->NmJabatan),"<p><br>") !!} </td>
    </tr>
    <tr>
        <td valign="top">Satker</td>
        <td align="center" valign="top">:</td>
        <td valign="top">{!! strip_tags(nl2br($layanan->pelapor->NmUnitOrg),"<p><br>") !!} {!! strip_tags(nl2br($layanan->pelapor->NmUnitOrg<>$layanan->pelapor->NmUnitOrgInduk
            ? $layanan->pelapor->NmUnitOrgInduk :''),"<p><br>") !!} </td>
    </tr>
</table>
<br>

<b>{!! strip_tags(nl2br($message),"<p><br>") !!}</b> <br>

<table class="action" align="center" width="100%" cellpadding="0" cellspacing="0" role="presentation"
    style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; box-sizing: border-box; margin: 30px auto; padding: 0; text-align: center; width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%;">
    <tbody>
        <tr>
            <td align="center"
                style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; box-sizing: border-box;">
                <table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation"
                    style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; box-sizing: border-box;">
                    <tbody>
                        <tr>
                            <td align="center"
                                style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; box-sizing: border-box;">
                                <table border="0" cellpadding="0" cellspacing="0" role="presentation"
                                    style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; box-sizing: border-box;">
                                    <tbody>
                                        <tr>
                                            <td
                                                style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; box-sizing: border-box;">
                                                <a href="{{$url}}" class="button button-primary" target="_blank"
                                                    style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; box-sizing: border-box; border-radius: 3px; box-shadow: 0 2px 3px rgba(0, 0, 0, 0.16); color: #fff; display: inline-block; text-decoration: none; -webkit-text-size-adjust: none; background-color: #3490dc; border-top: 10px solid #3490dc; border-right: 18px solid #3490dc; border-bottom: 10px solid #3490dc; border-left: 18px solid #3490dc;">Klik
                                                    untuk lihat detail</a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
<br>
{{-- Hormat kami,
Pengelola Aplikasi Persuratan BPK RI <br>
=============================================<br>
Email ini dikirimkan secara otomatis . Mohon untuk tidak membalas (reply) email ini.
Terima kasih. --}}