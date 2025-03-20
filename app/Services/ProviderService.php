<?php

namespace App\Services;

use App\Models\Provider;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ProviderService
{
    public function createProvider(array $data): Provider
    {
        $role = Role::where('name', 'provider')->first()->id;
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => $role
        ]);

        return Provider::create([
            'user_id' => $user->id,
            'address' => $data['address'],
            'phone' => $data['phone'],
            'profile_picture' => $data['profile_picture']
        ]);
        return $provider;
    }
}
