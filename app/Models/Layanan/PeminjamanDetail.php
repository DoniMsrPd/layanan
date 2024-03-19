<?php

namespace App\Models\Layanan;

use Carbon\Carbon;
use Uuids;
use Illuminate\Database\Eloquent\Model;

class PeminjamanDetail extends Model
{
    protected $guarded = [];
    protected $table = 'PeminjamanDetail';
    const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = 'UpdatedAt';
    // public $incrementing = false;
    protected $primaryKey = 'Id';
    public $timestamps = true;
    protected $keyType = 'string';
    protected $fillable = ['PeminjamanId', 'KeteranganPeminjaman', 'AsetLayananId'];
    protected $appends = ['NamaBarang', 'NomorIKN', 'SN'];
    protected static function booted(): void
    {
        static::creating(function (self $model) {
            $model->UpdatedAt = null;
            $model->CreatedBy = auth()->user()->NIP;
        });
        static::updating(function (self $model) {
            $model->UpdatedBy = auth()->user()->NIP;
        });
    }
    public function asetLayanan()
    {
        return $this->belongsTo('App\Models\Setting\Aset', 'AsetLayananId', 'Id');
    }
    public function getNamaBarangAttribute()
    {
        return $this->asetLayanan->JenisAset . ' ' . $this->asetLayanan->TypeAset . '<br>' . $this->asetLayanan->Nama;
    }
    public function getNomorIKNAttribute()
    {
        return $this->asetLayanan->NoIkn1 . ' ' . $this->asetLayanan->NoIkn2;
    }
    public function getSNAttribute()
    {
        return $this->asetLayanan->SerialNumber;
    }
    public function peminjaman()
    {
        return $this->belongsTo('App\Models\Layanan\Peminjaman', 'PeminjamanId', 'Id');
    }
}
