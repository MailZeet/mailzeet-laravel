<?php

namespace MailZeet\Laravel\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use MailZeet\Laravel\Config;
use MailZeet\MailZeet;
use MailZeet\Objects\Mail;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable;

    use InteractsWithQueue;

    use Queueable;

    private Mail $mail;

    private MailZeet $mailZeetInstance;

    public function __construct(
        Mail $mail,
        MailZeet $mailZeetInstance
    ) {
        $this->mail = $mail;
        $this->mailZeetInstance = $mailZeetInstance;
    }

    public function handle(): void
    {
        if (Config::getEnv() === 'test') {
            info(
                message: 'MailZeet: Email not sent because the environment is set to test. ' .
                    'To send emails, set the environment to live.',
                context: $this->mail->toArray()
            );
            return;
        }

        $this->mailZeetInstance->send($this->mail);
    }
}
