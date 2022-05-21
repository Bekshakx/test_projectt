<?php
namespace App\Services;

use App\Exceptions\AuthErrorException;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    protected User $userModel;

    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }
    public function login($attributes)
    {
        $user = $this->userModel
            ->query()
            ->where('email', $attributes['email'])
            ->first();

        if(! $user)
        {
            throw new AuthErrorException('Не правильный логин');
        }

        if(!$user->checkPassword($attributes['password']))
        {
            throw new AuthErrorException('Не правильный пароль');
        }

        $response = [
            'token' => $user->createToken($attributes['email'])->plainTextToken,
        ];

        return $response;
    }

    public function logout()
    {
        if(Auth::check()) {
            $user = $this->userModel->find(Auth::id());
            $user->token()->delete();
        } else abort(401);
    }
}