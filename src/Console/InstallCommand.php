<?php

namespace MailZeet\Laravel\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use MailZeet\Laravel\Providers\MailZeetServiceProvider;

final class InstallCommand extends Command
{
    protected $signature = 'mailzeet:install';

    protected $description = 'Install the MailZeet Laravel Package';

    public function handle(): void
    {
        $this->info(string: 'Installing MailZeet Laravel Package...');

        $this->info(string: 'Publishing configuration...');

        if (! $this->configExists()) {
            $this->publishConfiguration();
            $this->info(string: 'Published configuration');
        } elseif ($this->shouldOverwriteConfig()) {
            $this->info(string: 'Overwriting configuration file...');
            $this->publishConfiguration($force = true);
        } else {
            $this->info(string: 'Existing configuration was not overwritten');
        }

        $this->info(string: 'MailZeet Laravel Package installed successfully.');
    }

    private function configExists(): bool
    {
        return File::exists(config_path(path: 'mailzeet-laravel.php'));
    }

    private function shouldOverwriteConfig(): bool
    {
        return $this->confirm(
            question: 'Config file already exists. Do you want to overwrite it?',
            default: false
        );
    }

    private function publishConfiguration($forcePublish = false): void
    {
        $params = [
            '--provider' => MailZeetServiceProvider::class,
            '--tag'      => 'config',
        ];

        if ($forcePublish === true) {
            $params['--force'] = true;
        }
        $this->call('vendor:publish', $params);
        $this->updateEnvironmentFile();
    }

    /**
     * Updates the environment file with the basic configuration.
     */
    public function updateEnvironmentFile(): void
    {
        if (File::exists($env = app()->environmentFile())) {
            $contents = File::get(path: $env);

            if (! Str::contains($contents, 'MAILZEET_QUEUE=')) {
                File::append(
                    path: $env,
                    data: PHP_EOL . 'MAILZEET_QUEUE=default' . PHP_EOL
                );
                $this->info('Added MAILZEET_QUEUE to your .env file');
            }

            if (! Str::contains($contents, 'MAILZEET_API_KEY=')) {
                File::append(
                    path: $env,
                    data: PHP_EOL . 'MAILZEET_API_KEY=your-api-key' . PHP_EOL
                );
                $this->info(string: 'Added MAILZEET_API_KEY to your .env file');
                $this->info(string: 'Please update the value with your MailZeet API Key');
            }
        }
    }
}
