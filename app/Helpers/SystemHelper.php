<?php

use App\Models\Referensi\SpgDataKonseli;
use App\Models\System\KonselingFile;
use App\Models\System\MelatiFile;
use App\Models\System\Pegawai;
use App\Models\System\Role;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Activity;
use Ramsey\Uuid\Uuid;
use Jenssegers\Agent\Agent;

function ToDmy($date, $format = 'd M Y')
{
    if ($date != null) {
        $formated = str_replace('/', '-', $date);
        $dateFormated = Carbon::parse($formated);
        return $dateFormated->format($format);
    } else {
        return null;
    }
}

function penyebut($nilai)
{
    $nilai = abs($nilai);
    $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
    $temp = "";
    if ($nilai < 12) {
        $temp = " " . $huruf[$nilai];
    } else if ($nilai < 20) {
        $temp = penyebut($nilai - 10) . " belas";
    } else if ($nilai < 100) {
        $temp = penyebut($nilai / 10) . " puluh" . penyebut($nilai % 10);
    } else if ($nilai < 200) {
        $temp = " seratus" . penyebut($nilai - 100);
    } else if ($nilai < 1000) {
        $temp = penyebut($nilai / 100) . " ratus" . penyebut($nilai % 100);
    } else if ($nilai < 2000) {
        $temp = " seribu" . penyebut($nilai - 1000);
    } else if ($nilai < 1000000) {
        $temp = penyebut($nilai / 1000) . " ribu" . penyebut($nilai % 1000);
    } else if ($nilai < 1000000000) {
        $temp = penyebut($nilai / 1000000) . " juta" . penyebut($nilai % 1000000);
    } else if ($nilai < 1000000000000) {
        $temp = penyebut($nilai / 1000000000) . " milyar" . penyebut(fmod($nilai, 1000000000));
    } else if ($nilai < 1000000000000000) {
        $temp = penyebut($nilai / 1000000000000) . " trilyun" . penyebut(fmod($nilai, 1000000000000));
    }
    return $temp;
}

function terbilang($nilai)
{
    if ($nilai < 0) {
        $hasil = "minus " . trim(penyebut($nilai));
    } else {
        $hasil = trim(penyebut($nilai));
    }
    return ucwords($hasil);
}
function pejabatTTD()
{
    return Pegawai::where('KdUnitOrg', '100205040200')->where('JnsJabatanCur', 1)->where('StsPensiun', 0)->first();
}
function getRole()
{
    return Role::select('name', 'id')->get();
}

function userNip()
{
    return Auth::user()->NIP;
}

function userNipImp()
{
    if (session('nip_imp'))
        return session('nip_imp');

    return Auth::user()->nip;
}

function userNipByName($q)
{
    if ($q)
        return Pegawai::where("NmPeg", "like", "%$q%")->first();
    return '-';
}

function userKdOrg()
{
    return Auth::user()->pegawai->KD_UNIT_ORG ?? Auth::user()->pegawai->KdUnitOrg;
}


