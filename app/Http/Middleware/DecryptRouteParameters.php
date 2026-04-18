<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DecryptRouteParameters
{
    public function handle(Request $request, Closure $next): Response
    {
        $route = $request->route();

        if ($route) {
            foreach ($route->parameters() as $key => $value) {
                if (! is_scalar($value)) {
                    continue;
                }

                $route->setParameter($key, decrypt_url_value((string) $value));
            }
        }

        return $next($request);
    }
}
