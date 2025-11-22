<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Services;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Http\Requests\ComplaintRequest;
use App\Http\Services\ComplaintService;
use App\Http\Resources\ComplaintResource;
use App\Http\Resources\GetComplaintResource;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\ActivityLogger;
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

    public function showAllComplaints()
    {
        $complaints = Complaint::all();
        return GetComplaintResource::collection($complaints);
    }

    public function countAllComplaints()
    {
        $count = Complaint::count();

        return response()->json([
            'totalComplaints' => $count
        ]);
    }

    public function countPendingComplaints()
    {
        $count = Complaint::where('status', 'pending')->count();

        return response()->json([
            'pending_complaints' => $count
        ]);
    }

    public function countNewComplaints()
    {
        $count = Complaint::where('status', 'new')->count();

        return response()->json([
            'new_complaints' => $count
        ]);
    }

    public function getByConcerned($concerned)
    {
        $complaints = Complaint::where('theConcerned', $concerned)->get();
        return GetComplaintResource::collection($complaints);
    }

    public function complaintsForDepartment()
    {
        $user=Auth::user();
        $department = $user->department;
        $complaints = Complaint::where('theConcerned', $department)->get();

        return GetComplaintResource::collection($complaints);
    }

    public function updateComplaint(Request $request, $id)
    {
        $request->validate([
            'status' => 'in:new,pending,completed,rejected',
            'notesForEmployee' => 'nullable|string'
        ]);

        $complaint = Complaint::findOrFail($id);
        $user = Auth::user();
       // if ($complaint->theConcerned !== auth()->user()->department) {
        if ($complaint->theConcerned !== $user->department) {
            return response()->json(['error' => 'غير مسموح بتعديل شكوى ليست تابعة لجهتك'], 403);
        }
        $before = $complaint->toArray();
        $complaint->status = $request->status ?? $complaint->status;
        $complaint->notesForEmployee = $request->notesForEmployee;
        $complaint->save();
        $after = $complaint->toArray();
        ActivityLogger::log(
            'update_complaint',
            $complaint,
            $before,
            $after
        );
        return new GetComplaintResource($complaint);
    }
}
