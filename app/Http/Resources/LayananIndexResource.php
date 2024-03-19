<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LayananIndexResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'Id' => $this->Id,
            'NoTicket' => $this->NoTicket,
            'NoTicketRandom' => $this->NoTicketRandom,
            'PermintaanLayanan' => $this->PermintaanLayanan,
            'StatusLayanan' => $this->status?->Nama,
            'CreatedAt' => $this->CreatedAt,
            'TglLayanan' => $this->TglLayanan,
            'tl' => LayananTLIndexResource::collection($this->tl),
            'ButtonGroupSolver' => !$this->DeletedAt && (request()->user()->can('layanan.eskalasi.all') || auth()->user()->hasRole('Pejabat Struktural')),
            'AllSolver' => $this->AllSolver
        ];
    }
}
