<?php

namespace Src\admin\user\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Src\admin\user\application\useCases\FindUserByIdUseCase;
use Src\shared\domain\exceptions\UserNotFoundException;

final class GET_GetUserByIdController extends Controller
{
    public function __construct(
        private FindUserByIdUseCase $findUserByIdUseCase
    ) {}

    public function show(int $id): JsonResponse
    {
        try {
            $user = $this->findUserByIdUseCase->execute($id);

            return response()->json([
                'status' => true,
                'data' => [
                    'id' => $user->id(),
                    'name' => $user->username(),
                    'email' => $user->email()
                ],
                'message' => 'success',
            ]);

        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'status' => false,
                'error' => 'Invalid ID',
            ], 400);
        } catch (UserNotFoundException $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => 'Internal Server Error',
            ], 500);
        }
    }
}