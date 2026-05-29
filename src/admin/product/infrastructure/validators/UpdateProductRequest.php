<?php

namespace Src\admin\product\infrastructure\validators;

use App\Rules\NoSpecialCharacters;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'artist'          => ['sometimes', 'string', 'max:255', new NoSpecialCharacters],
            'album_title'     => ['sometimes', 'string', 'max:255', new NoSpecialCharacters],
            'genre'           => ['sometimes', 'required', 'string', 'max:100', new NoSpecialCharacters],
            'release_year'    => 'sometimes|required|integer|min:1900|max:2100',
            'country'         => ['sometimes', 'required', 'string', 'max:100', new NoSpecialCharacters],
            'label'           => ['sometimes', 'required', 'string', 'max:255', new NoSpecialCharacters],
            'price'           => 'sometimes|numeric|min:0',
            'stock'           => 'sometimes|integer|min:0|max:1000',
            'description'     => ['sometimes', 'nullable', 'string', new NoSpecialCharacters],
            'cover_image_url' => 'sometimes|nullable|url|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'artist.max'           => 'El artista no puede superar los 255 caracteres.',
            'album_title.max'      => 'El título no puede superar los 255 caracteres.',
            'price.numeric'        => 'El precio debe ser un número.',
            'price.min'            => 'El precio no puede ser negativo.',
            'stock.integer'        => 'El stock debe ser un número entero.',
            'stock.min'            => 'El stock no puede ser inferior a 0.',
            'stock.max'            => 'El stock no puede ser superior a 1000.',
            'genre.required'       => 'El género es obligatorio.',
            'genre.max'            => 'El género no puede superar los 100 caracteres.',
            'release_year.required'=> 'El año de lanzamiento es obligatorio.',
            'release_year.integer' => 'El año de lanzamiento debe ser un número entero.',
            'release_year.min'     => 'El año de lanzamiento no puede ser anterior a 1900.',
            'release_year.max'     => 'El año de lanzamiento no puede ser posterior a 2100.',
            'country.required'     => 'El país es obligatorio.',
            'country.max'          => 'El país no puede superar los 100 caracteres.',
            'label.required'       => 'El sello discográfico es obligatorio.',
            'label.max'            => 'El sello discográfico no puede superar los 255 caracteres.',
            'cover_image_url.url'  => 'La URL de la portada no tiene un formato válido.',
            'cover_image_url.max'  => 'La URL de la portada no puede superar los 2048 caracteres.',
        ];
    }
}
