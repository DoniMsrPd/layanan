<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\Setting\ServiceCatalogTematik;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ServiceCatalogTematikController extends Controller
{

    private static $response = [
        'success' => false,
        'data'    => null,
        'message' => null
    ];

    public function dataTables()
    {
        $data = ServiceCatalogTematik::select('ServiceCatalogTematik.*','mstTematik.Tema')
            ->join('mstTematik', 'mstTematik.Id', '=', 'ServiceCatalogTematik.MstTematikId')->whereNull('ServiceCatalogTematik.DeletedAt')->where('ServiceCatalogId', request()->serviceCatalogId);
        return DataTables::of($data)->addColumn('action', function ($data) {
            $deleteButton='';
            if(request()->user()->can('service-catalog-tematik.delete'))
                $deleteButton = '<a data-id="' . $data->Id . ' " data-url="/setting/service-catalog-tematik/' . $data->Id . ' " class="mb-2 mr-2 btn btn-danger btn-sm deleteData" data-title ="Data" title="Hapus" title-pos="up">'.btnDelete().'</a>';
            return $deleteButton;
        })->rawColumns(['action'])->make(true);
    }
    public function store(Request $request)
    {
        $this->authorize('service-catalog-tematik.create');
        $response = self::$response;
        $save = false;
        DB::beginTransaction();
        try {
            $inputServiceCatalogDetail['Id'] = uuid();
            $inputServiceCatalogDetail['ServiceCatalogId'] = $request->serviceCatalogId;
            $inputServiceCatalogDetail['MstTematikId'] = $request->mstTematikId;
            $inputServiceCatalogDetail['CreatedAt'] = Carbon::now();
            $inputServiceCatalogDetail['CreatedBy'] = auth()->user()->NIP;
            $save = ServiceCatalogTematik::create($inputServiceCatalogDetail);
            DB::commit();
            logActivity('default', 'Service Catalog')->log("Add Service Catalog Tematik ".$inputServiceCatalogDetail['Id']);
            $response['success'] = $save;
            $response['message'] = $save ? 'Berhasil Tambah Data' : 'Gagal Tambah Data';
        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();
        }
        return response()->json($response);
    }
    public function destroy(ServiceCatalogTematik $serviceCatalogTematik)
    {
        $this->authorize('service-catalog-tematik.delete');
        $response = self::$response;
        $delete = false;

        try {
            $serviceCatalogTematik->DeletedAt = Carbon::now()->format('Y-m-d H:i:s');
            $serviceCatalogTematik->DeletedBy = auth()->user()->NIP;
            $delete = $serviceCatalogTematik->save();
            $response['success'] = $delete;
            $response['message'] = $delete ? 'Berhasil Hapus Data' : 'Gagal Hapus Data';

            logActivity('default', 'Service Catalog')->log("Delete Service Catalog Tematik ".$serviceCatalogTematik->Id);
            return response()->json($response);
        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();
            return response()->json($response);
        }
    }
}
