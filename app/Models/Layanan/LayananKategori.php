<?php

namespace App\Models\Layanan;
use Uuids;
use Illuminate\Database\Eloquent\Model;

class LayananKategori extends Model
{
    protected $guarded = [];
    protected $table = 'LayananKategori';
    // public $incrementing = false;
    protected $primaryKey = 'Id';
	public $timestamps = false;
    protected $keyType = 'string';
    protected $with = ['mstKategori'];
    public function mstKategori()
    {
        return $this->belongsTo('App\Models\Setting\Kategori', 'MstKategoriId', 'Id');
    }
}
