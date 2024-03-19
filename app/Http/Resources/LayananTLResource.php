<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LayananTLResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'Id' => $this->Id,
            'NmPeg' => optional($this->pegawai)->NmPeg ,
            'TglTL' => $this->UpdatedAt??$this->CreatedAt,
            'Keterangan' => $this->Keterangan,
            'StatusTL' => LayananTLStatusResource::collection($this->status),
            'File' => LayananTLFileResource::collection($this->files),
            'CanDelete' => auth()->user()->can('layanan.tl.delete-all')||(auth()->user()->NIP==$this->CreatedBy),
            'CanUpdate' => auth()->user()->can('layanan.tl.update-all')||(auth()->user()->NIP==$this->CreatedBy)
        ];
    }
}