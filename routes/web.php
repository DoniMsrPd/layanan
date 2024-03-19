<?php

use App\Http\Controllers\Layanan\BaPeminjamanController;
use App\Http\Controllers\Layanan\BaPerbaikanController;
use App\Http\Controllers\Layanan\BaPersediaanController;
use App\Http\Controllers\Layanan\EskalasiController;
use App\Http\Controllers\Layanan\LayananController;
use App\Http\Controllers\Layanan\TindakLanjutController;
use App\Http\Controllers\Setting\AsetController;
use App\Http\Controllers\Setting\GroupSolverController;
use App\Http\Controllers\Setting\JenisAsetController;
use App\Http\Controllers\Setting\KategoriController;
use App\Http\Controllers\Setting\LayananOwnerController;
use App\Http\Controllers\Setting\MstTematikController;
use App\Http\Controllers\Setting\PersediaanController;
use App\Http\Controllers\Setting\ServiceCatalogController;
use App\Http\Controllers\Setting\ServiceCatalogDetailController;
use App\Http\Controllers\Setting\ServiceCatalogTematikController;
use App\Http\Controllers\Setting\SolverController;
use App\Http\Controllers\Setting\TemplatePenyelesaianController;
use App\Http\Controllers\Setting\TypeAsetController;
use App\Http\Controllers\System\AuthController;
use App\Http\Controllers\System\CoreController;
use App\Http\Controllers\System\DashboardController;
use App\Http\Controllers\System\DocumentController;
use App\Http\Controllers\System\PegawaiController;
use App\Http\Controllers\System\PermissionController;
use App\Http\Controllers\System\RoleController;
use App\Http\Controllers\System\SpgUnitOrgController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// onlyoffice
Route::get('doc/get/{file_id}', [DocumentController::class, 'get'])->name('doc.get');
Route::get('doc/full/{nm_file}', [DocumentController::class, 'full']);
Route::get('doc/view/{nm_file}', [DocumentController::class, 'view']);
Route::get('doc/edit/{nm_file}', [DocumentController::class, 'edit']);

Route::get('/', function (Request $request) {
    if (config('keycloak.enable')) {
        return Socialite::driver('keycloak')->redirect();
    }
    return redirect('/login');
});

Route::get('/sso/login', function (Request $request) {
	return Socialite::driver('keycloak')->redirect();
})->name('sso.login');

Route::any('/logout', function () {
    Auth::logout();
    request()->session()->flush();
    request()->session()->save();
    return redirect(Socialite::driver('keycloak')->getLogoutUrl(url('logged-out')));
})->name('logout');

