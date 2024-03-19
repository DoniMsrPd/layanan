<?php

namespace App\Http\Controllers\Layanan;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\System\Pegawai;
use App\Models\Layanan\Layanan;
use App\Models\Layanan\LayananGroupSolver;
use App\Models\Layanan\LayananLog;
use App\Models\Layanan\LayananSolver;
use App\Models\Layanan\LayananTL;
use App\Services\LayananService;

class EskalasiController extends Controller
{

    private static $response = [
        'success' => false,
        'data'    => null,
        'message' => null
    ];

    public function showGroupSolver($layananId)
    {
        if (request()->getData) {
            return  LayananGroupSolver::where('LayananId', $layananId)->whereNull('DeletedAt')->pluck('MstGroupSolverId')->implode(',');;
        }
        $data = LayananGroupSolver::where('LayananId', $layananId)->whereNull('DeletedAt')->get();
        return view('layanan.layanan.eskalasi._show-group-solver', compact('data'));
    }
    public function destroyGroupSolver($id)
    {
        $response = self::$response;
        $delete = false;

        try {
            $data = LayananGroupSolver::find($id);
            $data->DeletedAt = Carbon::now()->format('Y-m-d H:i:s');
            $data->DeletedBy = auth()->user()->NIP;
            $delete = $data->save();
            $response['success'] = $delete;
            $response['message'] = $delete ? 'Berhasil Hapus Data' : 'Gagal Hapus Data';

            DB::statement("Update Layanan set [AllGroupSolver]= [dbo].Func_getAllGroupSolver('{$data->LayananId}') WHERE Id='{$data->LayananId}'");
            $this->inputLayananLog($data->LayananId, 'Update Group Solver');
            logActivity('default', 'Layanan')->log("Delete Group Solver {$data->MstGroupSolverId} from Layanan " . $data->LayananId);
            return response()->json($response);
        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();
            return response()->json($response);
        }
    }
    public function storeGroupSolver(Request $request, LayananService $layananService, $api= false)
    {
        $inputLayananGroupSolver['LayananId'] = $request->LayananId;
        $inputLayananGroupSolver['MstGroupSolverId'] = $request->Id;
        $inputLayananGroupSolver['CreatedAt'] = Carbon::now();
        $inputLayananGroupSolver['CreatedBy'] = auth()->user()->NIP;
        LayananGroupSolver::create($inputLayananGroupSolver);
        $layanan = Layanan::findOrFail($request->LayananId);
        $layanan->UpdatedAt = Carbon::now();
        $layanan->UpdatedBy = auth()->user()->NIP;
        $layanan->save();
        DB::statement("Update Layanan set [AllGroupSolver]= [dbo].Func_getAllGroupSolver('{$request->LayananId}') WHERE Id='{$request->LayananId}'");
        $this->inputLayananLog($request->LayananId, 'Update Group Solver');
        logActivity('default', 'Layanan')->log("Add Group Solver {$request->Id} to Layanan " . $request->LayananId);
        $kasubag = Pegawai::where('KdUnitOrg',$request->Id)->where('StsPensiun',0)->where('JnsJabatanCur',1)->first();
        if(isset($kasubag->Nip)){
            $subject = "Layanan {$layanan->NoTicket} {$layanan->NoTicketRandom} :: Open";
            $to = User::where('NIP', $kasubag->Nip)->first();
            $data = [
                'user' => $to,
                'layanan' => $layanan,
                'url' => route('layanan.eskalasi', $layanan->Id),
                'message' => 'Silahkan klik tombol dibawah ini untuk melihat detil layanan dan memantau proses penyelesaian layanan',
                'eskalasi' => true
            ];
            $view = view('layanan.notifications.wa', compact('data'))->render();
            $layananService->sendMail($subject, 'layanan.notifications.mail', $data, $view, 'Melati ',$to);
        }
        if ($api) {
            $response['success'] = true;
            $response['message'] = 'Berhasil Tambah Group Solver';
            return $response;
        }
        $response['success'] = true;
        $response['message'] = 'Berhasil Tambah Group Solver';
        return response()->json($response);
    }
    public function updateSolver(Request $request,$id)
    {
        $solver = LayananSolver::find($id);
        $solver->Catatan = $request->Catatan;
        $solver->save();
        $response['success'] = true;
        $response['message'] = 'Berhasil Update Catatan Solver';
        return response()->json($response);
    }
    public function showSolver($layananId)
    {
        if (request()->getData) {
            return  LayananSolver::where('LayananId', $layananId)->whereNull('DeletedAt')->pluck('Nip')->implode(',');
        }
        $data = LayananSolver::where('LayananId', $layananId)->whereNull('DeletedAt')->get();
        return view('layanan.layanan.eskalasi._show-solver', compact('data'));
    }
    public function destroySolver($id)
    {
        $response = self::$response;
        $delete = false;

        try {
            $data = LayananSolver::find($id);
            $data->DeletedAt = Carbon::now()->format('Y-m-d H:i:s');
            $data->DeletedBy = auth()->user()->NIP;
            $delete = $data->save();
            $response['success'] = $delete;
            $response['message'] = $delete ? 'Berhasil Hapus Data' : 'Gagal Hapus Data';
            DB::statement("Update Layanan set [AllSolver]= [dbo].Func_getAllSolver('{$data->LayananId}') WHERE Id='{$data->LayananId}'");
            $this->inputLayananLog($data->LayananId, 'Update  Solver');
            logActivity('default', 'Layanan')->log("Delete Solver {$data->Nip} from Layanan " . $data->LayananId);

            return response()->json($response);
        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();
            return response()->json($response);
        }
    }
    public function storeSolver(Request $request, LayananService $layananService,$api = false)
    {
        $inputSolver['LayananId'] = $request->LayananId;
        $inputSolver['Nip'] = $request->Nip;
        $inputSolver['CreatedAt'] = Carbon::now();
        $inputSolver['CreatedBy'] = auth()->user()->NIP;
        LayananSolver::create($inputSolver);
        $layanan = Layanan::findOrFail($request->LayananId);
        $layanan->UpdatedAt = Carbon::now();
        $layanan->UpdatedBy = auth()->user()->NIP;
        $layanan->save();
        DB::statement("Update Layanan set [AllSolver]= [dbo].Func_getAllSolver('{$request->LayananId}') WHERE Id='{$request->LayananId}'");
        $this->inputLayananLog($request->LayananId, 'Update  Solver');
        logActivity('default', 'Layanan')->log("Add Solver {$request->Nip} to Layanan " . $request->LayananId);

        $subject = "Layanan {$layanan->NoTicket} {$layanan->NoTicketRandom} :: Eskalasi";
        $to = User::where('NIP', $request->Nip)->first();
        $data = [
            'user' => $to,
            'layanan' => $layanan,
            'url' => route('layanan.eskalasi', $layanan->Id),
            'message' => 'Silahkan klik tombol dibawah ini untuk melihat detil layanan dan memantau proses penyelesaian layanan',
            'eskalasi' => true
        ];
        $view = view('layanan.notifications.wa', compact('data'))->render();
        $layananService->sendMail($subject, 'layanan.notifications.mail', $data,$view, 'Melati ', $to);
        $response['success'] = true;
        $response['message'] = 'Berhasil Tambah Solver';
        if ($api) {
            return $response;
        }
        return response()->json($response);
    }
    public function inputLayananLog($layananId, $keterangan)
    {

        $inputLog['LayananId'] = $layananId;
        $inputLog['Keterangan'] = $keterangan;
        $inputLog['CreatedAt'] = Carbon::now();
        $inputLog['CreatedBy'] = auth()->user()->NIP;
        $inputLog['Nip'] = auth()->user()->NIP;
        LayananLog::create($inputLog);
    }
}
