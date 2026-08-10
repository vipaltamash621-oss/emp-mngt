<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\Employee;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //

        $leaves = Leave::all();
        return view('admin.leave.index', compact('leaves'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $employees = Employee::all();
        
        return view('admin.leave.create', ['employees' => $employees]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        Leave::create($request->all());
        return back()->with('success', 'Leave added');
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Leave $leave)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
        $leave = Leave::findOrFail($id);
        $employees = Employee::all();
        return view('admin.leave.edit',compact('leave', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'employee_id' => 'required|exists:employees,id',
                'leave_from' => 'required|date|after_or_equal:today',
                'leave_to' => 'required|date|after_or_equal:leave_from',
                'reason' => 'nullable|string|max:500',
                'status' => 'nullable|in:0,1',
            ]);

            $leave = Leave::findOrFail($id);
            $leave->update($validated);

            return back()->with('success', 'Leave record updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update leave record: ' . $e->getMessage());
        }
    }

    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $leave = Leave::findOrFail($id);
            $leave->delete();

            return back()->with('success', 'Leave record deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete leave record: ' . $e->getMessage());
        }
    }
}
