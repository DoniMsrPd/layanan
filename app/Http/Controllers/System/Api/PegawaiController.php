<?php

namespace App\Http\Controllers\System\Api;

use App\Http\Controllers\Controller;
use App\Models\System\Pegawai;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PegawaiController extends Controller
{
    public function index(Request $request)
    {
        $data = Pegawai::select('Nip','NmPeg','KdUnitOrg','NmUnitOrg','NmUnitOrgInduk','NoHp')->filtered()->where('stsPensiun', '0')->paginate(10)->appends(request()->all());
        return response(['success' => True, "data" => $data ], Response::HTTP_OK);
    }

    public function show($nip)
    {
        $data = Pegawai::select('Nip','NmPeg','KdUnitOrg','NmUnitOrg','NmUnitOrgInduk','NoHp')->where('Nip', $nip)->first();
        return response(['success' => True, "data" => $data ], Response::HTTP_OK);
    }
}