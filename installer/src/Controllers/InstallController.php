<?php

declare(strict_types=1);

namespace AineNext\Installer\Controllers;

use AineNext\Installer\Services\EnvironmentManager;
use AineNext\Installer\Services\InstallerService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\View\View;
use Throwable;

final class InstallController
{
    public function __construct(
        private readonly InstallerService $service,
        private readonly EnvironmentManager $environment,
    ) {}

    /**
     * GET /install/run — show the progress page that auto-submits.
     */
    public function show(Request $request): View|RedirectResponse
    {
        $config = (array) session('_installer_config', []);

        if ($config === []) {
            return redirect()->route('install.configure');
        }

        return view('installer::installing', [
            'basePath' => base_path(),
        ]);
    }

    /**
     * POST /install/run — actually run the installation.
     */
    public function run(Request $request): View|RedirectResponse
    {
        $config = (array) session('_installer_config', []);

        if ($config === []) {
            return redirect()->route('install.configure');
        }

        // Validate one-time token
        $installerToken = $request->input('_installer_token');
        $sessionToken = session('_installer_token');
        
        if (!$sessionToken || $installerToken !== $sessionToken) {
            // Token mismatch - redirect without clearing session
            // (allows user to retry if session was temporarily unavailable)
            return redirect()->route('install.configure');
        }

        try {
            $log = $this->service->run($config);

            // Store completion info in a temporary file before finalizing drivers
            $completionData = [
                'app_url' => rtrim($config['app_url'], '/'),
                'admin_email' => $config['admin_email'],
                'cron' => $this->service->cronCommand(),
                'log' => $log,
            ];
            
            $completionFile = storage_path('framework/cache/installer_completion.json');
            file_put_contents($completionFile, json_encode($completionData, JSON_PRETTY_PRINT));

            // Finalize session/queue/cache drivers
            $this->environment->finalizeDrivers();

            // Clear caches after driver finalization
            try {
                Artisan::call('config:clear');
                Artisan::call('route:clear');
                Artisan::call('view:clear');
            } catch (Throwable) {
                // Non-fatal
            }

            // Clear session data
            session()->forget('_installer_config');
            session()->forget('_installer_token');

            // Redirect to completion page
            return redirect()->route('install.complete');
        } catch (Throwable $e) {
            return view('installer::error', [
                'message' => $e->getMessage(),
                'log' => (array) session('_installer_log', []),
            ]);
        }
    }
}
