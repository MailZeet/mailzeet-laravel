<?php

namespace MailZeet\Laravel\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use MailZeet\Laravel\Console;
use MailZeet\Laravel\Facades;
use MailZeet\Laravel\MailZeet;

class MailZeetServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(abstract: 'mailzeet', concrete: function () {
            return new MailZeet();
        });
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands(commands: [
                Console\InstallCommand::class,
            ]);

            $this->publishes([
                __DIR__ . '/../../config/mailzeet-laravel.php' => config_path('mailzeet.php'),
            ], 'config');
        }

        $this->app->booting(function () {
            $loader = AliasLoader::getInstance();
            $loader->alias('MailZeet', Facades\MailZeet::class);
        });
    }
}
