<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
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
            'name' => 'nullable|string',
            'sort' => 'nullable|string|in:asc,desc',
            'limit' => 'nullable|integer',
            'page' => 'nullable|integer',
            'filter' => 'nullable|array',
            'filter.*.property' => 'required_with:filter|string',
            'filter.*.operation' => 'required_with:filter|string',
            'filter.*.value' => 'required_with:filter',
        ];
    }

}
