<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeaveRequest extends FormRequest
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
            'employee_id' => 'required|exists:employees,id',
            'leave_from' => 'required|date|after_or_equal:today',
            'leave_to' => 'required|date|after_or_equal:leave_from',
            'reason' => 'nullable|string|max:500',
            'status' => 'nullable|in:0,1',
        ];
    }
}
