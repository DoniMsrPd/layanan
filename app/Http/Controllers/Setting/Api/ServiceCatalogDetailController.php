<?php

namespace App\Http\Controllers\Setting\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting\ServiceCatalogDetail;
use Illuminate\Http\Response;

class ServiceCatalogDetailController extends Controller
{
    public function index()
    {
        $data = ServiceCatalogDetail::select('Id','Nama')->Filtered()->whereNull('DeletedAt')
        ->where('ServiceCatalogId', request()->serviceCatalogId)->orderBy('Nama')->paginate(10);
        return response(['success' => True, "data" => $data ], Response::HTTP_OK);
    }
}