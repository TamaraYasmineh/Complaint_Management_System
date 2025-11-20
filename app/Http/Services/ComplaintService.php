<?php

namespace App\Http\Services;
use Illuminate\Support\Facades\Auth;

use App\Models\Complaint;

class ComplaintService
{
    public function createComplaint(array $data): Complaint
    {

        if (isset($data['image'])) {
            $data['image'] = $data['image']->store('complaints/images', 'public');
        }

        if (isset($data['file'])) {
            $data['file'] = $data['file']->store('complaints/files', 'public');
        }
        $data['userId'] =auth()->id();
        return Complaint::create($data);
    }
}
