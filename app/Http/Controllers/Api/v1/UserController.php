<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * List all users
     */
    public function index(): JsonResponse
    {
        try {
            $users = User::query()->latest()->get();

            return response()->success($users, 'Users fetched successfully');

        } catch (\Exception $e) {
            return response()->error('Failed to fetch users', 500, $e->getMessage());
        }
    }

    /**
     * Show a single user by id or email
     */
    public function show(string $identifier): JsonResponse
    {
        try {
            $user = is_numeric($identifier)
                ? User::find($identifier)
                : User::where('email', $identifier)->first();

            if (! $user) {
                return response()->error('User not found', 404);
            }

            return response()->success($user, 'User fetched successfully');

        } catch (\Exception $e) {
            return response()->error('Failed to fetch user', 500, $e->getMessage());
        }
    }

    /**
     * Update a user
     */
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        try {
            $data = $request->safe()->only(['name', 'email', 'password']);

            $user->update($data);

            return response()->success($user->fresh(), 'User updated successfully');

        } catch (\Exception $e) {
            return response()->error('Failed to update user', 500, $e->getMessage());
        }
    }
}