function logActivity($log = 'default', $menu = '')
{
    return activity($log)
        ->by(request()->user())
        ->withProperties([
            'USER_ID' => auth()->user()->NIP,
            'CTIME' => date('Y-m-d H:i:s.0000000'),
            'IP' => request()->ip(),
            'NIP_SESSION' => auth()->user()->NIP,
            'URI' => request()->path(),
            'ACT' => Request::method(),
            'KD_UNIT_ORG' => auth()->user()->pegawai->kd_unit_org ?? null,
            'MAIN_MENU' => $menu
        ])
        ->tap(function (Activity $activity) use ($menu) {
            $activity->USER_ID = auth()->user()->NIP;
            $activity->CTIME = date('Y-m-d H:i:s.0000000');
            $activity->IP = request()->ip();
            $activity->NIP_SESSION = auth()->user()->NIP;
            $activity->URI = request()->path();
            $activity->ACT = Request::method();
            $activity->KD_UNIT_ORG = auth()->user()->pegawai->kd_unit_org ?? null;
            $activity->MAIN_MENU = $menu;
        });
}
function dateOutput($date)
{
    if (!$date)
        return '';
    return Carbon::parse($date)->format('d F Y');
}
function dateInput($date)
{
    if (!$date || $date == '-')
        return null;
    $d =  Carbon::createFromFormat('d F Y', $date);
    return Carbon::parse($d)->format('Y-m-d');
}
function uuid()
{
    $uuid1 = Uuid::uuid1();
    return strtoupper($uuid1->toString());
}
function ToDmyHi($date)
{
    if ($date != null) {
        $formated = str_replace('/', '-', $date);
        $date = Carbon::parse($formated)->locale('id_ID');
        return $date->format('d').' '.$date->monthName.' '.$date->format('Y H:i');
    } else {
        return null;
    }
}
function isMobile()
{
    $cek = env("SET_MOBILE");
    if ($cek <> true) {
        $var = $cek;
        $agent = new Agent();
        // akses via url utama
        if ($agent->isAndroidOS() == true || $agent->isMobile() == true) {
            $var = true;
        }
        return $var;
    } else {
        // setup mobile, jika tetap akses url mobile via dekstop
        return $cek;
    }
}
 function isTablet()
{

    $cek = env("SET_MOBILE");
    if ($cek <> true) {
        $var = $cek;
        $agent = new Agent();
        // akses via url utama
        if ($agent->isTablet() == true) {
            $var = true;
        }
        return $var;
    } else {
        // setup mobile, jika tetap akses url mobile via dekstop
        return $cek;
    }
}
function pegawaiBiasa()
{

    if (Session::has('pegawaiBiasa')) {
        return Session::get('pegawaiBiasa');
    } else {
        $role = Role::where('name', '!=', 'Pegawai BPK')->get();
        session::put('pegawaiBiasa', !auth()->user()->hasAnyRole($role));
        return session::get('pegawaiBiasa');
    }
}
function checkUUID($uuid)
{
    if (preg_match('/^\{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}\}?$/', $uuid)) {
        return true;
    } else {
        return false;
    }
}

function inputFiles($request, $tableId, $name = 'FileAttachment', $jnsFile = null, $tableName = 'Layanan', $disk = 'layanan')
{
    if ($request->hasFile($name)) {
        $files = $request->file($name);
        $i = [];
        foreach ($files as $key => $file) {
            $fu = sprintf("%s.%s", md5(date('YmdHis') . $key), $file->getClientOriginalExtension());
            $originalName = $file->getClientOriginalName();
            Storage::disk($disk)->put($fu, File::get($file));
            if (Storage::disk($disk)->put($fu, File::get($file))) {
                MelatiFile::create([
                    'Id' =>  uuid(),
                    'TableId' =>  $tableId,
                    'createdBy' => auth()->user()->NIP,
                    'NmFileOriginal' => $originalName,
                    'NmFile' => $fu,
                    'JnsFile' => $jnsFile,
                    'TableName' => $tableName,
                    'PathFile' => $disk . '/storage/' . $fu,
                    'createdAt' => Carbon::now()
                ]);
            }
        }
    }
}


function getNoKonseli($nip) {
    return SpgDataKonseli::where('Nip',$nip)->first()->NoKonseli ?? null;
}
function getNmUnitOrg($kdUnitOrg) {
    return DB::select("select [dbo].[Func_getNmUnitOrg]('$kdUnitOrg') NmUnitOrg")[0]->NmUnitOrg ?? null;
}
function getNmUnitOrgInduk($kdUnitOrg) {
    return DB::select("select [dbo].[Func_getNmUnitOrgInduk]('$kdUnitOrg') NmUnitOrgInduk")[0]->NmUnitOrgInduk ?? null;
}
function btnEdit() {
    return '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>';
}
function btnDelete() {
    return '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>';
}
function btnSearch() {
    return '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>';
}
function btnPilih() {
    return '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-share"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path><polyline points="16 6 12 2 8 6"></polyline><line x1="12" y1="2" x2="12" y2="15"></line></svg>';
}
function kdUnitOrgOwner($nip = null) {
    $nipUser = '';
    if (!isset($nip)) {
        $nipUser = auth()->user()->NIP;
    } else {
        $nipUser = $nip;
    }

    // $kdUnitOrgOwner = Cache::remember('kdUnitOrgOwner'.$nipUser, 60*5, function () use ($nipUser) {
    //     return DB::select("select [dbo].[Func_getKdUnitOrgOwnerLayanan](?) kdUnitOrgOwner", [$nipUser]);
    // });

    $kdUnitOrgOwner = DB::select("select [dbo].[Func_getKdUnitOrgOwnerLayanan](?) kdUnitOrgOwner", [$nipUser]);
    return $kdUnitOrgOwner[0]->kdUnitOrgOwner ?? null;
}
