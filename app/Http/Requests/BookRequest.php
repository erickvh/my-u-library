<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
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
            'title' => 'required|string|min:3|max:50',
            'author' => 'required|string|min:3|max:100',
            'description' => 'nullable|string|max:255',
            'published_year' => 'required|digits:4|integer|min:1900|max:' . (date('Y') + 1),
            'stock' => 'nullable|integer',
            'genre_id' => 'required|integer',
        ];
    }
}
