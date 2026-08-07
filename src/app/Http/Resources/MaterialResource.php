<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MaterialResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'materialsId' => $this->id,
            'materialsName' => $this->name,
            'defaultDilutionRate' => $this->default_dilution_rate,
            'standardSprayVolume' => $this->standard_spray_volume,
            'unit' => $this->unit,
            'manufacturer' => $this->manufacturer,
            'isReusable' => $this->is_reusable,

            // eagerロードしているときにだけ
            'materialCategory' => $this->whenLoaded('materialCategory', function () {
                return [
                    'id'    => $this->materialCategory->id,
                    'typeLabel' => $this->materialCategory->label,
                ];
            }),
        ];

    }
}
