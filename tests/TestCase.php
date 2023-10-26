<?php

namespace MailZeet\Laravel\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use MailZeet\Laravel\Providers\MailZeetServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    use WithFaker;

    protected function getPackageProviders($app): array
    {
        return [
            MailZeetServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');
        config()->set('app.debug', 'true');
    }
}
