<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(AuthRequest $request)
    {
        $user = User::create($request->validated());

        Auth::login($user);

        return response()->json($user, 201);
    }

    public function login(AuthRequest $request)
    {
        if (! Auth::attempt($request->only(['email', 'password']))) {
            return response()->json(['message' => 'Invalid email or password.'], 401);
        }

        $request->session()->regenerate();

        if ($request->has('timezone')) {
            Auth::user()->update(['timezone' => $request->timezone]);
        }

        return response()->json(Auth::user());
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logged out.']);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
