<?php

namespace App\Models\Layanan;

use Uuids;
use Illuminate\Database\Eloquent\Model;

class LayananPersediaan extends Model
{
    protected $guarded = [];
    protected $table = 'LayananPersediaan';
    const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = 'UpdatedAt';
    // public $incrementing = false;
    protected $primaryKey = 'Id';
    public $timestamps = true;
    protected $keyType = 'string';
    protected $fillable = ['MstPersediaanId', 'Keterangan', 'LayananTLId', 'LayananId','Qty'];
    protected $with = ['mstPersediaan'];
    protected $appends = ['NamaBarang','NomorIKN','SN','NmBrg'];
    protected static function booted(): void
    {
        static::creating(function (self $model) {
            $model->UpdatedAt = null;
            $model->CreatedBy = auth()->user()->NIP;
        });
    }
    public function mstPersediaan()
    {
        return $this->belongsTo('App\Models\Setting\Persediaan', 'MstPersediaanId', 'Id');
    }
    public function getNamaBarangAttribute()
    {
        return optional($this->mstPersediaan)->KdBrg . ' ' . optional($this->mstPersediaan)->NmBrg . '<br>' . optional($this->mstPersediaan)->NmBrgLengkap;
    }    public function asetLayanan()
    {
        return $this->belongsTo('App\Models\Setting\Aset', 'AsetLayananId', 'Id');
    }
    public function asetSma()
    {
        return $this->belongsTo('App\Models\Setting\AsetSMA', 'AsetSMAId', 'Id');
    }
    public function getNmBrgAttribute()
    {
        $nmBrg='';
        if($this->attributes['AsetLayananId']){
            $nmBrg =  optional($this->asetLayanan)->JenisAset.' '.optional($this->asetLayanan)->TypeAset.' '.optional($this->asetLayanan)->Nama;
        }elseif($this->attributes['AsetSMAId']){
            $nmBrg = optional($this->asetSma)->nm_brg.' '.optional($this->asetSma)->nm_lgkp_brg;
        }
        return $nmBrg;
    }
    public function getNomorIKNAttribute()
    {
        $nomorIKN = '';
        if($this->attributes['AsetLayananId']){
            $nomorIKN =  optional($this->asetLayanan)->NoIkn1.' '.optional($this->asetLayanan)->NoIkn2;
        }elseif($this->attributes['AsetSMAId']){
            $nomorIKN = optional($this->asetSma)->no_ikn;
        }
        return $nomorIKN;
    }
    public function getSNAttribute()
    {
        $SN = '';
        if($this->attributes['AsetLayananId']){
            $SN =  optional($this->asetLayanan)->SerialNumber;
        }elseif($this->attributes['AsetSMAId']){
            $SN = optional($this->asetSma)->keterangan;
        }
        return $SN;
    }
}
