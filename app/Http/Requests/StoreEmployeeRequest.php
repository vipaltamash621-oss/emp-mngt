<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . ($this->employee->id ?? 'NULL'),
            'phone' => 'nullable|string|max:20|unique:employees,phone,' . ($this->employee->id ?? 'NULL'),
            'department_id' => 'required|exists:departments,id',
            'designation_id' => 'required|exists:designations,id',
            'schedule_id' => 'required|exists:schedules,id',
            'address' => 'nullable|string|max:500',
            'dob' => 'nullable|date|before:today',
            'gender' => 'nullable|in:1,2',
            'religion' => 'nullable|string|max:50',
            'marital' => 'nullable|in:1,2',
            'status' => 'nullable|boolean',
            'unique_id' => 'nullable|string|max:255|unique:employees,unique_id,' . ($this->employee->id ?? 'NULL'),
        ];
    }
}
