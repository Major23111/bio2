<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ImpersonationMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check if an impersonation session is currently active via session flag.
        $isImpersonating = session()->has('impersonator_id');

        if ($isImpersonating) {
            // Only log state-changing requests — skip plain page views.
            $isImportantAction = in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE']);

            if ($isImportantAction) {
                // Read the active impersonation session id from the session.
                $activeSessionId = session('impersonation_audit_id');

                // Build a short plain-text description from the method and URL path.
                $actionDescription = $request->method() . ' ' . $request->path();

                // Log this action to the impersonation activity log table.
                DB::table('impersonation_activity_logs')->insert([
                    'impersonation_audit_id' => $activeSessionId,
                    'url'                    => $request->fullUrl(),
                    'http_method'            => $request->method(),
                    'action_description'     => $actionDescription,
                    'ip_address'             => $request->ip(),
                    'performed_at'           => now(),
                    'created_at'             => now(),
                    'updated_at'             => now(),
                ]);
            }
        }

        // Continue the request normally.
        $response = $next($request);

        return $response;
    }
}
