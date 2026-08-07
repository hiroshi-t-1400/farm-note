<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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

            // Eagerロードしているとき
            'createdBy' => $this->whenLoaded('createdBy', function () {
                return [
                    'createdLogId' => $this->createdBy->id,
                ];
            }),

            'updatedBy' => $this->whenLoaded('updatedBy', function () {
                return [
                    'updatedLogId' => $this->updatedBy->id,
                ];
            }),

            // pivotロード
            'performedBy' => $this->whenLoaded('performedBy', function () {
                return [
                    'performedLogId' => UserResource::collection($this->performedBy->id),
                ];
            }),
        ];

    }
}
