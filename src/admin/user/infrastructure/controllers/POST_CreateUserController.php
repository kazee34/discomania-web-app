<?php

namespace Src\admin\user\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Src\admin\user\application\useCases\CreateUserUseCase;
use Src\admin\user\infrastructure\validators\CreateUserRequest;

final class POST_CreateUserController extends Controller
{
    public function __construct(
        private CreateUserUseCase $createUserUseCase
    ) {}

    public function store(CreateUserRequest $request): JsonResponse
    {
        try {
            $user = $this->createUserUseCase->execute(
                $request->input('name'),
                $request->input('email'),
                $request->input('password'),
                $request->input('role')
            );

            return response()->json([
                'success' => true,
                'data' => [
                    'name' => $user->username,
                    'email' => $user->email,
                ],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 400);
        }
    }
}
