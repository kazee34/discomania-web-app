<?php

namespace Src\admin\product\infrastructure\validators;

use Illuminate\Foundation\Http\FormRequest;

class CreateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'artist' => 'required|string|max:255',
            'album_title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:280|unique:products,slug',
            'genre' => 'nullable|string|max:100',
            'release_year' => 'nullable|integer|min:1900|max:2100',
            'country' => 'nullable|string|max:100',
            'label' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0|max:1000',
            'description' => 'nullable|string',
            'cover_image_url' => 'nullable|url|max:2048',
        ];
    }
}
