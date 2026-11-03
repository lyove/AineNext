<?php

declare(strict_types=1);

namespace AineNext\Installer\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class RedirectIfNotInstalled
{
    /**
     * Until the application is installed, send every non-installer request
     * to the wizard. API requests are rejected with 404 instead.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Keep the existing test-suite behaviour untouched.
        if (app()->runningUnitTests()) {
            return $next($request);
        }

        $installed = file_exists(storage_path('app/installed'));

        if ($installed && $request->is('install*')) {
            $path = $request->path();

            if ($path !== 'install/complete') {
                abort(404);
            }
        }

        if ($installed) {
            return $next($request);
        }

        // The wizard itself and the framework health check stay reachable.
        if ($request->is('install*') || $request->is('up')) {
            return $next($request);
        }

        if ($request->expectsJson()) {
            abort(404);
        }

        return redirect()->guest('/install');
    }
}
