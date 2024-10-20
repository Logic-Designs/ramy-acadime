<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\UsersTrait;
use Illuminate\Support\Facades\Response;

class UserController extends Controller
{
    use UsersTrait;

    public function index()
    {
        $users = $this->listUsers();
        return Response::success('Users retrieved successfully.', [
            'users' => UserResource::collection($users)
        ]);
    }


    public function store(StoreUserRequest $request)
    {
        $user = $this->createUser($request->validated());
        return Response::success('User created successfully.', new UserResource($user));
    }


    public function show(User $user)
    {
        return Response::success('User retrieved successfully.', new UserResource($user));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $user = $this->updateUser($user, $request->validated());
        return Response::success('User updated successfully.', new UserResource($user));
    }

    public function destroy(User $user)
    {
        $this->deleteUser($user);
        return Response::success('User deleted successfully.');
    }
}
