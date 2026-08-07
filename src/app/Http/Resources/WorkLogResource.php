<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'workLogId' => $this->id,
            'cropSeasonId' => $this->crop_season_id,
            'createdById' => $this->created_by,
            'updatedById' => $this->updated_by,
            'workDate' => $this->work_date->format('Y-m-d'),
            'workDateHi' => $this->work_date->format('Y-m-d H:i'),
            'workDateTS' => $this->work_date,
            'title' => $this->title,
            'content' => $this->content,
            'createdAt' => $this->created_at->format('Y-m-d'),
            'updatedAt' => $this->updated_at->format('Y-m-d'),

            // eagerロードしているとき
            'cropSeason' => $this->whenLoaded('cropSeason', function () {
                return [
                    'cropName' => $this->cropSeason->crop->name,
                    'fieldName' => $this->cropSeason->field->name,
                    'fieldNotes' => $this->cropSeason->field->notes,
                ];
            }),

            'material' => $this->whenLoaded('material', function () {
                return  $this->material->map(function ($mat) {
                    return [
                        'materialId' => $mat->id,
                        'materialName' => $mat->name,
                        'defaultDilutionRate' => $mat->default_dilution_rate,
                        'standardSprayVolume' => $mat->standard_spray_volume,
                        'unit' => $mat->unit,
                        'manufacturer' => $mat->manufacturer,
                        'materialTypeId' => $mat->materialCategory->id,
                        'materialType' => $mat->materialCategory->label,

                        'quantity' => $mat->pivot->quantity,
                        'dilutionRate' => $mat->pivot->dilution_rate,
                        'materialAmount' => $mat->pivot->material_amount,
                    ];
                });
            }),

        ];
    }
}
