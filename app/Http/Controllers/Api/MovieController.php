<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use App\Http\Resources\MovieResource;
use App\Models\Movie;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Storage;

class MovieController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $peliculas = $request->user()->movies()
            ->when($request->filled('genero'), fn ($q) => $q->where('genero', $request->string('genero')))
            ->when($request->filled('vista'), fn ($q) => $q->where('vista', $request->boolean('vista')))
            ->when($request->filled('buscar'), function ($q) use ($request) {
                $term = '%' . $request->string('buscar') . '%';
                $q->where(function ($w) use ($term) {
                    $w->where('titulo', 'like', $term)
                        ->orWhere('director', 'like', $term)
                        ->orWhere('actores', 'like', $term);
                });
            })
            ->latest()
            ->paginate(15);

        return MovieResource::collection($peliculas);
    }

    public function store(StoreMovieRequest $request): MovieResource
    {
        $data = collect($request->validated())->except(['caratula'])->all();

        if (array_key_exists('actores', $data)) {
            $data['actores'] = $this->limpiarActores($data['actores']);
        }

        if ($request->hasFile('caratula')) {
            $data['ruta_caratula'] = $request->file('caratula')->store('caratulas', 'public');
        }

        $pelicula = $request->user()->movies()->create($data);

        return MovieResource::make($pelicula);
    }

    private function limpiarActores(?array $actores): array
    {
        if (! $actores) {
            return [];
        }

        return collect($actores)
            ->map(fn ($a) => trim((string) $a))
            ->filter(fn ($a) => $a !== '')
            ->values()
            ->all();
    }

    public function show(Request $request, Movie $movie): MovieResource
    {
        abort_unless($request->user()->is($movie->user), 403);

        return MovieResource::make($movie);
    }

    public function update(UpdateMovieRequest $request, Movie $movie): MovieResource
    {
        abort_unless($request->user()->is($movie->user), 403);

        $data = collect($request->validated())->except(['caratula', 'quitar_caratula'])->all();

        if (array_key_exists('actores', $data)) {
            $data['actores'] = $this->limpiarActores($data['actores']);
        }

        if ($request->boolean('quitar_caratula') && $movie->ruta_caratula) {
            Storage::disk('public')->delete($movie->ruta_caratula);
            $data['ruta_caratula'] = null;
        }

        if ($request->hasFile('caratula')) {
            if ($movie->ruta_caratula) {
                Storage::disk('public')->delete($movie->ruta_caratula);
            }
            $data['ruta_caratula'] = $request->file('caratula')->store('caratulas', 'public');
        }

        $movie->update($data);

        return MovieResource::make($movie);
    }

    public function destroy(Request $request, Movie $movie): JsonResponse
    {
        abort_unless($request->user()->is($movie->user), 403);

        if ($movie->ruta_caratula) {
            Storage::disk('public')->delete($movie->ruta_caratula);
        }

        $movie->delete();

        return response()->json(['mensaje' => 'Película eliminada.']);
    }
}
