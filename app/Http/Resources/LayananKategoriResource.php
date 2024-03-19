<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LayananKategoriResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'Id' => $this->Id,
            'Nama' => $this->mstKategori->Nama,
            'Keterangan' => $this->mstKategori->Keterangan,
        ];
    }
}