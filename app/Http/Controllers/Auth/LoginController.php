<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Handle an incoming authentication request (Login).
     */
    public function store(LoginRequest $request)
    {
        try {
            // Login validation & Authentification
            $request->authenticate();

            $user = $request->user();

            // Token creation
            $token = $user->createToken('main')->plainTextToken;

            // Successful API-Response
            return response()->success([
                'user'  => new UserResource($user),
                'token' => $token,
            ], 'Login successful', 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation Errors
            return response()->error('Invalid credentials', 422, $e->errors());
        } catch (\Exception $e) {
            // Unexpected Errors
            return response()->error('Login failed', 500, $e->getMessage());
        }
    }

    /**
     * Logout: Destroy an authenticated session / token.
     */
    public function destroy(Request $request)
    {
        try {
            $user = $request->user();

            if ($user?->currentAccessToken()) {
                $user->currentAccessToken()->delete();
            }

            return response()->success(null, 'Logout successful', 204);

        } catch (\Exception $e) {
            return response()->error('Logout failed', 500, $e->getMessage());
        }
    }
}
