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
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'condition' => 'required|in:new,used',
            'image' => 'nullable|image|max:4096',
            'phone' => 'required|string|max:50',
            'location' => 'required|string|max:255',
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

            'condition.required' => 'The condition is required.',
            'condition.in' => 'The condition must be either "new" or "used".',

            'image.image' => 'The uploaded file must be an image.',
            'image.max' => 'The image may not be larger than 4MB.',

            'phone.required' => 'The phone number is required.',
            'phone.string' => 'The phone number must be a valid string.',
            'phone.max' => 'The phone number may not be greater than 50 characters.',

            'location.required' => 'The location is required.',
            'location.string' => 'The location must be a valid string.',
            'location.max' => 'The location may not be greater than 255 characters.',

            'category_id.required' => 'The category is required.',
            'category_id.exists' => 'The selected category is invalid.',
        ];
    }
}
