<?php

namespace App\Http\Controllers\Layanan;

use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Layanan\LayananAset;
use App\Models\Layanan\LayananPersediaan;
use App\Models\Layanan\Peminjaman;
use App\Models\Layanan\PeminjamanDetail;
use App\Models\Layanan\Pengembalian;
use App\Services\LayananService;
use Yajra\DataTables\Facades\DataTables;

class LayananAsetController extends Controller
{
    private static $response = [
        'success' => false,
        'data'    => null,
        'message' => null
    ];
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function dataTables(Request $request)
    {
        $data = LayananAset::where('LayananId',$request->LayananId)->whereNull('DeletedAt');
        return DataTables::of($data)->addColumn('pilih', function ($data) {
            $button = '<button href="#" class="mb-2 mr-2 btn btn-primary pilih-layanan-aset" data-id="' . $data->Id . '"  data-aset_layanan_id="' . $data->AsetLayananId . '" data-aset_sma_id="' . $data->AsetSMAId . '"  title-pos="up"><i class="icon-feather-share"></i></button>';
            return '<span class="btn-group" role="group">' . $button . '</span>';
        })->rawColumns(['pilih'])->make(true);
    }

    public function destroy(LayananPersediaan $persediaan)
    {
        $response = self::$response;
        $delete = false;

        try {
            $persediaan->AsetLayananId = null;
            $persediaan->AsetSMAId = null;
            $delete = $persediaan->save();
            $response['success'] = $delete;
            $response['message'] = $delete ? 'Berhasil Hapus Mapping' : 'Gagal Hapus Mapping';
            logActivity('default', ' Layanan')->log("Delete Mapping Persediaan " . $persediaan->Id);

            return response()->json($response);
        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();
            return response()->json($response);
        }
    }
    public function update(Request $request,LayananPersediaan $persediaan)
    {
        $response = self::$response;
        $save = false;

        try {
            $persediaan->AsetLayananId = $request->aset_layanan_id;
            $persediaan->AsetSMAId = $request->aset_sma_id;
            $save = $persediaan->save();
            $response['success'] = $save;
            $response['message'] = $save ? 'Berhasil  Mapping' : 'Gagal  Mapping';
            logActivity('default', ' Layanan')->log(" Mapping Persediaan " . $persediaan->Id);

            return response()->json($response);
        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();
            return response()->json($response);
        }
    }
}
