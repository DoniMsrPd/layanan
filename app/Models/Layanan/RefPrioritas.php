<?php

namespace App\Models\Layanan;
use Uuids;
use Illuminate\Database\Eloquent\Model;

class RefPrioritas extends Model
{
    protected $guarded = [];
    protected $table = 'RefPrioritas';
    // public $incrementing = false;
    protected $primaryKey = 'No';
	public $timestamps = false;
}
