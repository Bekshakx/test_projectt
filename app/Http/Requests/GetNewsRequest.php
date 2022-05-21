<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetNewsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'header' => 'nullable|string',
            'preview' => 'nullable|string',
            'author_name' => 'nullable|string',
            'rubric_title' => 'nullable|string',
            'per_page' => 'nullable|integer'
        ];
    }
}
