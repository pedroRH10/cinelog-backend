<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('name', 'nombre');
            $table->renameColumn('theme_mode', 'modo_tema');
        });

        Schema::table('movies', function (Blueprint $table) {
            $table->renameColumn('title', 'titulo');
            $table->renameColumn('actors', 'actores');
            $table->renameColumn('genre', 'genero');
            $table->renameColumn('year', 'anio');
            $table->renameColumn('rating', 'puntuacion');
            $table->renameColumn('watched', 'vista');
            $table->renameColumn('watched_at', 'vista_el');
            $table->renameColumn('notes', 'notas');
            $table->renameColumn('cover_path', 'ruta_caratula');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('nombre', 'name');
            $table->renameColumn('modo_tema', 'theme_mode');
        });

        Schema::table('movies', function (Blueprint $table) {
            $table->renameColumn('titulo', 'title');
            $table->renameColumn('actores', 'actors');
            $table->renameColumn('genero', 'genre');
            $table->renameColumn('anio', 'year');
            $table->renameColumn('puntuacion', 'rating');
            $table->renameColumn('vista', 'watched');
            $table->renameColumn('vista_el', 'watched_at');
            $table->renameColumn('notas', 'notes');
            $table->renameColumn('ruta_caratula', 'cover_path');
        });
    }
};
