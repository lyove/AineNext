<?php

declare(strict_types=1);

namespace AineNext\Installer\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class CanInstall
{
    /**
     * Block the installer wizard once the application has been installed.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (file_exists(storage_path('app/installed'))) {
            $routeName = $request->route()?->getName();

            if ($routeName !== 'install.complete') {
                abort(404);
            }
        }

        return $next($request);
    }
}
