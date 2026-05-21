<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'titulo' => $this->titulo,
            'director' => $this->director,
            'actores' => $this->actores ?? [],
            'genero' => $this->genero,
            'anio' => $this->anio,
            'puntuacion' => $this->puntuacion,
            'vista' => $this->vista,
            'vista_el' => $this->vista_el,
            'notas' => $this->notas,
            'url_caratula' => $this->ruta_caratula ? asset('storage/' . $this->ruta_caratula) : null,
            'usuario' => UserResource::make($this->whenLoaded('user')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
