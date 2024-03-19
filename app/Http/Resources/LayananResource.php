<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LayananResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'Id' => $this->Id,
            'Nip' => $this->Nip,
            'NmPeg' => $this->NmPeg,
            'KdUnitOrg' => $this->KdUnitOrg,
            'NmUnitOrg' => $this->NmUnitOrg,
            'NmUnitOrgInduk' => $this->NmUnitOrgInduk,
            'NipLayanan' => $this->NipLayanan,
            'KdUnitOrgLayanan' => $this->KdUnitOrgLayanan,
            'NmPegLayanan' => optional($this->penerima)->NmPeg,
            'NomorKontak' => $this->NomorKontak,
            'NoTicket' => $this->NoTicket,
            'NoTicketRandom' => $this->NoTicketRandom,
            'PermintaanLayanan' => $this->PermintaanLayanan,
            'CreatedAt' => $this->CreatedAt,
            'KodeITSM' => optional($this->serviceCatalog)->Kode.' '.optional($this->serviceCatalog)->Nama ,
            'SLA' => optional($this->sla)->Nama ,
            'NipOperator' => optional($this->operatorOpen)->Nip ,
            'NmPegOperator' => optional($this->operatorOpen)->NmPeg ,
            'JenisLayanan' => optional($this->jenis)->Nama ,
            'LayananKategori' => LayananKategoriResource::collection($this->LayananKategori),
            'File' => LayananFileResource::collection($this->files),
            'TL' => LayananTLResource::collection($this->tl),
            'Prioritas' => optional($this->prioritas)->Id ,
            'ServiceCatalogId' => $this->ServiceCatalogId,
            'ServiceCatalogNama' => $this->ServiceCatalogNama,
            'ServiceCatalogKode' => $this->ServiceCatalogKode,
            'ServiceCatalogDetailId' => $this->ServiceCatalogDetailId,
            'JenisLayananId' => $this->JenisLayanan,
            'KeteranganLayanan' => $this->KeteranganLayanan,
        ];
    }
}