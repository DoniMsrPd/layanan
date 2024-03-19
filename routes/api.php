<?php

use App\Http\Controllers\Layanan\Api\DashboardController;
use App\Http\Controllers\Setting\Api\KategoriController;
use App\Http\Controllers\Setting\Api\ServiceCatalogController;
use App\Http\Controllers\Setting\Api\ServiceCatalogDetailController;
use App\Http\Controllers\Layanan\Api\EskalasiController;
use App\Http\Controllers\Layanan\Api\LayananController;
use App\Http\Controllers\Layanan\Api\TindakLanjutController;
use App\Http\Controllers\Layanan\LayananController as LayananLayananController;
use App\Http\Controllers\System\Api\PegawaiController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group([ 'middleware' => ['keycloak']], function () {
    Route::get('dashboard',[DashboardController::class,'index']);
    Route::get('layanan/status-layanan',[LayananController::class,'statusLayanan']);
    Route::get('layanan/jenis-layanan',[LayananController::class,'jenisLayanan']);
    Route::get('layanan/prioritas-layanan',[LayananController::class,'prioritasLayanan']);
    Route::delete('/layanan/kategori/{kategori}', [LayananController::class,'destroyKategori']);
    Route::resource('layanan', LayananController::class)->except('edit','create');


    Route::get('/eskalasi/group-solver/{layanan}', [EskalasiController::class,'getGroupSolver']);
    Route::get('/eskalasi/group-solver',[EskalasiController::class,'showGroupSolver']);
    Route::post('/eskalasi/group-solver',[EskalasiController::class,'storeGroupSolver']);
    Route::delete('/eskalasi/group-solver/{layanan}', [EskalasiController::class,'destroyGroupSolver']);


    Route::get('/eskalasi/solver/{layanan}', [EskalasiController::class,'getSolver']);
    Route::get('/eskalasi/solver',[EskalasiController::class,'showSolver']);
    Route::post('/eskalasi/solver',[EskalasiController::class,'storeSolver']);
    Route::delete('/eskalasi/solver/{layanan}', [EskalasiController::class,'destroySolver']);
    Route::patch('/eskalasi/{id}/solver', [EskalasiController::class,'updateSolver']);
    Route::resource('layanan-tl', TindakLanjutController::class)->only('store','update','destroy');

    Route::get('/pegawai/userinfo', function (Request $request) {
        $user = User::where('NIP',$request->user()->pegawai->Nip)->first();
        $pegawai = [
            'Nip' => $request->user()->pegawai->Nip,
            'NmPeg' => $request->user()->pegawai->NmPeg,
            'KdUnitOrg' => $request->user()->pegawai->KdUnitOrg,
            'NmUnitOrg' => $request->user()->pegawai->NmUnitOrg,
            'NmUnitOrgInduk' => $request->user()->pegawai->NmUnitOrgInduk,
            'NoHp' => $request->user()->pegawai->NoHp,
        ];
        $pegawai['roles'] = $user->roles->pluck('name')->toArray();
        return $pegawai;
    });
    Route::get('/pegawai', [PegawaiController::class,'index']);
    Route::get('/pegawai/{nip}', [PegawaiController::class,'show']);
    Route::delete('/storage/{file}', [LayananLayananController::class,'destroyFile']);
    Route::get('/{disk}/storage/{fileName}', function ($disk,$fileName) {
        if(request()->Id){
            // $fileName = '/'.request()->folder.'/'.request()->Id.'/'.$fileName.'.jpg' ;
            $fileName = '/'.request()->folder.'/'.request()->Id.'/'.request()->NmFile ;
            // dd($fileName,$fileName2);
        }
        if (!Storage::disk($disk)->exists($fileName)) {
            abort(404, 'Tidak ditemukan, silahkan upload ulang File');
        }
        $file = Storage::disk($disk)->get($fileName);
        $type = Storage::disk($disk)->mimeType($fileName);

        if(request()->download==1){
            return Storage::disk($disk)->download($fileName);

        }
        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);

        return $response;
    });
});

Route::group(['prefix' => 'setting', 'as' => 'setting.', 'middleware' => ['keycloak']], function () {
    Route::get('/kategori', [KategoriController::class,'index']);
    Route::get('/service-catalog', [ServiceCatalogController::class,'index']);
    Route::get('/service-catalog-detail', [ServiceCatalogDetailController::class,'index']);
});

