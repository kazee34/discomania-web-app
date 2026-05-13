<?php

namespace Src\admin\product\infrastructure\validators;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('product');

        return [
            'artist'          => 'sometimes|string|max:255',
            'album_title'     => 'sometimes|string|max:255',
            'slug'            => "sometimes|string|max:280|unique:products,slug,{$id}",
            'genre'           => 'sometimes|nullable|string|max:100',
            'release_year'    => 'sometimes|nullable|integer|min:1900|max:2100',
            'country'         => 'sometimes|nullable|string|max:100',
            'label'           => 'sometimes|nullable|string|max:255',
            'price'           => 'sometimes|numeric|min:0',
            'stock'           => 'sometimes|integer|min:0|max:1000',
            'description'     => 'sometimes|nullable|string',
            'cover_image_url' => 'sometimes|nullable|url|max:2048',
        ];
    }
}