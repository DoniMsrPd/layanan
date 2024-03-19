<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LayananFileResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'Id' => $this->Id,
            'Nama' => $this->NmFileOriginal,
            'Path' =>"/core/".$this->PathFile.'?download=1',
        ];
    }
}