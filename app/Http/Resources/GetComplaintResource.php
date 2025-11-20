<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetComplaintResource extends JsonResource
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
            'referenceNumber' => $this->referenceNumber,
            'type' => $this->type,
            'name' => User::where('id', $this->userId)->value('name'),
            'theConcerned' => $this->theConcerned,
            'address' => $this->address,
            'description' => $this->description,

            'image' => $this->image
                ? asset('storage/' . $this->image)
                : null,

            'file' => $this->file
                ? asset('storage/' . $this->file)
                : null,
            'notesForEmployee '=>$this->notesForEmployee?:null,
            'status' => $this->status,

            'created_at' => $this->created_at
                ? $this->created_at->format('Y-m-d H:i:s')
                : null,
        ];
    }
}
