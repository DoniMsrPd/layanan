<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MelatiFile extends Model
{
    protected $guard = [];
    protected $table = 'MelatiFile';
    protected $primaryKey  = 'Id';
    public $incrementing = false;
	public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = [
        'Id',
        'TableId',
        'createdBy',
        'NmFileOriginal',
        'NmFile',
        'JnsFile',
        'TableName',
        'PathFile',
        'createdAt'
    ];
}
