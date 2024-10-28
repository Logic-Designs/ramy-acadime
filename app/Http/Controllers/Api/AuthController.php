<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    // Register new user
    public function register(RegisterRequest $request)
    {
        $validatedData = $request->validated();
        // return $validatedData;

        $user = $this->userService->store($validatedData);

        return Response::success('User registered successfully.', ['user'=>new UserResource($user)], 201);
    }

    // Login user
    public function login(LoginRequest $request)
    {
        $identifier = $request->input('identifier');

        $user = User::where(function ($query) use ($identifier) {
            $query->where('email', $identifier)
                ->orWhere('name', $identifier)
                ->orWhere('phone', $identifier);
        })->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'identifier' => [__('The provided credentials are incorrect.')],
            ]);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return Response::success('Login successful.', [
            'user' => new UserResource($user),
            'token' => $token,
        ], 200);
    }

    // Logout user
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return Response::success('Logged out successfully');
    }
}
