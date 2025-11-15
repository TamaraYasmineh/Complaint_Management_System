<?php

namespace App\Http\Controllers;

use App\Http\Requests\ComplaintRequest;
use App\Http\Resources\ComplaintResource;
use App\Http\Services\ComplaintService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Http\Services;

class ComplaintController extends Controller
{
     protected ComplaintService $service;

    public function __construct(ComplaintService $service)
    {
        $this->service = $service;
    }
  public function store(ComplaintRequest $request)
    {
        $complaint = $this->service->createComplaint($request->validated());

        return new ComplaintResource($complaint);
    }


}
