<?php

use AineNext\Installer\Services\InstalledFileManager;

beforeEach(function () {
    $this->tmp = sys_get_temp_dir().'/ainenext-installed-test-'.bin2hex(random_bytes(6));
    mkdir($this->tmp, 0777, true);
});

afterEach(function () {
    if (is_dir($this->tmp)) {
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
    }
});

test('write creates a valid JSON lock file', function () {
    InstalledFileManager::write('https://example.com/', $this->tmp);

    $file = $this->tmp.'/installed';

    expect(is_file($file))->toBeTrue()
        ->and(fileperms($file) & 0777)->toBe(0644);

    $data = json_decode((string) file_get_contents($file), true);

    expect($data)->not->toBeNull()
        ->and($data)->toHaveKeys(['installed_at', 'app_url', 'installer'])
        ->and($data['app_url'])->toBe('https://example.com')
        ->and($data['installer'])->toBe('ainenext-installer');
});

test('write creates the target directory when it is missing', function () {
    $dir = $this->tmp.'/nested/app';

    InstalledFileManager::write('http://localhost', $dir);

    expect(is_file($dir.'/installed'))->toBeTrue();
});

test('write throws when the target directory is not writable', function () {
    $dir = $this->tmp.'/readonly';
    mkdir($dir, 0555, true);

    // Swallow the native PHP warning that a write to a read-only directory
    // triggers; Pest would otherwise flag it even though the @ operator
    // suppressed it.
    set_error_handler(static fn (): bool => true);
    try {
        try {
            InstalledFileManager::write('http://localhost', $dir);
        } finally {
            chmod($dir, 0755);
        }
    } finally {
        restore_error_handler();
    }
})->throws(RuntimeException::class);

test('write leaves no temp files behind', function () {
    InstalledFileManager::write('https://example.com/', $this->tmp);

    $leftovers = array_values(array_filter(
        scandir($this->tmp),
        fn ($name) => str_starts_with($name, '.installed-')
    ));

    expect($leftovers)->toBe([]);
});
