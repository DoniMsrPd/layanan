<?php

namespace App\Models\Layanan;

use Uuids;
use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    protected $guarded = [];
    protected $table = 'Layanan';
    // public $incrementing = false;
    protected $primaryKey = 'Id';
    public $timestamps = false;
    protected $keyType = 'string';
    protected $with = ['pelapor', 'status', 'layananKategori'];
    public function status()
    {
        $query = $this->belongsTo('App\Models\Layanan\RefStatusLayanan', 'StatusLayanan', 'Id');
        if ($this->KdUnitOrgOwnerLayanan != '100205000000') {
            $query->where('KdUnitOrgOwnerLayanan', kdUnitOrgOwner());
        }

        return $query;
    }
    public function owner()
    {
        return $this->belongsTo(MstUnitOrgLayananOwner::class, 'KdUnitOrgOwnerLayanan', 'KdUnitOrgOwnerLayanan');
    }
    public function jenis()
    {
        return $this->belongsTo('App\Models\Layanan\RefJenisLayanan', 'JenisLayanan', 'Id');
    }
    public function prioritas()
    {
        return $this->belongsTo('App\Models\Layanan\RefPrioritas', 'PrioritasLayanan', 'Id');
    }
    public function pelapor()
    {
        return $this->belongsTo('App\Models\System\Pegawai', 'Nip', 'Nip');
    }
    public function penerima()
    {
        return $this->belongsTo('App\Models\System\Pegawai', 'NipLayanan', 'Nip');
    }
    public function operatorOpen()
    {
        return $this->belongsTo('App\Models\System\Pegawai', 'NipOperatorOpen', 'Nip');
    }
    public function serviceCatalog()
    {
        return $this->belongsTo('App\Models\Setting\ServiceCatalog', 'ServiceCatalogId', 'Id');
    }
    public function sla()
    {
        return $this->belongsTo('App\Models\Setting\ServiceCatalogDetail', 'ServiceCatalogDetailId', 'Id');
    }

    public function files()
    {
        return $this->hasMany('App\Models\System\MelatiFile', 'TableId', 'Id')
            ->where('TableName', $this->table)
            ->whereNull('deletedAt')->whereNull('JnsFile');
    }
    public function filesOld()
    {
        return $this->hasMany('App\Models\System\MelatiFile', 'TableId', 'IdInc')
            ->where('TableName', $this->table)
            ->whereNull('deletedAt')->whereNull('JnsFile');
    }
    public function filesNotaDinas()
    {
        return $this->hasMany('App\Models\System\MelatiFile', 'TableId', 'Id')
            ->where('TableName', $this->table)
            ->whereNull('deletedAt')->where('JnsFile', 1);
    }
    public function filesNotaDinasOld()
    {
        return $this->hasMany('App\Models\System\MelatiFile', 'TableId', 'IdInc')
            ->where('TableName', $this->table)
            ->whereNull('deletedAt')->where('JnsFile', 1);
    }
    public function groupSolver()
    {
        return $this->hasMany('App\Models\Layanan\LayananGroupSolver', 'LayananId', 'Id')
            ->whereNull('DeletedAt');
    }
    public function solver()
    {
        return $this->hasMany('App\Models\Layanan\LayananSolver', 'LayananId', 'Id')
            ->whereNull('DeletedAt');
    }
    public function tl()
    {
        $query = $this->hasMany('App\Models\Layanan\LayananTL', 'LayananId', 'Id')
            ->whereNull('DeletedAt');

        if (pegawaiBiasa() && $this->KdUnitOrgOwnerLayanan != '100205000000') {
            $query->take(2)->whereIn('StatusLayanan', ['4', '5'])->whereNotNull('Keterangan')->orderBy('CreatedAt', 'desc');
        } else {
            $query->orderBy('CreatedAt', 'ASC');
        }

        return $query;
    }
    public function layananKategori()
    {
        return $this->hasMany('App\Models\Layanan\LayananKategori', 'LayananId', 'Id');
    }
    public function scopeFiltered($query)
    {
        //for custom datatable
        $query->when(request()->q, function ($query) {
            $param = '%' . request()->q . '%';

            $query->where(function ($query) use ($param) {
                $query->where('Layanan.Nip', 'like', $param)
                    ->orWhere('PermintaanLayanan', 'like', $param)
                    ->orWhere('NoTicket', 'like', $param)
                    ->orWhere('NoTicketRandom', 'like', $param);
            });
        });
    }
    public function scopeFiltered2($query)
    {

        if (request()->statusLayanan)
            $query->whereIn('StatusLayanan', request()->statusLayanan);
        if (request()->serviceCatalog) {
            if (request()->serviceCatalog[0] == "undefined") {
                $query->whereNull('ServiceCatalogKode');
            } else {
                $query->whereIn('ServiceCatalogKode', request()->serviceCatalog);
            }
        }
        if (request()->prioritasLayanan)
            $query->where('PrioritasLayanan', request()->prioritasLayanan);
        if (request()->groupSolver) {
            $groupSolver = request()->groupSolver;
            $solver = request()->solver;
            if (request()->groupSolver[0] == 'Kosong') {
                $remove = array_shift($groupSolver);
                $query->where(function ($query) use ($groupSolver) {
                    $query->doesntHave('groupSolver')->orwhereHas('groupSolver', function ($q) use ($groupSolver) {
                        $q->whereIn('MstGroupSolverId', $groupSolver);
                    });;
                });
            } else {
                $query->whereHas('groupSolver', function ($q) use ($groupSolver) {
                    $q->whereIn('MstGroupSolverId', $groupSolver);
                });
            }
        }
        if (request()->solver) {
            $solver = request()->solver;
            if (request()->solver[0] == 'Kosong') {
                $remove = array_shift($solver);
                $query->doesntHave('solver')->orwhereHas('solver', function ($q) use ($solver) {
                    $q->whereIn('Nip', $solver);
                });;
            } else {
                $query->whereHas('solver', function ($q) use ($solver) {
                    $q->whereIn('Nip', $solver);
                });
            }
        }
        if (request()->tematik) {
            $tematik = request()->tematik;
            $query->whereHas('serviceCatalog.tematik', function ($q) use ($tematik) {
                $q->whereIn('MstTematikId', $tematik);
            });
        }
    }
}
