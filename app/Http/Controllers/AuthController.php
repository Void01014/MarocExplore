<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use OpenApi\Attributes as OA;

class AuthController extends Controller
{
    #[OA\Post(path: '/api/auth/register', summary: 'Register a new user', tags: ['Auth'])]
    #[OA\RequestBody(
        required: true,
        content: new OA\MediaType(
            mediaType: 'multipart/form-data',
            schema: new OA\Schema(ref: '#/components/schemas/User')
        )
    )]
    #[OA\Response(
        response: 201,
        description: 'User registered',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'user', ref: '#/components/schemas/User'),
                new OA\Property(property: 'access_token', type: 'string'),
                new OA\Property(property: 'token_type', type: 'string'),
                new OA\Property(property: 'expires_in', type: 'integer')
            ]
        )
    )]
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password'])
        ]);

        $token = auth()->login($user);
        return $this->respondWithToken($token);
    }

    #[OA\Post(path: '/api/auth/login', summary: 'Login a user', tags: ['Auth'])]
    #[OA\RequestBody(
        required: true,
        content: new OA\MediaType(
            mediaType: 'multipart/form-data',
            schema: new OA\Schema(
                required: ['email', 'password'],
                properties: [
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'user@example.com'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', example: 'secret123')
                ]
            )
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Login successful',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'user', ref: '#/components/schemas/User'),
                new OA\Property(property: 'access_token', type: 'string'),
                new OA\Property(property: 'token_type', type: 'string'),
                new OA\Property(property: 'expires_in', type: 'integer')
            ]
        )
    )]
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    #[OA\Get(path: '/api/auth/me', summary: 'Get authenticated user', security: [['bearerAuth' => []]], tags: ['Auth'])]
    #[OA\Response(
        response: 200,
        description: 'User data',
        content: new OA\JsonContent(ref: '#/components/schemas/User')
    )]
    public function me()
    {
        return response()->json(auth()->user());
    }

    #[OA\Post(path: '/api/auth/logout', summary: 'Logout', security: [['bearerAuth' => []]], tags: ['Auth'])]
    #[OA\Response(
        response: 200,
        description: 'Logged out successfully',
        content: new OA\JsonContent(
            properties: [new OA\Property(property: 'message', type: 'string', example: 'Successfully logged out')]
        )
    )]
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'user' => auth()->user(), // Include the user details here
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
        ]);
    }
}
