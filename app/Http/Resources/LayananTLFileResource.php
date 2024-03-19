<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LayananTLFileResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'Path' => "/core/".$this->PathFile,
            'Nama' => $this->NmFileOriginal
        ];
    }
}