<?php

use AineNext\Installer\Services\RequirementsChecker;

beforeEach(function () {
    $this->tmp = sys_get_temp_dir().'/ainenext-installer-test-'.bin2hex(random_bytes(6));
    $this->checker = new RequirementsChecker;
});

afterEach(function () {
    if (! is_dir($this->tmp)) {
        return;
    }

    $it = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($this->tmp, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );

    foreach ($it as $file) {
        if ($file->isDir() && ! $file->isLink()) {
            rmdir($file->getPathname());
        } else {
            unlink($file->getPathname());
        }
    }

    rmdir($this->tmp);
});

function scaffoldApp(string $root, array $opts = []): void
{
    $dirs = [
        'storage/app/private',
        'storage/app/public',
        'storage/framework/cache/data',
        'storage/framework/sessions',
        'storage/framework/views',
        'storage/framework/testing',
        'storage/logs',
        'bootstrap/cache',
        'node_modules',
        'vendor',
        'public/build',
    ];

    foreach ($dirs as $dir) {
        mkdir($root.'/'.$dir, 0777, true);
    }

    touch($root.'/vendor/autoload.php');
    touch($root.'/public/build/manifest.json');
    touch($root.'/artisan');
    touch($root.'/composer.json');
    touch($root.'/.env.example');

    if (! empty($opts['installed'])) {
        if (! is_dir($root.'/storage/app')) {
            mkdir($root.'/storage/app', 0777, true);
        }
        file_put_contents($root.'/storage/app/installed', '{}');
    }
}

test('a complete application passes every requirement', function () {
    scaffoldApp($this->tmp);

    $checks = $this->checker->check($this->tmp);

    expect($this->checker->allPassed($checks))->toBeTrue();
});

test('missing composer dependencies fail the requirements', function () {
    scaffoldApp($this->tmp);
    unlink($this->tmp.'/vendor/autoload.php');

    $check = collect($this->checker->check($this->tmp))->firstWhere('id', 'vendor');

    expect($check['ok'])->toBeFalse()
        ->and($check['detail'])->toContain('composer install')
        ->and($check['group'])->toBe('dependencies');
});

test('missing npm dependencies fail the requirements', function () {
    scaffoldApp($this->tmp);
    rmdir($this->tmp.'/node_modules');

    $check = collect($this->checker->check($this->tmp))->firstWhere('id', 'node_modules');

    expect($check['ok'])->toBeFalse()
        ->and($check['detail'])->toContain('npm install');
});

test('missing frontend build output fails the requirements', function () {
    scaffoldApp($this->tmp);
    unlink($this->tmp.'/public/build/manifest.json');

    $check = collect($this->checker->check($this->tmp))->firstWhere('id', 'frontend_build');

    expect($check['ok'])->toBeFalse()
        ->and($check['detail'])->toContain('npm run build');
});

test('an existing installed marker fails the not-installed check', function () {
    scaffoldApp($this->tmp, ['installed' => true]);

    $check = collect($this->checker->check($this->tmp))->firstWhere('id', 'not_installed');

    expect($check['ok'])->toBeFalse();
});

test('a missing writable directory is reported with its path', function () {
    scaffoldApp($this->tmp);
    rmdir($this->tmp.'/storage/framework/views');

    $check = collect($this->checker->check($this->tmp))->firstWhere('id', 'writable_views');

    expect($check['ok'])->toBeFalse()
        ->and($check['detail'])->toContain('Missing directory')
        ->and($check['detail'])->toContain('storage/framework/views');
});

test('the app-path check fails when artisan is missing', function () {
    scaffoldApp($this->tmp);
    unlink($this->tmp.'/artisan');

    $check = collect($this->checker->check($this->tmp))->firstWhere('id', 'app_path');

    expect($check['ok'])->toBeFalse();
});
