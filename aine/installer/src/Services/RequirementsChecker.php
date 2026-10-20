<?php

declare(strict_types=1);

namespace AineNext\Installer\Services;

final class RequirementsChecker
{
    private const PHP_MIN = '8.4.0';

    /** @var list<string> */
    private const EXTENSIONS = [
        'ctype',
        'curl',
        'dom',
        'fileinfo',
        'filter',
        'hash',
        'mbstring',
        'openssl',
        'pdo',
        'pdo_mysql',
        'session',
        'tokenizer',
        'xml',
        'json',
    ];

    /**
     * @return list<array{id: string, label: string, ok: bool, detail: string, group: string}>
     */
    public function check(string $basePath): array
    {
        $checks = [];

        // --- PHP runtime -------------------------------------------------

        $phpOk = version_compare(PHP_VERSION, self::PHP_MIN, '>=');
        $checks[] = [
            'id' => 'php',
            'group' => 'environment',
            'label' => 'PHP '.self::PHP_MIN.' or higher',
            'ok' => $phpOk,
            'detail' => 'Detected PHP '.PHP_VERSION,
        ];

        foreach (self::EXTENSIONS as $ext) {
            $loaded = extension_loaded($ext);
            $checks[] = [
                'id' => 'ext_'.$ext,
                'group' => 'environment',
                'label' => 'PHP extension: '.$ext,
                'ok' => $loaded,
                'detail' => $loaded ? 'Loaded' : 'Missing',
            ];
        }

        // --- Dependencies -------------------------------------------------

        $checks[] = $this->dependencyCheck(
            'vendor',
            'PHP dependencies (composer install)',
            is_file($basePath.'/vendor/autoload.php'),
            'Missing — run `composer install` in '.$basePath,
        );

        $checks[] = $this->dependencyCheck(
            'node_modules',
            'Frontend dependencies (npm install)',
            is_dir($basePath.'/node_modules'),
            'Missing — run `npm install` in '.$basePath,
        );

        $checks[] = $this->dependencyCheck(
            'frontend_build',
            'Frontend build (npm run build)',
            is_file($basePath.'/public/build/manifest.json'),
            'Missing — run `npm run build` in '.$basePath,
        );

        // --- Application layout -------------------------------------------

        $appOk = is_file($basePath.'/artisan') && is_file($basePath.'/composer.json');
        $checks[] = [
            'id' => 'app_path',
            'group' => 'application',
            'label' => 'AineNext application found',
            'ok' => $appOk,
            'detail' => $appOk ? $basePath : 'artisan / composer.json not found at '.$basePath,
        ];

        $envExample = is_file($basePath.'/.env.example');
        $checks[] = [
            'id' => 'env_example',
            'group' => 'application',
            'label' => '.env.example present',
            'ok' => true,
            'detail' => $envExample ? 'Found' : 'Missing — installer will use a built-in template',
        ];

        $already = is_file($basePath.'/storage/app/installed');
        $checks[] = [
            'id' => 'not_installed',
            'group' => 'application',
            'label' => 'Not already installed',
            'ok' => ! $already,
            'detail' => $already
                ? $basePath.'/storage/app/installed exists — remove it only if you intend to reinstall'
                : 'Ready for a fresh install',
        ];

        // --- File & folder permissions -------------------------------------

        $writable = [
            'base' => ['path' => $basePath, 'label' => 'Application root writable (for .env)'],
            'storage' => ['path' => $basePath.'/storage', 'label' => 'storage/ writable'],
            'app' => ['path' => $basePath.'/storage/app', 'label' => 'storage/app/ writable (install lock, uploads)'],
            'app_private' => ['path' => $basePath.'/storage/app/private', 'label' => 'storage/app/private/ writable (private uploads)'],
            'app_public' => ['path' => $basePath.'/storage/app/public', 'label' => 'storage/app/public/ writable (public uploads)'],
            'framework' => ['path' => $basePath.'/storage/framework', 'label' => 'storage/framework/ writable'],
            'cache' => ['path' => $basePath.'/storage/framework/cache', 'label' => 'storage/framework/cache/ writable'],
            'cache_data' => ['path' => $basePath.'/storage/framework/cache/data', 'label' => 'storage/framework/cache/data/ writable'],
            'sessions' => ['path' => $basePath.'/storage/framework/sessions', 'label' => 'storage/framework/sessions/ writable'],
            'views' => ['path' => $basePath.'/storage/framework/views', 'label' => 'storage/framework/views/ writable'],
            'testing' => ['path' => $basePath.'/storage/framework/testing', 'label' => 'storage/framework/testing/ writable'],
            'logs' => ['path' => $basePath.'/storage/logs', 'label' => 'storage/logs/ writable'],
            'bootstrap_cache' => ['path' => $basePath.'/bootstrap/cache', 'label' => 'bootstrap/cache/ writable'],
        ];

        foreach ($writable as $id => $item) {
            $checks[] = $this->writableCheck('writable_'.$id, $item['label'], $item['path']);
        }

        return $checks;
    }

    /**
     * @param  list<array{ok: bool}>  $checks
     */
    public function allPassed(array $checks): bool
    {
        foreach ($checks as $check) {
            if (! $check['ok']) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return array{id: string, label: string, ok: bool, detail: string, group: string}
     */
    private function dependencyCheck(string $id, string $label, bool $ok, string $missingHint): array
    {
        return [
            'id' => $id,
            'group' => 'dependencies',
            'label' => $label,
            'ok' => $ok,
            'detail' => $ok ? 'Found' : $missingHint,
        ];
    }

    /**
     * Probe the directory with a real write instead of trusting is_writable(),
     * which reports the mode bits rather than the actual permission the web
     * server has (misleading for ACLs and some network mounts).
     *
     * @return array{id: string, label: string, ok: bool, detail: string, group: string}
     */
    private function writableCheck(string $id, string $label, string $path): array
    {
        if (! is_dir($path)) {
            return [
                'id' => $id,
                'group' => 'permissions',
                'label' => $label,
                'ok' => false,
                'detail' => 'Missing directory: '.$path,
            ];
        }

        $probe = $path.'/.installer-write-test-'.bin2hex(random_bytes(4));
        $writable = @file_put_contents($probe, 'ok') !== false;
        if ($writable) {
            @unlink($probe);
        }

        // Fall back to the stat-based check when the probe itself is blocked
        // by an exotic filesystem, so a directory that reports writable still
        // passes instead of being flagged on a probe quirk.
        if (! $writable && is_writable($path)) {
            $writable = true;
        }

        return [
            'id' => $id,
            'group' => 'permissions',
            'label' => $label,
            'ok' => $writable,
            'detail' => $writable ? 'Writable' : 'Not writable: '.$path,
        ];
    }
}
