<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

trait UsersTrait
{
    use PaginationTrait;
    /**
     * List all users with pagination.
     *
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function listUsers()
    {
        return $this->paginat(User::query());
    }

    /**
     * Create a new user.
     *
     * @param array $data
     * @return User
     */
    public function createUser(array $data)
    {
        $user =  User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        if(isset($data['role']))
            $user->syncRoles($data['role']);

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

        // Check if the password is present in the data and hash it
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        if (isset($data['role'])) {
            $user->syncRoles([$data['role']]);
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
