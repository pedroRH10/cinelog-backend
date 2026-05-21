<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return UserResource::collection(User::query()->latest()->paginate(15));
    }

    public function show(User $user): UserResource
    {
        return UserResource::make($user);
    }

    public function update(Request $request, User $user): UserResource
    {
        abort_unless($request->user()->is($user), 403);

        $data = $request->validate([
            'nombre' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $user->update($data);

        return UserResource::make($user);
    }

    public function destroy(Request $request, User $user): JsonResponse
    {
        abort_unless($request->user()->is($user), 403);

        $user->delete();

        return response()->json(['mensaje' => 'Usuario eliminado.']);
    }
}
