<?php

namespace App\Models\Layanan;
use Uuids;
use Illuminate\Database\Eloquent\Model;

class RefJenisLayanan extends Model
{
    protected $guarded = [];
    protected $table = 'RefJenisLayanan';
    // public $incrementing = false;
    protected $primaryKey = 'Id';
	public $timestamps = false;
    protected $keyType = 'string';
}
