<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateProductRequest extends FormRequest
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
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:100|unique:products,name',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'stock' => 'required|string',
            'tag_id' => 'required|array',
            'tag_id.*' => 'exists:tags,id'
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Category is required',
            'category_id.exists' => 'Selected Category does not exist',
            'tag_id.*.exists' => 'One or more entered tags do not exist',
            'name' => 'The Name has already been taken',
        ];
    }
}
