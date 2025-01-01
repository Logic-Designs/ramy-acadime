<?php

namespace App\Traits;

use App\Models\User;
use App\Services\ImageService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

trait UsersTrait
{
    use PaginationTrait;

    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }
    /**
     * List all users with pagination.
     *
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function listUsers()
    {
        return $this->paginate(User::query());
    }

    /**
     * Create a new user.
     *
     * @param array $data
     * @return User
     */
    public function createUser(array $data)
    {
        if(isset($data['avatar'])){
            $data['avatar'] = $this->imageService->store($data['avatar'], 'users');
        }
        $user =  User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name']?? null,
            'password' => Hash::make($data['password']),
        ]);

        return $user;
    }

    /**
     * Get a user by ID.
     *
     * @param int $id
     * @return User
     * @throws ValidationException
     */
    public function getUserById($id)
    {
        $user = User::find($id);

        if (!$user) {
            throw ValidationException::withMessages(['user' => 'User not found.']);
        }

        return $user;
    }

    /**
     * Update an existing user.
     *
     * @param int $id
     * @param array $data
     * @return User
     * @throws ValidationException
     */
    public function updateUser(User $user, array $data)
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        if(isset($data['avatar'])){
            $data['avatar'] = $this->imageService->store($data['avatar'], 'users');
        }

        $user->update($data);

        return $user;
    }

    /**
     * Delete a user by ID.
     *
     * @param int $id
     * @return void
     * @throws ValidationException
     */
    public function deleteUser(User $user)
    {
        $user->delete();
    }
}
