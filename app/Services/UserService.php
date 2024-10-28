<?php

namespace App\Services;

use App\Models\User;
use App\Traits\UsersTrait;
use Illuminate\Support\Facades\Auth;

class UserService
{
    use UsersTrait;

    /**
     * Store a new user and create a profile if the role is 'user'.
     */
    public function store($data)
    {
        $user = $this->createUser($data);

        /** @var User $authUser */
        $authUser = Auth::user();

        if (isset($data['role']) && $authUser->hasRole('admin')) {
            $user->syncRoles($data['role']);
        }



        if ($user->hasRole('user')) {
            $profileData = $this->extractProfileData($data);
            $user->profile()->create($profileData);
        }

        return $user->load('profile');
    }

    /**
     * Update an existing user and their profile data if the role is 'user'.
     */
    public function update(User $user, $data)
    {
        $user = $this->updateUser($user, $data);


        if ($user->hasRole('user')) {
            // Filter out profile data only
            $profileData = $this->extractProfileData($data);
            $user->profile()->update($profileData);
        }

        return $user->load('profile');

    }

    /**
     * Extracts profile-related data from the given array.
     */
    private function extractProfileData(array $data): array
    {
        return array_filter($data, function ($key) {
            return in_array($key, ['address', 'bio', 'birthday', 'gender', 'city_id']);
        }, ARRAY_FILTER_USE_KEY);
    }
}
