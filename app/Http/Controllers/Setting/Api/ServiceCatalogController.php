<?php

namespace App\Http\Controllers\Setting\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting\ServiceCatalog;
use Illuminate\Http\Response;

class ServiceCatalogController extends Controller
{
    public function index()
    {
        $data = ServiceCatalog::select('Id','Kode','Nama')->Filtered()->whereNull('DeletedAt')
        ->whereRaw('TglEnd is null or TglEnd > getdate()')->where('KdUnitOrgOwnerLayanan',kdUnitOrgOwner())->orderBy('Kode')->paginate(10);
        return response(['success' => True, "data" => $data ], Response::HTTP_OK);
    }
}