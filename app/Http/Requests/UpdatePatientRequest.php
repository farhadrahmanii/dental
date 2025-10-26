<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePatientRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'x_ray_id' => 'nullable|string|max:255',
            'name' => 'sometimes|required|string|max:255',
            'father_name' => 'nullable|string|max:255',
            'sex' => 'nullable|in:male,female,other',
            'age' => 'nullable|integer|min:0|max:120',
            'phone_number' => 'nullable|string|max:20',
            'permanent_address' => 'nullable|string|max:500',
            'current_address' => 'nullable|string|max:500',
            'occupation' => 'nullable|string|max:255',
            'diagnosis' => 'nullable|string|max:255',
            'comment' => 'nullable|string',
            'images' => 'nullable|array',
            'treatment' => 'nullable|string',
            'doctor_name' => 'sometimes|required|string|max:255',
            'marital_status' => 'nullable|in:single,married,divorced,widowed',
        ];
    }
}
