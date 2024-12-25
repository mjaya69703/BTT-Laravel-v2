<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;

class checkRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $userRole): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->role === $userRole) {
                return $next($request);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'This area not for you.'
            ], 403); 
        }
    }
}
