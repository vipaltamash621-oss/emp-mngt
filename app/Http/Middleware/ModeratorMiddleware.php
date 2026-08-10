<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ModeratorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next) {
        // Check if the user is authenticated
        if (!auth()->check()) {
            return redirect('/login');
        }
        
        // Check if user has role and verify permissions
        $user = auth()->user();
        if (!$user->role || $user->role->slug !== 'moderator') {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
