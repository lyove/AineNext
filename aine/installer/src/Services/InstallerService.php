<?php

declare(strict_types=1);

namespace AineNext\Installer\Services;

use App\Models\User;
use AineNext\Installer\Support\InstallerHelpers;
use Illuminate\Support\Facades\Artisan;
use RuntimeException;
use Throwable;

final class InstallerService
{
    public function __construct(private readonly EnvironmentManager $environment) {}

    /**
     * Run migrations, seeders, apply the admin account and write the install
     * lock. The .env driver finalization is handled by InstallController::run.
     *
     * @param  array<string, string>  $config
     * @return list<string>
     */
    public function run(array $config): array
    {
        $log = [];

        if (InstalledFileManager::installed()) {
            throw new RuntimeException('Application is already installed.');
        }

        $migrate = Artisan::call('migrate', ['--force' => true]);
        if ($migrate !== 0) {
            throw new RuntimeException('Migration failed. Output: '.Artisan::output());
        }
        $log[] = 'Ran migrations';

        $seed = Artisan::call('db:seed', ['--force' => true]);
        if ($seed !== 0) {
            throw new RuntimeException('Seeding failed. Output: '.Artisan::output());
        }
        $log[] = 'Ran database seeders';

        $user = User::query()->where('email', 'admin@admin.com')->first();
        if ($user === null) {
            $user = User::query()->orderBy('id')->first();
        }

        if ($user === null) {
            throw new RuntimeException('Admin user was not created by the seeder.');
        }

        $user->name = $config['admin_name'];
        $user->email = $config['admin_email'];
        $user->password = $config['admin_password'];
        $user->save();
        $log[] = 'Updated admin account ('.$config['admin_email'].')';

        InstalledFileManager::write($config['app_url']);
        $log[] = 'Wrote install lock';

        return $log;
    }

    public function cronCommand(): string
    {
        return InstallerHelpers::cronCommand(base_path());
    }
}
