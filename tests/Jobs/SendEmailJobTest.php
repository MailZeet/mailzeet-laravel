<?php

namespace MailZeet\Laravel\Tests\Jobs;

use Illuminate\Support\Facades\Log;
use MailZeet\Laravel\Jobs\SendEmailJob;
use MailZeet\Laravel\Tests\TestCase;
use MailZeet\MailZeet;
use MailZeet\Objects\Mail;

class SendEmailJobTest extends TestCase
{
    /**
     * It should not send email in test environment.
     *
     * @test
     */
    public function it_should_not_send_email_in_test_environment(): void
    {
        config([
            'mailzeet.env'    => 'test',
            'mailzeet.apiKey' => 'expected-api-key',
        ]);

        Log::shouldReceive('info')
            ->once()
            ->withArgs(function ($message, $context) {
                $this->assertStringContainsString('MailZeet: Email not sent because the environment is set to test.', $message);
                $this->assertIsArray($context);
                return true;
            });

        $mail = Mail::make();
        $mailZeetInstance = \MailZeet\Laravel\MailZeet::getMailZeetClientInstance();

        $job = new SendEmailJob($mail, $mailZeetInstance);
        $job->handle();
    }

    /**
     * It should send email in live environment.
     *
     * @test
     */
    public function it_should_send_email_in_live_environment(): void
    {
        $mailZeetMock = $this->createMock(MailZeet::class);
        $mailZeetMock->expects($this->once())
            ->method('send');

        config(['mailzeet.env' => 'live']);

        $mail = Mail::make();

        $job = new SendEmailJob($mail, $mailZeetMock);
        $job->handle();
    }
}
