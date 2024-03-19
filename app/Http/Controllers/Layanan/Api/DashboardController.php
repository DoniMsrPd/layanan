<?php

namespace App\Http\Controllers\Layanan\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DashboardResource;
use App\Models\Layanan\MstUnitOrgLayananOwner;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DashboardController extends Controller
{
    public function index()
    {
        $data = MstUnitOrgLayananOwner::whereNull('KdKantor')->orWhereRaw("KdKantor =[dbo].[Func_getKdKantorLayanan](?)",[userNip()])->get();
        return response(['success' => true, "data" => DashboardResource::collection($data) ], Response::HTTP_OK);
    }
}
