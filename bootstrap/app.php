<?php

use App\Http\Middleware\DecryptRouteParameters;
use App\Http\Middleware\EnsureAdmin;
use App\Http\Middleware\EnsurePermission;
use App\Http\Middleware\EnsureUserIsActive;
use App\Http\Middleware\ImpersonationMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\PostTooLargeException;

require_once __DIR__.'/../app/Support/url.php';

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin'            => EnsureAdmin::class,
            'decrypt.route'    => DecryptRouteParameters::class,
            'active'           => EnsureUserIsActive::class,
            'permission'       => EnsurePermission::class,
            'impersonation.log' => ImpersonationMiddleware::class,
        ]);

        // Run impersonation activity logging on every web request.
        $middleware->web(append: [
            ImpersonationMiddleware::class,
        ]);

        $middleware->redirectUsersTo(function (\Illuminate\Http\Request $request) {
            $user = $request->user();
            if ($user && in_array($user->user_type, ['admin', 'delegated_admin', 'super_admin'])) {
                return route('admin.dashboard');
            }
            return route('home');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (PostTooLargeException $exception, $request) {
            // Show a simple message when PHP rejects a large upload before controller validation runs.
            return back()->withErrors([
                'images' => 'Uploaded files are too large. Increase PHP upload_max_filesize and post_max_size, or upload smaller files.',
            ]);
        });
    })->create();
