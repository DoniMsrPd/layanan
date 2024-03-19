<?php

namespace App\Models\Layanan;

use Uuids;
use Illuminate\Database\Eloquent\Model;

class LayananTL extends Model
{
    protected $guarded = [];
    protected $table = 'LayananTL';
    // public $incrementing = false;
    protected $primaryKey = 'Id';
    public $timestamps = false;
    protected $keyType = 'string';
    protected $with = ['layananPersediaan', 'layananAset', 'layananPeminjaman'];
    public function pegawai()
    {
        return $this->belongsTo('App\Models\System\Pegawai', 'Nip', 'Nip');
    }
    public function status()
    {
        return $this->hasMany('App\Models\Layanan\LayananLog', 'LayananTlId', 'Id')
        ->where('Keterangan','Update Status')->oldest('CreatedAt');
    }
    public function scopeFiltered($query)
    {
        $layanan = Layanan::select(['KdUnitOrgOwnerLayanan'])->where('Id', request()->LayananId)->first();
        if ($layanan != '100205000000' && pegawaiBiasa()) {
            $query->take(2)->whereIn('StatusLayanan', ['4', '5'])->whereNotNull('Keterangan')->orderBy('CreatedAt', 'desc');
        } else {
            $query->orderBy('CreatedAt');
        }
    }
    public function files()
    {
        return $this->hasMany('App\Models\System\MelatiFile', 'TableId', 'Id')
            ->where('TableName', $this->table)
            ->whereNull('deletedAt')->whereNull('JnsFile');
    }
    public function filesOld()
    {
        return $this->hasMany('App\Models\System\MelatiFile', 'TableId', 'LayananIdInc')
            ->where('TableName', $this->table)
            ->whereNull('deletedAt')->whereNull('JnsFile');
    }
    public function layananPersediaan()
    {
        return $this->hasMany('App\Models\Layanan\LayananPersediaan', 'LayananTLId', 'Id')->whereNull('deletedAt');
    }
    public function layananAset()
    {
        return $this->hasMany('App\Models\Layanan\LayananAset', 'LayananTLId', 'Id')->whereNull('deletedAt');
    }
    public function layananPeminjaman()
    {
        return $this->belongsTo('App\Models\Layanan\Peminjaman', 'Id', 'LayananTLId')->whereNull('deletedAt');
    }
    public function layanan()
    {
        return $this->belongsTo('App\Models\Layanan\Layanan', 'LayananId', 'Id');
    }
    public function persediaanDistribusi()
    {
        return $this->belongsTo('App\Models\Layanan\PersediaanDistribusi', 'Id', 'LayananTLId')->whereNull('deletedAt');
    }
}
