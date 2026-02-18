<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\UpdateUserRequest;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->get();
        return response()->success($users, 'Users fetched successfully');
    }

    public function show(string $identifier)
    {
        $user = is_numeric($identifier)
            ? User::find($identifier)
            : User::where('email', $identifier)->firstOrFail();

        return response()->success($user, 'User fetched successfully');
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();
        $user->update($data);

 return response()->success($user->fresh(), 'User updated successfully');
    }
}
