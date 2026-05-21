<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PreferencesController extends Controller
{
    public function update(Request $request): UserResource
    {
        $data = $request->validate([
            'modo_tema' => ['required', Rule::in(['light', 'dark'])],
        ]);

        $user = $request->user();
        $user->update($data);

        return UserResource::make($user);
    }
}
