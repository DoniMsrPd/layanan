<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LayananTLIndexResource extends JsonResource
{
    public function toArray($request, $index = false)
    {
        return [
            'CreatedAt' => $this->CreatedAt,
            'Keterangan' => $this->Keterangan,
            'LayananId' => $this->LayananId,
        ];
    }
}