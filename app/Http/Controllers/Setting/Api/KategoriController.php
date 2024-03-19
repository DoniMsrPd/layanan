<?php

namespace App\Http\Controllers\Setting\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting\Kategori;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        $data = Kategori::select('Id','Nama','Keterangan')->where('KdUnitOrgOwnerLayanan',$request->KdUnitOrgOwnerLayanan ??kdUnitOrgOwner())->whereNull('DeletedAt')->paginate(10);
        return response(['success' => True, "data" => $data ], Response::HTTP_OK);
    }
}