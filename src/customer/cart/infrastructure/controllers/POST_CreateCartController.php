<?php

namespace Src\customer\cart\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Src\customer\cart\application\useCases\CreateCartUseCase;
use Src\customer\cart\infrastructure\validators\CreateCartRequest;

final class POST_CreateCartController extends Controller
{
    public function __construct(
        private CreateCartUseCase $createCartUseCase,
    ) {}

    public function store(CreateCartRequest $request): JsonResponse
    {
        try {
            $result = $this->createCartUseCase->execute(
                customerId: $request->input('customer_id') ? (int) $request->input('customer_id') : null,
                sessionId: $request->input('session_id'),
                expiresAt: $request->input('expires_at')
                    ? new \DateTimeImmutable($request->input('expires_at'))
                    : null,
            );

            return response()->json([
                'success' => true,
                'data'    => $result,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => $e->getMessage(),
            ], 400);
        }
    }
}
