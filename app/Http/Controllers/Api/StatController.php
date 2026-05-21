<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StatController extends Controller
{
    public function me(Request $request): JsonResponse
    {
        $peliculas = $request->user()->movies();

        return response()->json([
            'total' => (clone $peliculas)->count(),
            'vistas' => (clone $peliculas)->where('vista', true)->count(),
            'pendientes' => (clone $peliculas)->where('vista', false)->count(),
            'puntuacion_media' => round((float) (clone $peliculas)->whereNotNull('puntuacion')->avg('puntuacion'), 2),
            'por_genero' => (clone $peliculas)
                ->selectRaw('genero, COUNT(*) as total')
                ->whereNotNull('genero')
                ->groupBy('genero')
                ->orderByDesc('total')
                ->get(),
            'mejor_puntuadas' => (clone $peliculas)
                ->whereNotNull('puntuacion')
                ->orderByDesc('puntuacion')
                ->limit(5)
                ->get(['id', 'titulo', 'puntuacion']),
        ]);
    }

    public function global(): JsonResponse
    {
        return response()->json([
            'total_peliculas' => Movie::count(),
            'total_vistas' => Movie::where('vista', true)->count(),
            'generos_mas_populares' => Movie::query()
                ->selectRaw('genero, COUNT(*) as total')
                ->whereNotNull('genero')
                ->groupBy('genero')
                ->orderByDesc('total')
                ->limit(5)
                ->get(),
        ]);
    }

    public function statistics(Request $request): JsonResponse
    {
        $peliculas = $request->user()->movies();

        $generosPrincipales = (clone $peliculas)
            ->selectRaw('genero, COUNT(*) as total')
            ->whereNotNull('genero')
            ->groupBy('genero')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $aniosDisponibles = (clone $peliculas)
            ->where('vista', true)
            ->whereNotNull('vista_el')
            ->get(['vista_el'])
            ->map(fn ($m) => (int) $m->vista_el->format('Y'))
            ->unique()
            ->sort()
            ->values()
            ->all();

        if ($request->has('anio')) {
            $anio = (int) $request->input('anio') ?: now()->year;
        } else {
            $anio = ! empty($aniosDisponibles) ? max($aniosDisponibles) : now()->year;
        }

        $vistasEsteAnio = (clone $peliculas)
            ->where('vista', true)
            ->whereNotNull('vista_el')
            ->whereYear('vista_el', $anio)
            ->orderBy('vista_el')
            ->get(['id', 'titulo', 'vista_el', 'genero', 'ruta_caratula']);

        $porMes = $vistasEsteAnio->groupBy(fn ($m) => (int) $m->vista_el->format('n'));

        $vistasPorMes = collect(range(1, 12))
            ->map(fn ($m) => ['mes' => $m, 'total' => $porMes->get($m, collect())->count()])
            ->values();

        $peliculasVistas = $vistasEsteAnio->map(fn ($m) => [
            'id' => $m->id,
            'titulo' => $m->titulo,
            'vista_el' => $m->vista_el,
            'mes' => (int) $m->vista_el->format('n'),
            'genero' => $m->genero,
            'url_caratula' => $m->ruta_caratula ? asset('storage/' . $m->ruta_caratula) : null,
        ]);

        return response()->json([
            'total_peliculas' => (clone $peliculas)->count(),
            'puntuacion_media' => round((float) (clone $peliculas)->whereNotNull('puntuacion')->avg('puntuacion'), 2),
            'generos_principales' => $generosPrincipales,
            'anios_disponibles' => $aniosDisponibles,
            'anio_seleccionado' => $anio,
            'vistas_por_mes' => $vistasPorMes,
            'peliculas_vistas' => $peliculasVistas,
        ]);
    }
}
