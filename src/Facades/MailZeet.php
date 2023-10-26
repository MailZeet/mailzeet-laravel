<?php

namespace MailZeet\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

class MailZeet extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'MailZeet';
    }
}
