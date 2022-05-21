<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    public function __construct(protected AuthService $authService){}

    public function login(LoginRequest $request)
    {
        return response()->json(
            [
                'data' => $this->authService->login($request->validated())
            ], 
            Response::HTTP_OK
        );
    }
}
