<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */

    protected function redirectTo(Request $request): ?string
    {
        if ($request->expectsJson()) {
            return null;
        } else {
            $response = new JsonResponse(['error' => 'Unauthenticated'], 401);
            $response->header('Content-Type', 'application/json');
            return $response->send();
        }
    }
    // protected function redirectTo($request)
    // {
    //     if (!$request->expectsJson()) {
    //         return response()->json(['error' => 'Unauthenticated'], 401);
    //     }
    // }
    
}
