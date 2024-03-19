<?php

namespace App\Http\Controllers\Layanan\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Layanan\TindakLanjutController as LayananTindakLanjutController;
use App\Models\Layanan\LayananTL;
use App\Services\LayananService;
use Illuminate\Http\Request;

class TindakLanjutController extends Controller
{
    protected $tlCtr;

    public function __construct()
    {
        $this->tlCtr = app(LayananTindakLanjutController::class);
    }
    public function store(Request $request, LayananService $layananService)
    {
        return $this->tlCtr->store($request, $layananService);
    }
    public function update(Request $request,LayananTL $layanan_tl)
    {
        return $this->tlCtr->update($request, $layanan_tl);
    }
    public function destroy(LayananTL $layanan_tl)
    {
        return $this->tlCtr->destroy($layanan_tl);
    }
}
