<?php

namespace App\Http\Middleware;

use App\Models\AdminModel;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! AdminModel::where('user_id', $request->user()->id)->where('is_active', true)->exists()) {
            abort(403);
        }

        return $next($request);
    }
}