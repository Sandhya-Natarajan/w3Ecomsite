<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('products', 'name')->ignore($this->product->id),
            ],
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
            'name.unique' => 'The Name has already been taken',
        ];
    }
}
