<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Adjust based on your authorization logic
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $categoryId = $this->route('category') ? $this->route('category')->id : null;

        $rules = [
            'parent_id' => [
                'nullable',
                'exists:categories,id',
                Rule::notIn([$categoryId]),
            ],
            'child_id' => [
                'nullable',
                'exists:categories,id',
            ],
            'newCategories' => [
                'required_if:action,store', // Required only for store
                'array',
                'min:1',
            ],
            'newCategories.*.name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z\s-]+$/',
                Rule::unique('categories', 'name'),
            ],
        ];

        // Add name validation only for update
        if ($this->route()->getActionMethod() === 'update') {
            $rules['name'] = [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z\s-]+$/',
                Rule::unique('categories', 'name')->ignore($categoryId),
            ];
        }

        return $rules;
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'name.regex' => 'The category name can only contain letters, spaces, and hyphens.',
            'name.unique' => 'This category name already exists.',
            'newCategories.*.name.regex' => 'Each new category name can only contain letters, spaces, and hyphens.',
            'newCategories.*.name.unique' => 'This new category name already exists.',
            'parent_id.not_in' => 'A category cannot be its own parent.',
            'newCategories.required_if' => 'At least one new category is required.',
        ];
    }
}
