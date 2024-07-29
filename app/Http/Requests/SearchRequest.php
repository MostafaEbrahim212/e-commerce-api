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
            'sort_by' => 'nullable|string|in:name,created_at,updated_at',
            'order' => 'nullable|string|in:asc,desc',
            'filter' => 'nullable|string',
            'value' => 'nullable|string',
            'page' => 'nullable|integer|min:1',
        ];
    }
}
