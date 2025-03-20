<?php

namespace App\Http\Controllers\Api;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\HttpResponses;
use App\Models\User;
use App\Models\Role;

class UserController extends Controller
{
    use AuthorizesRequests, HttpResponses;

    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return $this->success(UserResource::collection($users));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $request->validated($request->all());

        $role = Role::where('name', 'admin')->first();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $role->id,
        ]);
        $user->markEmailAsVerified();
        Password::sendResetLink([
            'email' => $user->email,
        ]);

        return $this->success([
            'user' => new UserResource($user)
        ], 'user created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return $this->success(['user' => new UserResource($user)]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $request->validated($request->all());
        $user->update($request->all());
        return $this->success(['user' => new UserResource($user)], 'user updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return $this->success(null, 'user updated successfully');
    }
}
