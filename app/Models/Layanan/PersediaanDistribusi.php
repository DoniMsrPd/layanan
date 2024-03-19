<?php

namespace App\Models\Layanan;

use Uuids;
use Illuminate\Database\Eloquent\Model;

class PersediaanDistribusi extends Model
{
    protected $guarded = [];
    protected $table = 'PersediaanDistribusi';
    // public $incrementing = false;
    protected $primaryKey = 'Id';
    public $timestamps = false;
    protected $keyType = 'string';
    protected $with = [ 'layanan','pihak2','persediaan'];
    public function persediaan()
    {
        return $this->hasMany('App\Models\Layanan\LayananPersediaan', 'LayananTLId', 'LayananTLId')->whereNull('deletedAt');
    }
    public function layanan()
    {
        return $this->belongsTo('App\Models\Layanan\Layanan', 'LayananId', 'Id');
    }
    public function layananTl()
    {
        return $this->belongsTo('App\Models\Layanan\LayananTL', 'LayananTLId', 'Id');
    }
    public function pihak2()
    {
        return $this->belongsTo('App\Models\System\Pegawai', 'NipPihak2', 'Nip');
    }
    public function pihak1()
    {
        return $this->belongsTo('App\Models\System\Pegawai', 'NipPihak1', 'Nip');
    }
    public function scopeFiltered($query)
    {
        if (request()->tglStart) {
            $query->whereHas('layanan', function ($q) {
                $q->whereBetween('Layanan.CreatedAt', [request()->tglStart . ' 00:00:00', request()->tglEnd . ' 23:59:59']);
            });
        }
    }
}
