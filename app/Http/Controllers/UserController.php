<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetRequest;
use App\Http\Requests\UpdateUserImageRequest;
use App\Http\Requests\UserCreateRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function __construct(protected UserService $userService){}

    public function create(UserCreateRequest $request)
    {
        $user = $this->userService->create($request->validated());

        return response()->json(
            [
            'message' => 'пользователь успешно создан!',
            'user' => $user,
            ], 
            Response::HTTP_OK
        );
    }

    public function updateImage(UpdateUserImageRequest $request)
	{
        $path = $this->userService->updateImage($request->validated());

        return response()->json(
            [
            'path'	=> $path,
            'message' => 'пользователь успешно обновил аватар!',
            ],
            Response::HTTP_OK
        );
	}

    public function index(GetRequest $request)
    {
        return response()->json(
            [
                'data' => $this->userService->getUsers($request->validated())
            ],
            Response::HTTP_OK
        );
    }
}
