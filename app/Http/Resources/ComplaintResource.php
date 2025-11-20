<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ComplaintResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
         return [
            'id' => $this->id,
            'reference number' => $this->referenceNumber,
            // 'type' => $this->type,
            // 'theConcerned' => $this->theConcerned,
            // 'address' => $this->address,
            // 'description' => $this->description,
            // 'image' => $this->image ? asset('storage/'.$this->image) : null,
            // 'file' => $this->file ? asset('storage/'.$this->file) : null,
            'status' => 'new',
            'userId' => $this->userId,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
