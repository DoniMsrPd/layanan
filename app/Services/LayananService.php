<?php

namespace App\Services;

use App\Models\Layanan\Layanan;
use App\Models\Layanan\LayananAset;
use App\Models\Layanan\Peminjaman;
use App\Models\Layanan\Pengembalian;
use App\Models\Layanan\PersediaanDistribusi;
use App\Notifications\GenericNotification;
use Notification;

class LayananService
{
    public function getAllLayanan($option = [],$joinStatus = true)
    {
        $Nip = auth()->user()->NIP;
        $data = Layanan::whereNull('Layanan.DeletedAt');
        if (auth()->user()->hasRole(['Operator', 'SuperUser', 'Admin Proses Bisnis'])) {
            if (auth()->user()->hasRole(['SuperUser', 'Admin Proses Bisnis'])) {
                $data = Layanan::whereNull('Layanan.DeletedAt');
            }
            if(request()->pending==1){
                $data->whereNull('DeletedAt');
            }
        }   else if (auth()->user()->hasRole('Struktural TI')) {
            $data->whereRaw("((Layanan.id in (select LayananId from LayananGroupSolver where DeletedAt IS NULL and [MstGroupSolverId] in (SELECT [MstGroupSolverId] FROM [Melati_V3].[dbo].[MstSolver] where nip='$Nip'))) or Layanan.CreatedBy ='$Nip')");
        } elseif (auth()->user()->hasRole('Solver')) {
            $updatedByMe = '';
            if(request()->updatedByMe==1 || (isset($option['updatedByMe'])&&$option['updatedByMe']==TRUE) ){
                $updatedByMe="OR Layanan.Id in (Select LayananId From LayananLog where CreatedBy ='$Nip'  )";
            }
            $data->whereRaw("
            (
            Layanan.id in (select LayananId from LayananSolver where nip='$Nip' and DeletedAt IS NULL)
            or ( AllSolver is null and
            Layanan.id in (select LayananId from LayananGroupSolver where DeletedAt IS NULL and [MstGroupSolverId] in (SELECT [MstGroupSolverId] FROM [Melati_V3].[dbo].[MstSolver] where nip='$Nip') and DeletedAt IS NULL)
            ) $updatedByMe or Layanan.CreatedBy ='$Nip'
            ) ");
        } else {
            $data = Layanan::where(function($q){
                $q->whereNull('Layanan.DeletedAt')->orWhereNotNull('ParentId');
            })->whereRaw("(nip='$Nip' or NipLayanan='$Nip' ) ");
        }
        if(request()->assignToMe==1){
            $data = Layanan::whereNull('Layanan.DeletedAt');
            $data->whereRaw(" Layanan.id in (select LayananId from LayananSolver where nip='$Nip' and DeletedAt IS NULL)");
        }
        if(request()->updatedByMe==1){
            $data->whereRaw("Layanan.Id in (Select LayananId From LayananLog where CreatedBy ='$Nip'  )");
        }
        if(request()->createdByMe==1){
            $data->whereRaw("(Layanan.CreatedBy  ='$Nip' or Layanan.Nip ='$Nip' or NipLayanan ='$Nip' ) ");
        }
        if ($joinStatus ) {
            $data->selectRaw('Layanan.*,RefStatusLayanan.Nama AS NamaStatusLayanan')->leftJoin('RefStatusLayanan', function ($join) {
                $join->on('RefStatusLayanan.Id', '=', 'Layanan.StatusLayanan')
                ->on('Layanan.KdUnitOrgOwnerLayanan','=','RefStatusLayanan.KdUnitOrgOwnerLayanan');
            });
        }
        if (kdUnitOrgOwner()||request()->KdUnitOrgOwnerLayanan) {
            $data->where('Layanan.KdUnitOrgOwnerLayanan',  request()->KdUnitOrgOwnerLayanan ?? kdUnitOrgOwner());
        }
        return $data;
    }
    public function sendMail($subject,$view,$data,$message,$from,$to)
    {
        $notification = new GenericNotification(
            $subject,
            $view,
            $data,
            $message,
            $from
        );
        Notification::send($to, $notification);
    }
    public function noUrutBa()
    {
        $layananAset = LayananAset::selectRaw("ISNULL(max(NoUrutBA),0)+1 NoUrut")->whereYear('CreatedAt',date('Y'))->first();
        return $layananAset->NoUrut;
    }
    public function noUrutBaPengembalian()
    {
        $layananAset = LayananAset::selectRaw("ISNULL(max(NoUrutBAPengembalian),0)+1 NoUrut")->whereYear('CreatedAt',date('Y'))->first();
        return $layananAset->NoUrut;
    }
    public function noUrutBaPeminjaman()
    {
        $peminjaman = Peminjaman::selectRaw("ISNULL(max(NoUrutBA),0)+1 NoUrut")->whereYear('CreatedAt',date('Y'))->first();
        return $peminjaman->NoUrut;
    }
    public function noUrutBaPengembalian2()
    {
        $pengembalian = Pengembalian::selectRaw("ISNULL(max(NoUrutBA),0)+1 NoUrut")->whereYear('CreatedAt',date('Y'))->first();
        return $pengembalian->NoUrut;
    }
    public function noUrutBaPersediaan()
    {
        $persediaan = PersediaanDistribusi::selectRaw("ISNULL(max(NoUrutBA),0)+1 NoUrut")->whereYear('CreatedAt',date('Y'))->first();
        return $persediaan->NoUrut;
    }
}