Route::view('/logged-out', 'logged-out');
Route::get('/sso/callback', function (Request $request) {
    $response = Socialite::driver('keycloak')->user();
    $ssoUser = $response->getRaw()['info'];
    $data = [
        'name' => $ssoUser['Name'],
        'nip' => $ssoUser['NIP'],
        'email' => $ssoUser['Email'],
        'password' => '*',
    ];
    $user = App\Models\User::where('NIP',$ssoUser['NIP'])->first();

    Auth::login($user);

    return redirect()->route('dashboard');
});
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware(['auth'])->group(function () {

    // System
    Route::get('/dashboard', [DashboardController::class, 'dashboardEcommerce'])->name('dashboard');
    Route::get('/layanandatatables', [DashboardController::class,'datatablesLayanan'])->name('dashboard.layanan.datatables');
    Route::post('/dashboard-data',[DashboardController::class,'dashboardData'])->name('dashboard.data');


    // layanan
        Route::post('layanan/datatable', [LayananController::class, 'datatables'])->name('layanan.datatables');
        Route::group(['middleware' => ['auth'], 'prefix' => 'layanan', 'as' => 'layanan.'], function () {
            Route::post('/layanan-aset/datatable', [LayananController::class,'datatables'])->name('layanan-aset.datatables');
            Route::post('/layanan-aset/{persediaan}', [LayananController::class,'update'])->name('layanan-aset.update');
            Route::delete('/layanan-aset/{persediaan}', [LayananController::class,'destroy'])->name('layanan-aset.destroy');


            Route::get('/layanan-baru', [LayananController::class,'index_baru'])->name('index-baru');
            Route::get('/merge/{parentId}/{id}', [LayananController::class,'merge'])->name('merge');
            Route::get('/{layanan}/eskalasi', [LayananController::class,'eskalasi'])->name('eskalasi');
            Route::get('/layanan-export', [LayananController::class,'export'])->name('export');
            Route::delete('/kategori/{kategori}', [LayananController::class,'destroyKategori'])->name('destroy-kategori');
        });
        Route::get('/layanan-create-tiket', [LayananController::class,'createTiket'])->name('layanan.create-tiket');
        Route::post('/layanan-store-tiket', [LayananController::class,'storeTiket'])->name('layanan.store-tiket');
        Route::resource('/layanan', LayananController::class);

        Route::get('/ba-persediaan/{persediaanDistribusi}/download', [BaPersediaanController::class,'download'])->name('ba-persediaan.download');
        Route::post('/ba-persediaan/datatable', [BaPersediaanController::class,'datatables'])->name('ba-persediaan.datatables');
        Route::get('/ba-persediaan-export', [BaPersediaanController::class,'export'])->name('ba-persediaan.export');
        Route::resource('/ba-persediaan', BaPersediaanController::class);

        Route::get('/ba-perbaikan/{layananAset}/download', [BaPerbaikanController::class,'download'])->name('ba-perbaikan.download');
        Route::get('/ba-perbaikan/{layananAset}', [BaPerbaikanController::class,'show'])->name('ba-perbaikan.show');
        Route::post('/ba-perbaikan/datatable', [BaPerbaikanController::class,'datatables'])->name('ba-perbaikan.datatables');
        Route::get('/ba-perbaikan-export', [BaPerbaikanController::class,'export'])->name('ba-perbaikan.export');
        Route::resource('/ba-perbaikan', BaPerbaikanController::class);

        Route::get('/ba-peminjaman-export', [BaPeminjamanController::class,'export'])->name('ba-peminjaman.export');
        Route::get('/ba-peminjaman/getDetailPengembalian', [BaPeminjamanController::class,'getDetailPengembalian'])->name('ba-peminjaman.getDetailPengembalian');
        Route::resource('/ba-peminjaman', BaPeminjamanController::class);
        Route::get('/ba-peminjaman/{peminjaman}/download', [BaPeminjamanController::class,'download'])->name('ba-peminjaman.download');
        Route::get('/ba-peminjaman/{peminjaman}/lihat', [BaPeminjamanController::class,'lihat'])->name('ba-peminjaman.lihat');
        Route::post('/ba-peminjaman/datatable', [BaPeminjamanController::class,'datatables'])->name('ba-peminjaman.datatables');
        Route::post('/ba-peminjaman/storePengembalian', [BaPeminjamanController::class,'storePengembalian'])->name('ba-peminjaman.storePengembalian');
        Route::post('/ba-peminjaman/updatePengembalian', [BaPeminjamanController::class,'updatePengembalian'])->name('ba-peminjaman.updatePengembalian');
        Route::get('/ba-peminjaman/{pengembalian}/showPengembalian', [BaPeminjamanController::class,'showPengembalian'])->name('ba-peminjaman.showPengembalian');

        Route::get('/eskalasi/group-solver/{layanan}', [EskalasiController::class,'showGroupSolver'])->name('eskalasi.show-group-solver');
        Route::delete('/eskalasi/group-solver/{layanan}', [EskalasiController::class,'destroyGroupSolver'])->name('eskalasi.destroy-group-solver');
        Route::post('/eskalasi/group-solver', [EskalasiController::class,'storeGroupSolver'])->name('eskalasi.store-group-solver');


        Route::get('/eskalasi/solver/{layanan}', [EskalasiController::class,'showSolver'])->name('eskalasi.show-solver');
        Route::delete('/eskalasi/solver/{layanan}', [EskalasiController::class,'destroySolver'])->name('eskalasi.destroy-solver');
        Route::post('/eskalasi/solver', [EskalasiController::class,'storeSolver'])->name('eskalasi.store-solver');
        Route::patch('/eskalasi/{id}/solver', [EskalasiController::class,'updateSolver'])->name('eskalasi.update-solver');

        Route::resource('/layanan-tl', TindakLanjutController::class);
        Route::post('/layanan-tl/selesaikan/{layanan}', [TindakLanjutController::class,'selesaikan'])->name('layanan-tl.selesaikan');
        Route::delete('/layanan-tl/persediaan/{persediaan}', [TindakLanjutController::class,'destroyPersediaan'])->name('layanan-tl.destroy-persediaan');
        Route::delete('/layanan-tl/aset/{aset}', [TindakLanjutController::class,'destroyAset'])->name('layanan-tl.destroy-aset');
        Route::delete('/layanan-tl/peminjaman-detail/{peminjamanDetail}', [TindakLanjutController::class,'destroyPeminjamanDetail'])->name('layanan-tl.destroy-peminjaman-detail');

        // setting

        Route::group(['middleware' => ['auth'], 'prefix' => 'setting', 'as' => 'setting.'], function () {
            Route::post('/service-catalog-check', [ServiceCatalogController::class,'check'])->name('service-catalog.check');
            Route::post('/service-catalog/datatable', [ServiceCatalogController::class,'datatables'])->name('service-catalog.datatables');
            Route::post('/service-catalog-detail/datatable', [ServiceCatalogDetailController::class,'datatables'])->name('service-catalog-detail.datatables');
            Route::get('/service-catalog-detail/{service_catalog}/create', [ServiceCatalogDetailController::class,'create'])->name('service-catalog-detail.create');
            Route::post('/service-catalog-tematik/datatable', [ServiceCatalogTematikController::class,'datatables'])->name('service-catalog-tematik.datatables');
            Route::post('/jenis-aset/datatable', [JenisAsetController::class,'datatables'])->name('jenis-aset.datatables');
            Route::post('/type-aset/datatable', [TypeAsetController::class,'datatables'])->name('type-aset.datatables');
            Route::get('/type-aset/{jenis_aset}/create', [TypeAsetController::class,'create'])->name('type-aset.create');
            Route::get('/type-aset/select', [TypeAsetController::class,'select']);
            Route::get('/master-tematik/select', [MstTematikController::class,'select']);
            Route::post('/master-tematik/datatable', [MstTematikController::class,'datatables'])->name('master-tematik.datatables');
            Route::post('/aset/datatable', [AsetController::class,'datatables'])->name('aset.datatables');
            Route::delete('/aset/deletePengguna/{aset}', [AsetController::class,'deletePengguna'])->name('aset.deletePengguna');
            Route::post('/group-solver/datatable', [GroupSolverController::class,'datatables'])->name('group-solver.datatables');
            Route::post('/solver/datatable', [SolverController::class,'datatables'])->name('solver.datatables');
            Route::post('/persediaan/datatable', [PersediaanController::class,'datatables'])->name('persediaan.datatables');
            Route::post('/kategori/datatable', [KategoriController::class,'datatables'])->name('kategori.datatables');
            Route::post('/template-penyelesaian/datatable', [TemplatePenyelesaianController::class,'datatables'])->name('template-penyelesaian.datatables');
            Route::post('/layanan-owner/datatable', [LayananOwnerController::class,'datatables'])->name('layanan-owner.datatables');
            Route::resource('/service-catalog', ServiceCatalogController::class);
            Route::resource('/service-catalog-detail', ServiceCatalogDetailController::class)->except(['create']);
            Route::resource('/service-catalog-tematik', ServiceCatalogTematikController::class)->only(['store','destroy']);
            Route::resource('/jenis-aset', JenisAsetController::class);
            Route::resource('/type-aset', TypeAsetController::class)->except(['create','show']);
            Route::resource('/master-tematik', MstTematikController::class);
            Route::resource('/aset', AsetController::class);
            Route::resource('/group-solver', GroupSolverController::class);
            Route::resource('/solver', SolverController::class)->only(['store','destroy']);
            Route::resource('/persediaan', PersediaanController::class);
            Route::resource('/kategori', KategoriController::class);
            Route::resource('/template-penyelesaian', TemplatePenyelesaianController::class);
            Route::resource('/layanan-owner', LayananOwnerController::class)->except('show');
            Route::post('/layanan-owner-check', [LayananOwnerController::class,'check'])->name('layanan-owner.check');

            Route::get('/ref-status-layanan/{unitOrg}', [LayananOwnerController::class,'show']);
            Route::post('/ref-status-layanan/datatable/{unitOrg}', [LayananOwnerController::class,'dataTablesStatusLayanan']);

        });

        Route::group(['middleware' => ['auth'], 'prefix' => 'core', 'as' => 'core.'], function () {

            Route::get('/clear-cache', function () {
                Artisan::call('cache:clear');
                Artisan::call('config:clear');
                Artisan::call('cache:forget spatie.permission.cache');

            });
            Route::get('/dashboard', [CoreController::class,'index'])->name('dashboard');
            Route::resource('/permission', PermissionController::class);
            Route::get('/permissiondatatables', [PermissionController::class,'dataTables'])->name('permission.datatables');
            Route::resource('/role', RoleController::class);
            Route::get('/roledatatables', [RoleController::class,'datatables'])->name('role.datatables');
            Route::get('/roledatatablespegawai', [RoleController::class,'datatablesPegawai'])->name('role.datatablesPegawai');
            Route::post('/role/hapusdetail', [RoleController::class,'hapusdetail'])->name('role.hapus.user');
            Route::post('/role/tambahuser', [RoleController::class,'tambahuser'])->name('role.tambah.user');
            Route::resource('/pegawai', PegawaiController::class);
            Route::get('/userdatatables', [CoreController::class,'userDatatables'])->name('userdatatables');
            Route::get('/pegawai/assignrole/{id}', [PegawaiController::class,'assignRole'])->name('assignrole');
            Route::post('/userrolestore', [RoleController::class,'userRoleStore']);
            Route::get('/pegawaidatatables', [PegawaiController::class,'dataTables'])->name('pegawai.datatables');
            Route::get('/clear-cache', [CoreController::class,'clearCache']);

            Route::post('/spgunitorg/datatables', [SpgUnitOrgController::class,'datatables']);
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
            Route::delete('/storage/{file}', [CoreController::class,'destroyFile']);

            Route::get('/useronline', [CoreController::class,'getUserOnline']);
            Route::post('/notification', [CoreController::class,'notification'])->name('notification');
            Route::get('/notification-count', [CoreController::class,'notificationCount'])->name('notification-count');
        });

});
