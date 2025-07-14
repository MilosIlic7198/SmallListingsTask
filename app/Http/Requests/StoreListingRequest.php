<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreListingRequest extends FormRequest
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
            'title' => 'required|string|max:250',
            'description' => 'required|string|max:500',
            'price' => 'required|numeric|min:0|max:99999999.99|regex:/^\d{1,8}(\.\d{1,2})?$/',
            'condition' => 'required|in:new,used',
            'image' => 'nullable|image|max:2048',
            'phone' => 'required|string|regex:/^(\+\d{1,3}[- ]?)?\d{10}$/',
            'location' => 'required|string|max:100',
            'category_id' => 'required|exists:categories,id',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The title is required.',
            'title.string' => 'The title must be a valid string.',
            'title.max' => 'The title may not be greater than 255 characters.',

            'description.required' => 'The description is required.',
            'description.string' => 'The description must be a valid string.',

            'price.required' => 'The price is required.',
            'price.numeric' => 'The price must be a valid number.',
            'price.min' => 'The price must be at least 0.',
            'price.max' => 'The price may not exceed 99999999.99.',
            'price.regex' => 'The price must have up to 8 digits and 2 decimal places.',

            'condition.required' => 'The condition is required.',
            'condition.in' => 'The condition must be either "new" or "used".',

            'image.image' => 'The uploaded file must be an image.',
            'image.max' => 'The image may not be larger than 2MB.',

            'phone.required' => 'The phone number is required.',
            'phone.string' => 'The phone number must be a valid string.',
            'phone.regex' => 'The phone number must be a valid 10-digit number, optionally starting with a country code (e.g., +1).',

            'location.required' => 'The location is required.',
            'location.string' => 'The location must be a valid string.',
            'location.max' => 'The location may not be greater than 255 characters.',

            'category_id.required' => 'The category is required.',
            'category_id.exists' => 'The selected category is invalid.',
        ];
    }
}
