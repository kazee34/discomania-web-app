<?php

namespace Src\customer\cart\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Src\customer\cart\application\useCases\FindCartUseCase;
use Src\customer\cart\domain\exceptions\CartNotFoundException;

final class GET_FindCartController extends Controller
{
    public function __construct(
        private FindCartUseCase $findCartUseCase,
    ) {}

    public function show(string $token): JsonResponse
    {
        try {
            $result = $this->findCartUseCase->execute($token);

            return response()->json([
                'success' => true,
                'data'    => $result,
            ]);
        } catch (CartNotFoundException $e) {
            return response()->json([
                'success' => false,
                'error'   => $e->getMessage(),
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => 'Internal Server Error',
            ], 500);
        }
    }
}
