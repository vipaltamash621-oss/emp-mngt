<?php

namespace App\Http\Controllers;

use App\Models\Depart;
use App\Models\Employee;
use Illuminate\Http\Request;

class DepartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departs = Depart::with('employee')->paginate(15);
        return view('admin.depart.index', compact('departs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employee::all();
        return view('admin.depart.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'employee_id' => 'required|exists:employees,id',
                'depart_date' => 'required|date',
                'depart_time' => 'required|date_format:H:i',
                'type' => 'nullable|boolean',
                'status' => 'nullable|boolean',
            ]);

            Depart::create($validated);
            return redirect()->route('depart.index')->with('success', 'Depart record created successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Depart $depart)
    {
        return view('admin.depart.show', compact('depart'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Depart $depart)
    {
        $employees = Employee::all();
        return view('admin.depart.edit', compact('depart', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Depart $depart)
    {
        try {
            $validated = $request->validate([
                'employee_id' => 'required|exists:employees,id',
                'depart_date' => 'required|date',
                'depart_time' => 'required|date_format:H:i',
                'type' => 'nullable|boolean',
                'status' => 'nullable|boolean',
            ]);

            $depart->update($validated);
            return back()->with('success', 'Depart record updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Depart $depart)
    {
        try {
            $depart->delete();
            return back()->with('success', 'Depart record deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
