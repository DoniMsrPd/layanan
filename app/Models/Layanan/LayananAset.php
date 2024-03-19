<?php

namespace App\Models\Layanan;

use Uuids;
use Illuminate\Database\Eloquent\Model;
use Modules\Layanan\Services\LayananService;

class LayananAset extends Model
{
    protected $guarded = [];
    protected $table = 'LayananAset';
    const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = 'UpdatedAt';
    // public $incrementing = false;
    protected $primaryKey = 'Id';
    public $timestamps = true;
    protected $keyType = 'string';
    protected $fillable = ['AsetLayananId','AsetSMAId' ,'Keterangan', 'LayananTLId', 'LayananId','Fisik','Kelengkapan','Data','NoBox'];
    protected $with = ['asetLayanan','asetSMA','pihak2','layanan','pengembali','pihak2pengembalian'];
    protected $appends = ['NamaBarang','NomorIKN','SN'];

    public function getNamaBarangAttribute()
    {

        if($this->attributes['AsetLayananId']){
            $nmBrg =  $this->asetLayanan->JenisAset.' '.$this->asetLayanan->TypeAset.' '.$this->asetLayanan->Nama;
        }else{
            $nmBrg = $this->asetSma->nm_brg.' '.$this->asetSma->nm_lgkp_brg;
        }
        return $nmBrg;
    }
    public function getNomorIKNAttribute()
    {

        if($this->attributes['AsetLayananId']){
            $nomorIKN =  $this->asetLayanan->NoIkn1.' '.$this->asetLayanan->NoIkn2;
        }else{
            $nomorIKN = $this->asetSma->no_ikn;
        }
        return $nomorIKN;
    }
    public function getSNAttribute()
    {

        if($this->attributes['AsetLayananId']){
            $nomorIKN =  $this->asetLayanan->SerialNumber;
        }else{
            $nomorIKN = $this->asetSma->keterangan;
        }
        return $nomorIKN;
    }
    protected static function booted(): void
    {
        static::creating(function (self $model) {
            $model->UpdatedAt = null;
            $model->CreatedBy = auth()->user()->NIP;
        });
    }

    public function asetLayanan()
    {
        return $this->belongsTo('App\Models\Setting\Aset', 'AsetLayananId', 'Id');
    }
    public function asetSma()
    {
        return $this->belongsTo('App\Models\Setting\AsetSMA', 'AsetSMAId', 'Id');
    }
    public function pihak2()
    {
        return $this->belongsTo('App\Models\System\Pegawai', 'NipPihak2', 'Nip');
    }
    public function pihak2pengembalian()
    {
        return $this->belongsTo('App\Models\System\Pegawai', 'NipPengembalianAsetPihak2', 'Nip');
    }
    public function pengembali()
    {
        return $this->belongsTo('App\Models\System\Pegawai', 'NipPengembalianAset', 'Nip');
    }
    public function layanan()
    {
        return $this->belongsTo('App\Models\Layanan\Layanan', 'LayananId', 'Id');
    }

    public function scopeFiltered($query)
    {
        if (request()->tglStart) {
            $query->whereHas('layanan', function ($q) {
                $q->whereBetween('Layanan.CreatedAt', [request()->tglStart . ' 00:00:00', request()->tglEnd . ' 23:59:59']);
            });
        }
        if (request()->statusLayanan)
            $query->whereHas('layanan', function ($q) {
                $q->whereIn('StatusLayanan', request()->statusLayanan);
            });

        if (request()->solver) {
            $solver = request()->solver;
            $query->whereHas('layanan.solver', function ($q) use ($solver)  {
                $q->whereIn('Nip', $solver);
            });
        }

    }
}
