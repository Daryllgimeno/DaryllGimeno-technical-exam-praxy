<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
//use Illuminate\Support\Facades\Auth;

class ProductRequest extends FormRequest
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
    $rules = [
        'name' => 'required|string|max:255',
        'category' => 'required|string|max:255',
        'description' => 'required|string',
        'date_time' => 'nullable|date',
        'images.*' => 'image|mimes:jpg,jpeg,png|max:2048',
        'removed_images.*' => 'string',
    ];

    if ($this->isMethod('post')) {
      
        $rules['images'] = 'required|array|min:1';
    } else {
      
        $rules['images'] = 'sometimes|array';
        $rules['saved_images'] = 'sometimes|array|min:1'; 
    }

    return $rules;
}

      public function messages()
    {
        return [
            'name.required' => 'Product name is required.',
            'category.required' => 'Product category is required.',
            'images.*.required' => 'Please upload at least one image.',
            'images.*.image' => 'Only image files are allowed.',
            'images.*.mimes' => 'Allowed formats: jpeg, png, jpg, gif, webp.',
            'images.*.max' => 'Each image must be under 5MB.',
        ];
    }
}
