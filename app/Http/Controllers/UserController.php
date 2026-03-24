<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

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
}
