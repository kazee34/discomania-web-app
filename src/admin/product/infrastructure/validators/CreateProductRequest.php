<?php

namespace Src\admin\product\infrastructure\validators;

use App\Rules\NoSpecialCharacters;
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
            'artist'          => ['required', 'string', 'max:255', new NoSpecialCharacters],
            'album_title'     => ['required', 'string', 'max:255', new NoSpecialCharacters],
            'slug'            => 'nullable|string|max:280|unique:products,slug',
            'genre'           => ['nullable', 'string', 'max:100', new NoSpecialCharacters],
            'release_year'    => 'nullable|integer|min:1900|max:2100',
            'country'         => ['nullable', 'string', 'max:100', new NoSpecialCharacters],
            'label'           => ['nullable', 'string', 'max:255', new NoSpecialCharacters],
            'price'           => 'required|numeric|min:0',
            'stock'           => 'required|integer|min:0|max:1000',
            'description'     => ['nullable', 'string', new NoSpecialCharacters],
            'cover_image_url' => 'nullable|url|max:2048',
        ];
    }
}
