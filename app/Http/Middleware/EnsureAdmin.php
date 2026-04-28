<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    /**
     * Ensure the authenticated user has an admin-level user_type.
     * Allowed types: admin, delegated_admin, super_admin.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        $adminTypes = ['admin', 'delegated_admin', 'super_admin'];

        if (! in_array($user->user_type, $adminTypes, true)) {
            abort(403, 'You do not have administrative access.');
        }

        return $next($request);
    }
}
