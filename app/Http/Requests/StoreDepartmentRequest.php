<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDepartmentRequest extends FormRequest
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
            'title' => 'required|string|max:255|unique:departments,title,' . ($this->department->id ?? 'NULL'),
            'slug' => 'required|string|max:255|unique:departments,slug,' . ($this->department->id ?? 'NULL'),
            'status' => 'nullable|boolean',
        ];
    }
}
