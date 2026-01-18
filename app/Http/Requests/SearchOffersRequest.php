<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SearchOffersRequest extends FormRequest
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
            'metal' => ['nullable', Rule::in(['gold', 'silver', 'platinum'])],
            'weight' => ['nullable', 'numeric', 'min:0.01', 'required_with:gtlt,units'],
            'gtlt' => ['nullable', Rule::in(['gte', 'lte']), 'required_with:weight,units'],
            'units' => ['nullable', Rule::in(['oz', 'gram']), 'required_with:weight,gtlt'],
            'orderby' => ['nullable', Rule::in(['id', 'metal', 'weight', 'description', 'created_at', 'updated_at'])],
            'direction' => ['nullable', Rule::in(['asc', 'desc'])],
            'keywords' => ['nullable', 'string', 'max:255'],
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
            'weight.required_with' => 'Weight is required when comparison or units are specified.',
            'gtlt.required_with' => 'Comparison (greater/less than) is required when weight or units are specified.',
            'units.required_with' => 'Units are required when weight or comparison are specified.',
        ];
    }
}
