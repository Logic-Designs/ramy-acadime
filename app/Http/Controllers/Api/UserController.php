<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use App\Traits\PaginationTrait;
use App\Traits\UsersTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class UserController extends Controller
{
    use UsersTrait, PaginationTrait;

    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        $users = $this->listUsers();
        return Response::success('Users retrieved successfully.',
           UserResource::collection($users)
        , 200, $this->getPagination($users));
    }


    public function store(StoreUserRequest $request)
    {
        $user = $this->userService->store($request->validated());

        return Response::success('User created successfully.', new UserResource($user));
    }


    public function show(User $user)
    {
        return Response::success('User retrieved successfully.', new UserResource($user));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $user = $this->userService->update($user ,$request->validated());

        return Response::success('User updated successfully.', new UserResource($user));
    }

    public function destroy(User $user)
    {
        $this->deleteUser($user);
        return Response::success('User deleted successfully.');
    }

    public function storeChildUser(StoreUserRequest $request)
    {
        /** @var User $parent */
        $parent = Auth::user();

        $child = $this->createUser($request->validated());

        $parent->children()->attach($child->id);

        return Response::success('Child account Created successfully.', new UserResource($child));
    }

    public function getChildren()
    {
        /** @var User $parent */
        $parent = Auth::user();
        $children = $this->paginate($parent->children());

        return Response::success('Children retrieved successfully.',
                                UserResource::collection($children), 200,
                                $this->getPagination($children));
    }

    public function detachChildUser(User $child)
    {
        /** @var User $parent */
        $parent = Auth::user();
        $parent->children()->detach($child->id);
        return Response::success('Child account detached successfully.');
    }

}
