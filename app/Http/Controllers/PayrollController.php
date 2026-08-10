<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\Employee;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $employees = Employee::all();
       // Calculate salary for each employee based on attendance and formulas
       return view('admin.payroll.index', ['employees' => $employees]);
       
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    
         // Fetch employees and dates from your data source
    $employees = Employee::all(); // Replace 'Employee' with your actual model class
    $today =today();
    $dates = [];

    for ($i = 1; $i <= $today->daysInMonth; ++$i) {
        $dates[] = $i;
    }
    
    return view('admin.payroll.create', compact('employees','dates'));
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            if ($request->employee_id) {
                $currentYear = date('Y');
                $currentMonth = date('m');
                
                $employee = Employee::find($request->employee_id);
                
                if ($employee) {
                    $payroll = new Payroll();
                    $payroll->employee_id = $request->employee_id;
                    $payroll->year = $currentYear;
                    $payroll->month = $currentMonth;
                    $payroll->basic = $request->basic;
                    $payroll->house_rent = $request->house_rent ?? 0;
                    $payroll->medical = $request->medical ?? 0;
                    $payroll->transport = $request->transport ?? 0;
                    $payroll->phone_bill = $request->phone_bill ?? 0;
                    $payroll->internet_bill = $request->internet_bill ?? 0;
                    $payroll->special = $request->special ?? 0;
                    $payroll->days_present = $request->days_present ?? 0;
                    $payroll->days_absent = $request->days_absent ?? 0;
                    $payroll->gross_salary = $request->gross_salary ?? 0;
                    $payroll->provident_fund = $request->provident_fund ?? 0;
                    $payroll->income_tax = $request->income_tax ?? 0;
                    $payroll->life_insurance = $request->life_insurance ?? 0;
                    $payroll->health_insurance = $request->health_insurance ?? 0;
                    $payroll->deduction = $request->deduction ?? 0;
                    $payroll->net_salary = $request->net_salary ?? 0;
                    $payroll->save();
                    
                    return redirect()->route('admin.payroll.index')->with('success', 'Payroll data has been saved successfully.');
                }
            }
            
            return redirect()->back()->with('error', 'Failed to save payroll data.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Payroll $payroll)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payroll $payroll)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payroll $payroll)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payroll $payroll)
    {
        //
    }

    public function grossSalary() {
        $employees = Employee::all();
        return view('admin.payroll.gross', compact('employees'));
    }

    public function calculatePayroll(Request $request) {
        // dd($request);
        if (isset($request->payroll)) {
            foreach ($request->payroll as $employeeId => $payrollData) {
                // Get the current year and month
                $currentYear = date('Y');
                $currentMonth = date('m');
                
                // foreach ($payrollData as $year => $yearData) {
                    // foreach ($yearData as $month => $payrollFields) {
                        if ($employee = Employee::whereId($employeeId)->first()) {
                            $data = new Payroll();
                            $data->employee_id = $employeeId;
                            
                            // Use the current year and month for the entry
                            $data->year = $currentYear;
                            $data->month = $currentMonth;
                            
                            // Process individual payroll fields
                            // $data->basic = $payrollFields['basic'];
                            // $data->house_rent = $payrollFields['house_rent'];
                            // $data->medical = $payrollFields['medical'];
                            $data->basic = $payrollData['basic'];
                            $data->house_rent = $payrollData['house_rent'];
                            $data->medical = $payrollData['medical'];
                            $data->transport = $payrollData['transport'];
                            $data->phone_bill = $payrollData['phone_bill'];
                            $data->internet_bill = $payrollData['internet_bill'];
                            $data->special = $payrollData['special'];
                            // $data->bonus = $payrollData['bonus'];
                            $data->days_present = $payrollData['days_present'];
                            $data->days_absent = $payrollData['days_absent'];
                            $data->gross_salary = $payrollData['gross_salary'];
                            $data->provident_fund = $payrollData['provident_fund'];
                            // $data->advanced = $payrollData['advanced'];
                            $data->income_tax = $payrollData['income_tax'];
                            $data->life_insurance = $payrollData['life_insurance'];
                            $data->health_insurance = $payrollData['health_insurance'];
                            $data->deduction = $payrollData['deduction'];
                            $data->net_salary = $payrollData['net_salary'];
                            // Add more fields as needed
                            
                            // Additional salary calculation logic can be added here
                            
                            $data->save();
                        }
                    // }
                // }
            }
        }
        return back();
    }

    public function sheetReport()
    {
        $employees = Employee::all();
        $months = [
            1 => 'January',
            2 => 'February',
            // ... Define months for all 12 months
        ];

        return view('admin.payroll.report', compact('employees', 'months'));
    }

    public function generateReport(Request $request)
    {
        $selectedYear = $request->input('year');
        $selectedMonth = $request->input('month');
        $employees = Employee::all();
        $salaryData = [];

        if ($selectedYear && $selectedMonth) {
            $salaryData = Payroll::whereIn('employee_id', $employees->pluck('id'))
                ->whereYear('year', $selectedYear)
                ->whereMonth('month', $selectedMonth)
                ->get();
        }

        // if ($selectedYear && $selectedMonth) {
        //     $salaryData = Payroll::whereYear('year', $selectedYear)
        //         ->whereMonth('month', $selectedMonth)
        //         ->with('employee') // Load the employee relationship
        //         ->get();
    
        //     return view('admin.payroll.report', compact('salaryData', 'selectedYear', 'selectedMonth'));
        // }
    
        // return view('admin.payroll.report');

        return view('admin.payroll.report', compact('employees', 'selectedYear', 'selectedMonth', 'salaryData'));
    }
}
