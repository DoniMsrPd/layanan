<?php

namespace App\Models\Layanan;
use Uuids;
use Illuminate\Database\Eloquent\Model;

class LayananNotifikasi extends Model
{
    protected $guarded = [];
    protected $table = 'LayananNotifikasi';
    // public $incrementing = false;
    protected $primaryKey = 'No';
	public $timestamps = false;
}
