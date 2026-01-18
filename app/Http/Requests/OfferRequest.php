<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OfferRequest extends FormRequest
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
            'metal' => ['required', Rule::in(['gold', 'silver', 'platinum'])],
            'weight' => ['required', 'numeric', 'min:0.01', 'max:99999999.99'],
            'weight_unit' => ['required', Rule::in(['oz', 'gram'])],
            'description' => ['required', 'string', 'max:255'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'metal.required' => 'Please select a metal type.',
            'metal.in' => 'Please select a valid metal type.',
            'weight.required' => 'Please enter a weight.',
            'weight.numeric' => 'Weight must be a number.',
            'weight.min' => 'Weight must be at least 0.01.',
            'weight_unit.required' => 'Please select a weight unit.',
            'weight_unit.in' => 'Please select a valid weight unit.',
            'description.required' => 'Please enter a description.',
            'description.max' => 'Description cannot exceed 255 characters.',
        ];
    }
}
