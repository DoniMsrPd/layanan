<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\Setting\Aset;
use App\Models\Setting\AsetSMA;
use App\Models\Setting\JnsAset;
use App\Models\Setting\TypeAset;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class AsetController extends Controller
{

    private static $response = [
		'success' => false,
		'data'    => null,
		'message' => null
	];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('aset.read');
        logActivity('default', ' Aset TI')->log("View Aset TI");
        return view('setting.aset.index');
    }

    public function dataTables(Request $request)
    {
        $data = Aset::with('pengguna')->whereNull('DeletedAt');
        if($request->asetSMA)
            $data = AsetSMA::with('pengguna');
        if($request->peminjaman)
            $data->whereNull('NipPengguna');
        return DataTables::of($data) ->order( function ($query) {
            if(!request()->asetSMA)
                $query->orderBy('CreatedAt', 'DESC');
        })->addColumn('aset', function ($data) {
            $aset =$data->JenisAset." ".$data->TypeAset."<br>".$data->Nama;
                if(request()->asetSMA)
                    $aset = $data->nm_brg.'<br>'.$data->nm_lgkp_brg;
            return $aset;
        })->addColumn('sn', function ($data) {
            $sn =$data->SerialNumber;
                if(request()->asetSMA)
                    $sn = $data->keterangan;
            return $sn;
        })->addColumn('pilih', function ($data) {
            $NmPeg = optional($data->pengguna)->NmPeg;
            $aset =$data->JenisAset." ".$data->TypeAset." ".$data->Nama;
            $noIKN = $data->NoIkn1.' '.$data->NoIkn2;
            $noSerial =$data->SerialNumber;
            if(request()->asetSMA)
                $aset = $data->nm_brg.' '.$data->nm_lgkp_brg;
            if(request()->asetSMA)
                $noIKN = $data->no_ikn;
            if(request()->asetSMA)
                $noSerial =$data->keterangan;
            $isAsetSMA = request()->asetSMA ? 1:0;
            $button = '<button href="#" class="mb-2 mr-2 btn btn-primary pilih-aset" data-id="' . $data->Id . '" data-nama="' . $NmPeg . '" data-no_ikn="' . $noIKN. '" data-no_serial="' . $noSerial . '" data-is_aset_sma="' . $isAsetSMA . '"  data-aset="' . $aset . '"  title-pos="up"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-share"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path><polyline points="16 6 12 2 8 6"></polyline><line x1="12" y1="2" x2="12" y2="15"></line></svg></button>';
            return '<span class="btn-group" role="group">' . $button . '</span>';
        })->addColumn('pilihPeminjaman', function ($data) {
            $NmPeg = optional($data->pengguna)->NmPeg;
            $aset =$data->JenisAset." ".$data->TypeAset."<br>".$data->Nama;
            $noIKN = $data->NoIkn1.' '.$data->NoIkn2;
            $noSerial =$data->SerialNumber;
            $button = '<button href="#" class="mb-2 mr-2 btn btn-primary pilih-peminjaman" data-id="' . $data->Id . '" data-nama="' . $NmPeg . '" data-no_ikn="' . $noIKN. '" data-no_serial="' . $noSerial . '"   data-aset="' . $aset . '"  title-pos="up"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-share"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path><polyline points="16 6 12 2 8 6"></polyline><line x1="12" y1="2" x2="12" y2="15"></line></svg></button>';
            return '<span class="btn-group" role="group">' . $button . '</span>';
        })->addColumn('action', function ($data) {
            $editButton = '';
            $deleteButton = '';
            if(request()->user()->can('aset.update'))
                $editButton = '<a href="' . route('setting.aset.edit', $data->Id) . '" class="mb-2 mr-2 btn btn-warning btn-sm" title="Ubah" title-pos="up"><i class="icon-feather-edit-2"></i></a>';
            if(request()->user()->can('aset.delete'))
                $deleteButton = '<a data-id="' . $data->Id . ' " data-url="/setting/aset/' . $data->Id . ' " class="mb-2 mr-2 btn btn-danger btn-sm deleteData" data-title ="Data" title="Hapus" title-pos="up"><i class="icon-feather-trash-2"></i></a>';
            return '<span class="btn-group" role="group">'.$editButton.''.$deleteButton.'</span>';
        })->addColumn('NmPeg',function ($data){
            return optional($data->pengguna)->NmPeg;
        })->rawColumns(['aset','pilih','pilihPeminjaman','action','NmPeg'])->make(true);
    }
    public function create()
    {
        $this->authorize('aset.create');
        $data = (object) [
            'method' => 'POST',
            'action' => '/setting/aset',
            'title' => 'TAMBAH ASET',
            'jenisAset' => JnsAset::whereNull('DeletedAt')->get(),
            'typeAset' => TypeAset::whereNull('DeletedAt')->get(),
            'aset' => []
        ];
        return view('setting.aset.form',compact('data'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    function inputAset($request, $update = false){

        $dt = new  \Carbon\Carbon('01 '.$request->MasaGaransi);
        $inputAset = [
            'NoIkn1' => $request->NoIkn1,
            'NoIkn2' => $request->NoIkn2,
            'SerialNumber' => $request->SerialNumber,
            'RefJnsAsetId' => $request->RefJnsAsetId,
            'RefTypeAsetId' => $request->RefTypeAsetId,
            'JenisAset' => JnsAset::find($request->RefJnsAsetId)->Nama,
            'TypeAset' => TypeAset::find($request->RefTypeAsetId)->Nama,
            'Nama' => $request->Nama,
            'Tahun' => $request->Tahun,
            'HargaPerolehan' => str_replace(".","",$request->HargaPerolehan),
            'MasaGaransiBulan' => $dt->month,
            'MasaGaransiTahun' => $dt->year,
            'NipPengguna' => $request->NipPengguna ?  explode("/",$request->NipPengguna)[0]:null,
            'KdUnitOrgPengguna' => $request->KdUnitOrgPengguna,
            'Processor' => $request->Processor,
            'Hdd' => $request->Hdd,
            'Memory' => $request->Memory,
        ];
        return $inputAset;

    }
    public function store(Request $request)
    {

        // $this->authorize('aset.create');
        DB::beginTransaction();
        try {
            $inputAset = $this->inputAset($request);
            $inputAset['Id'] = uuid();
            $inputAset['CreatedAt'] = Carbon::now();
            $inputAset['CreatedBy'] = auth()->user()->NIP;
            $data = Aset::create($inputAset);
            DB::commit();
            logActivity('default', ' Aset TI')->log("Add Aset TI ".$inputAset['Id']);
            $msg = 'Tambah Aset Berhasil';
            $type = "success";
        } catch (\Exception $e) {
            DB::rollback();
            $type = "warning";
            $msg = $e->getMessage();
            \Log::error($e->getTraceAsString());
        }
        if(request()->ajax()){
            $response['success'] = true;
            $response['message'] ='Berhasil Tambah Data' ;
            return response()->json($response);
        }
        return redirect(url('setting/aset'))->with('flash_message', $msg)
            ->with('flash_type', $type);
    }
    public function edit(Aset $aset, $show = false)
    {

        $this->authorize('aset.read');
        $data = (object) [
            'method' => 'PATCH',
            'action' => "/setting/aset/$aset->Id",
            'title' => 'EDIT ASET',
            'jenisAset' => JnsAset::whereNull('DeletedAt')->get(),
            'typeAset' => TypeAset::whereNull('DeletedAt')->get(),
            'aset' => $aset
        ];
        return view('setting.aset.form',compact('data'));
    }
    public function update(Request $request, Aset $aset)
    {
        $this->authorize('aset.update');
        DB::beginTransaction();
        try {
            $inputAset = $this->inputAset($request, true);
            $inputAset['UpdatedAt'] = Carbon::now();
            $inputAset['UpdatedBy'] = auth()->user()->NIP;
            Aset::where('Id', $request->id)->update($inputAset);
            DB::commit();
            logActivity('default', ' Aset TI')->log("Update Aset TI ".$aset->Id);
            $msg = 'Edit Aset  Berhasil';
            $type = "success";
        } catch (\Throwable $e) {
            DB::rollback();
            $type = "warning";
            $msg = $e->getMessage();
            \Log::error($e->getTraceAsString());
        }

        return redirect(url('setting/aset'))->with('flash_message', $msg)
            ->with('flash_type', $type);
    }
    public function destroy(Aset $aset)
    {
        $this->authorize('aset.delete');
        $response = self::$response;
        $delete = false;

        try {
            $aset->DeletedAt = Carbon::now()->format('Y-m-d H:i:s');
            $aset->DeletedBy = auth()->user()->NIP;
            $delete = $aset->save();
            $response['success'] = $delete;
            $response['message'] = $delete ? 'Berhasil Hapus Data' : 'Gagal Hapus Data';
            logActivity('default', ' Aset TI')->log("Delete Aset TI ".$aset->Id);

            return response()->json($response);

        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();
            return response()->json($response);
        }
    }
    public function deletePengguna(Aset $aset)
    {
        $response = self::$response;
        $delete = true;
        try {
            $aset->UpdatedAt = Carbon::now()->format('Y-m-d H:i:s');
            $aset->UpdatedBy = auth()->user()->NIP;
            $aset->NipPengguna = null;
            $aset->save();
            $response['success'] = $delete;
            $response['message'] = $delete ? 'Berhasil Hapus Pengguna' : 'Gagal Hapus Pengguna';
            logActivity('default', ' Aset TI')->log("Update Aset TI ".$aset->Id);

            return response()->json($response);

        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();
            return response()->json($response);
        }
    }
}
