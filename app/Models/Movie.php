<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'titulo',
        'director',
        'actores',
        'genero',
        'anio',
        'puntuacion',
        'vista',
        'vista_el',
        'notas',
        'ruta_caratula',
    ];

    protected function casts(): array
    {
        return [
            'vista' => 'boolean',
            'vista_el' => 'datetime',
            'anio' => 'integer',
            'puntuacion' => 'float',
            'actores' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
