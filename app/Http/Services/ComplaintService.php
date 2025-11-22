<?php

namespace App\Http\Services;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\ActivityLogger;
use App\Models\Complaint;

class ComplaintService
{
    // public function createComplaint(array $data): Complaint
    // {
    //    $user=Auth::id();
    //     if (isset($data['image'])) {
    //         $data['image'] = $data['image']->store('complaints/images', 'public');
    //     }

    //     if (isset($data['file'])) {
    //         $data['file'] = $data['file']->store('complaints/files', 'public');
    //     }
    //     $data['userId'] =$user;
    //     return Complaint::create($data);
    // }
    protected $logger;

    public function __construct(ActivityLogger $logger)
    {
        $this->logger = $logger;
    }

    public function createComplaint(array $data): Complaint
    {
        $user = Auth::id();

        if (isset($data['image'])) {
            $data['image'] = $data['image']->store('complaints/images', 'public');
        }

        if (isset($data['file'])) {
            $data['file'] = $data['file']->store('complaints/files', 'public');
        }

        $data['userId'] = $user;
        $complaint = Complaint::create($data);
        $this->logger->log(
            'create_complaint',
            $complaint,    
            null,          
            $complaint->toArray() 
        );

        return $complaint;
    }
}
