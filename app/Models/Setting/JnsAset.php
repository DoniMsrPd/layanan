<?php

namespace App\Models\Setting;

use Illuminate\Database\Eloquent\Model;

class JnsAset extends Model
{
    protected $guarded = [];
    protected $table = 'RefJnsAset';
    public $incrementing = false;
    protected $primaryKey = 'Id';
	public $timestamps = false;
    protected $keyType = 'string';
}
