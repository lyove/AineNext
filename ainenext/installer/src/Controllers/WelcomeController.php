<?php

declare(strict_types=1);

namespace AineNext\Installer\Controllers;

use Illuminate\View\View;

final class WelcomeController
{
    public function welcome(): View
    {
        return view('installer::welcome');
    }
}
