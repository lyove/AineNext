<?php

declare(strict_types=1);

namespace AineNext\Installer\Services;

use RuntimeException;

final class InstalledFileManager
{
    /**
     * Whether the application has been installed.
     */
    public static function installed(): bool
    {
        return is_file(storage_path('app/installed'));
    }

    /**
     * Write the install lock file atomically: the payload goes to a temp file
     * in the same directory first, then gets renamed into place, so a crash
     * mid-write can never leave a truncated or corrupt marker behind.
     *
     * @param  string|null  $dir  Target directory, defaults to storage/app.
     */
    public static function write(string $appUrl, ?string $dir = null): void
    {
        $dir ??= storage_path('app');

        if (! is_dir($dir) && ! @mkdir($dir, 0755, true) && ! is_dir($dir)) {
            throw new RuntimeException('Could not create '.$dir);
        }

        $payload = json_encode([
            'installed_at' => gmdate('c'),
            'app_url' => rtrim($appUrl, '/'),
            'installer' => 'ainenext-installer',
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        if ($payload === false) {
            throw new RuntimeException('Could not encode install lock payload.');
        }

        $target = $dir.'/installed';
        $temp = $dir.'/.installed-'.bin2hex(random_bytes(4)).'.tmp';

        if (@file_put_contents($temp, $payload) === false) {
            @unlink($temp);
            throw new RuntimeException('Could not write install lock to '.$temp);
        }

        @chmod($temp, 0644);

        if (! @rename($temp, $target)) {
            @unlink($temp);
            throw new RuntimeException('Could not finalize install lock at '.$target);
        }

        if (! is_file($target)) {
            throw new RuntimeException('Install lock is missing after write: '.$target);
        }
    }
}
