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
            'id' => $this->id,
            'name' => $this->name,
            'defaultDilutionRate' => $this->default_dilution_rate,
            'standardSprayVolume' => $this->standard_spray_volume,
            'unit' => $this->unit,
            'manufacturer' => $this->manufacturer,
            'isReusable' => $this->is_reusable,

            // eagerロードしているときにだけ

            'typeLabel' => $this->whenLoaded('materialCategory', function () {
                return $this->materialCategory->label;
            }),
            'typeId' => $this->whenLoaded('materialCategory', function () {
                return $this->materialCategory->id;
            }),
            'materialCategory' => $this->whenLoaded('materialCategory', function () {
                return [
                    'id'    => $this->materialCategory->id,
                    'typeLabel' => $this->materialCategory->label,
                ];
            }),
        ];

    }
}
