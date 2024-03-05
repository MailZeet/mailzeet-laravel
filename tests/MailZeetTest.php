<?php

namespace MailZeet\Laravel\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Queue;
use MailZeet\Laravel\Jobs\SendEmailJob;
use MailZeet\Laravel\MailZeet;
use MailZeet\Objects\Address;
use MailZeet\Objects\Mail;

class MailZeetTest extends TestCase
{
    /**
     * It should send and dispatch a job with correct params.
     *
     * @test
     */
    public function it_should_send_and_dispatch_a_job_with_correct_params(): void
    {
        Queue::fake();

        config(
            [
                'mailzeet.queue'      => 'expected-queue',
                'mailzeet.apiKey'     => 'expected-api-key',
                'mailzeet.devMode'    => true,
                'mailzeet.devBaseUrl' => 'http://api.example.com']
        );

        $mail = new Mail();

        MailZeet::send($mail);

        Queue::assertPushedOn('expected-queue', SendEmailJob::class);
    }

    /**
     * It should send and dispatch job instantly with correct params via SendNow method.
     *
     * @test
     */
    public function it_should_send_and_dispatch_job_instantly_with_correct_params_via_send_now_method(): void
    {
        config(
            [
                'mailzeet.queue'      => 'expected-queue',
                'mailzeet.apiKey'     => 'expected-api-key',
                'mailzeet.devMode'    => true,
                'mailzeet.devBaseUrl' => 'http://api.example.com']
        );

        $mail = Mail::make()
            ->setRecipients([
                Address::make(
                    $this->faker->email(),
                    $this->faker->firstName(),
                ),
            ]);

        $mockHandler = new MockHandler([
            new Response(200, [], json_encode(['status' => 'success'])),
        ]);
        $mockedGuzzleClient = new Client([
            'handler'     => HandlerStack::create($mockHandler),
            'http_errors' => false,
        ]);

        config(['mailzeet.guzzleClient' => $mockedGuzzleClient]);

        $response = MailZeet::sendNow($mail);

        $this->assertEquals('success', $response->status);
    }

    /**
     * It should send after response dispatches job after response.
     *
     * @test
     */
    public function it_should_send_after_response_dispatches_job_after_response(): void
    {
        Bus::fake();

        config(
            [
                'mailzeet.queue'      => 'expected-queue',
                'mailzeet.apiKey'     => 'expected-api-key',
                'mailzeet.devMode'    => true,
                'mailzeet.devBaseUrl' => 'http://api.example.com']
        );

        $mail = new Mail();

        MailZeet::sendAfterResponse($mail);

        Bus::assertDispatchedAfterResponse(SendEmailJob::class);
    }
}
