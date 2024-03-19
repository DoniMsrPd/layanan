<?php

namespace App\Models\Layanan;
use Uuids;
use Illuminate\Database\Eloquent\Model;

class RefStatusPeminjaman extends Model
{
    protected $guarded = [];
    protected $table = 'RefStatusPeminjaman';
    // public $incrementing = false;
    protected $primaryKey = 'Id';
	public $timestamps = false;
}
