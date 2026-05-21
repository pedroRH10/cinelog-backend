<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMovieRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titulo' => ['required', 'string', 'max:255'],
            'director' => ['nullable', 'string', 'max:255'],
            'actores' => ['nullable', 'array', 'max:50'],
            'actores.*' => ['string', 'max:120'],
            'genero' => ['nullable', 'string', 'max:100'],
            'anio' => ['nullable', 'integer', 'min:1888', 'max:2100'],
            'puntuacion' => ['nullable', 'numeric', 'min:0', 'max:10'],
            'vista' => ['sometimes', 'boolean'],
            'vista_el' => ['nullable', 'date'],
            'notas' => ['nullable', 'string'],
            'caratula' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:5120'],
        ];
    }
}
