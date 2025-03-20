<?php

namespace App\Http\Controllers\Api;

use App\HttpResponses;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreProviderRequest;
use App\Http\Requests\UpdatePasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Services\ProviderService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use App\Http\Resources\ProviderResource;
use App\Http\Resources\UserResource;

class AuthController extends Controller
{
    use HttpResponses;
    public function register(StoreProviderRequest $request, ProviderService $providerService)
    {

        $request->validated($request->all());

        $provider = $providerService->createProvider($request->all());

        $provider->user->sendEmailVerificationNotification();
        return $this->success([
            'token' => $provider->user->createToken($provider->user->name)->plainTextToken,
            'provider' => new ProviderResource($provider)
        ]);
    }

    public function login(LoginUserRequest $request)
    {
        $request->validated($request->all());

        if (!Auth::attempt($request->only(['email', 'password']))) {
            return $this->error('', 'Invalid Credentials', 401);
        }
        $user = User::where('email', $request->email)->first();

        return $this->success([
            'user' => new UserResource($user),
            'token' => $user->createToken('API Token')->plainTextToken
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return $this->success([
            'message' => 'You have succesfully been logged out'
        ]);
    }

    public function verifyEmail(Request $request)
    {
        $user = User::findOrFail($request->route('id'));
        if (! hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            return $this->error('', 'Invalid verification link', 400);
        }

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }
        return $this->success([
            'message' => 'Email verified'
        ]);
    }

    public function resetPassword(UpdatePasswordRequest $request)
    {
        Log::info($request);

        $request->validated($request->all());

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ]);

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status == Password::PASSWORD_RESET
            ? $this->success('', 'password changed successfully')
            : $this->error('', 'Error changing password', 400);
    }
}
