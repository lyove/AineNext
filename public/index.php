<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Pre-boot permission guard
|--------------------------------------------------------------------------
| Laravel needs several writable directories before it can render any page
| at all (compiled blade views, file sessions, logs, cache, and the .env
| bootstrap). When a fresh upload does not have those permissions yet, the
| framework throws a 500 before the installer even renders — which makes
| the wizard's own "Requirements" step unreachable without manual fixes.
*/
if (! file_exists(__DIR__.'/../storage/app/installed')) {
    $prebootRoot = dirname(__DIR__);

    $prebootTargets = [
        'storage' => $prebootRoot.'/storage',
        'storage/app' => $prebootRoot.'/storage/app',
        'storage/app/private' => $prebootRoot.'/storage/app/private',
        'storage/app/public' => $prebootRoot.'/storage/app/public',
        'storage/framework' => $prebootRoot.'/storage/framework',
        'storage/framework/cache' => $prebootRoot.'/storage/framework/cache',
        'storage/framework/cache/data' => $prebootRoot.'/storage/framework/cache/data',
        'storage/framework/sessions' => $prebootRoot.'/storage/framework/sessions',
        'storage/framework/testing' => $prebootRoot.'/storage/framework/testing',
        'storage/framework/views' => $prebootRoot.'/storage/framework/views',
        'storage/logs' => $prebootRoot.'/storage/logs',
        'bootstrap/cache' => $prebootRoot.'/bootstrap/cache',
    ];

    if (! is_file($prebootRoot.'/.env')) {
        $prebootTargets['application root'] = $prebootRoot;
    }

    $prebootProblems = [];

    foreach ($prebootTargets as $label => $path) {
        if (! is_dir($path)) {
            if (! @mkdir($path, 0775, true)) {
                $prebootProblems[$label] = $path.' — missing and could not be created';
            }

            continue;
        }

        $probe = $path.'/.installer-write-test-'.bin2hex(random_bytes(4));
        $writable = @file_put_contents($probe, 'ok') !== false;
        if ($writable) {
            @unlink($probe);
        }

        if (! $writable && is_writable($path)) {
            $writable = true;
        }

        if (! $writable) {
            $prebootProblems[$label] = $path.' — not writable by the web server';
        }
    }

    if ($prebootProblems !== []) {
        $prebootCss = (string) @file_get_contents(__DIR__.'/../installer/resources/assets/install.css');
        $prebootLogo = 'data:image/svg+xml;base64,'.base64_encode((string) @file_get_contents(__DIR__.'/../installer/resources/assets/logo.svg'));
        $prebootRefresh = htmlspecialchars((string) ($_SERVER['REQUEST_URI'] ?? '/'), ENT_QUOTES, 'UTF-8');

        $prebootRows = '';
        foreach ($prebootProblems as $label => $detail) {
            $prebootRows .= '<li class="bad"><span class="status" aria-hidden="true">!</span>'
                .'<span class="label">'.htmlspecialchars((string) $label, ENT_QUOTES, 'UTF-8').'</span>'
                .'<span class="detail">'.htmlspecialchars((string) $detail, ENT_QUOTES, 'UTF-8').'</span>'
                .'</li>';
        }

        http_response_code(200);

        echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Directory permissions — AineNext</title>
    <style>'.$prebootCss.'</style>
</head>
<body>
<div class="shell">
    <div class="brand">
        <div class="brand-mark">
            <img src="'.$prebootLogo.'" width="32" height="32" alt="AineNext">
        </div>
        <div class="brand-text">
            <strong>AineNext</strong>
            <span>Web installer</span>
        </div>
    </div>
    <div class="card">
        <h1>Directory permissions required</h1>
        <p class="lede">
            Before AineNext can serve any page it needs write access to a few directories
            (compiled views, sessions, logs, cache and the <code>.env</code> bootstrap).
            Fix the entries below, then refresh this page to enter the installer —
            its <em>Requirements</em> step will then perform the full check.
        </p>
        <ul class="check-list">'.$prebootRows.'</ul>
        <h2>How to fix it</h2>
        <p>Find the user your web server runs as (commonly <code>www-data</code>, <code>nginx</code> or <code>apache</code>), then give it ownership of the writable paths:</p>
        <pre>chown -R www-data:www-data storage bootstrap/cache</pre>
        <p>If you cannot use <code>chown</code> (e.g. shared hosting), make the directories group-writable instead:</p>
        <pre>chmod -R 775 storage bootstrap/cache</pre>
        <p>If the application root itself is listed (because <code>.env</code> does not exist yet), either make it writable temporarily, or upload a pre-filled <code>.env</code> (copy <code>.env.example</code>, generate an <code>APP_KEY</code>) and reload. On Windows / IIS, grant write permission to the web server user through the folder\'s properties instead.</p>
        <p>Then <a href="'.$prebootRefresh.'">refresh this page</a> to continue the installation.</p>
    </div>
</div>
</body>
</html>';

        exit;
    }
}

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
