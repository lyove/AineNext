<?php

declare(strict_types=1);

namespace AineNext\Installer\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final class CompleteController
{
    /**
     * GET /install/complete — show the installation completion page.
     * 
     * This route is accessible even after the lock file is written, so the user
     * can see the final summary. Data is read from a temporary file to avoid
     * session driver switching issues.
     */
    public function show(): View|RedirectResponse
    {
        $completionFile = storage_path('framework/cache/installer_completion.json');
        $done = [];

        if (is_file($completionFile)) {
            $content = file_get_contents($completionFile);
            if ($content !== false) {
                $data = json_decode($content, true);
                if (is_array($data)) {
                    $done = $data;
                }
            }
            // Delete the temporary file after reading
            @unlink($completionFile);
        }

        if ($done === []) {
            // No completion data, redirect to login or welcome
            if (file_exists(storage_path('app/installed'))) {
                $appUrl = config('app.url', request()->getSchemeAndHttpHost());
                return redirect()->away(rtrim($appUrl, '/').'/login');
            }

            return redirect()->route('install.welcome');
        }

        return view('installer::complete', [
            'done' => $done,
            'log' => $done['log'] ?? [],
        ]);
    }
}
