<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Services\SheetMusicService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function show(Request $request)
    {
        return new UserResource($request->user());
    }

    public function update(UserRequest $request)
    {
        $user = $request->user();
        $data = $request->safe()->except(['current_password', 'new_password', 'new_password_confirmation']);

        if ($request->validated('new_password')) {
            $data['password'] = $request->validated('new_password');
        }

        $user->update($data);

        return new UserResource($user);
    }

    public function destroy(Request $request, SheetMusicService $sheetMusic)
    {
        $user = $request->user();

        foreach ($user->pieces as $piece) {
            $sheetMusic->deleteAll($piece);
        }

        Storage::disk('local')->deleteDirectory("pdfs/{$user->id}");

        $user->sessions()->delete();
        $user->pieces()->delete();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->noContent();
    }
}
