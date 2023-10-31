<?php

namespace MailZeet\Laravel\Tests;

use MailZeet\Exceptions\InvalidPayloadException;
use MailZeet\Laravel\Config;

class ConfigTest extends TestCase
{
    /**
     * It should return the expected value.
     *
     * @test
     */
    public function it_should_return_the_expected_value(): void
    {
        config(['mailzeet.queue' => 'expected-queue']);

        $this->assertEquals('expected-queue', Config::getQueue());
    }

    /**
     * It should throw an exception on empty value.
     *
     * @test
     */
    public function it_should_throw_an_exception_on_empty_value_for_queue(): void
    {
        config(['mailzeet.queue' => '']);

        $this->expectException(InvalidPayloadException::class);
        $this->expectExceptionMessage('MailZeet queue is not set.');

        Config::getQueue();
    }

    /**
     * It should get the base URL when in dev mode.
     *
     * @test
     */
    public function it_should_get_the_base_url_when_in_dev_mode(): void
    {
        config(['mailzeet.devMode' => true, 'mailzeet.devBaseUrl' => 'http://localhost']);

        $this->assertEquals('http://localhost', Config::getBaseUrl());
    }

    /**
     * It should get the default base URL when not in dev mode.
     *
     * @test
     */
    public function it_should_get_the_default_base_url_when_not_in_dev_mode(): void
    {
        config(['mailzeet.devMode' => false]);

        $this->assertEquals('https://api.mailzeet.com/v1', Config::getBaseUrl());
    }

    /**
     * It should throw an exception on empty value.
     *
     * @test
     */
    public function it_should_throw_an_exception_on_empty_value(): void
    {
        config(['mailzeet.devMode' => true, 'mailzeet.devBaseUrl' => '']);

        $this->expectException(InvalidPayloadException::class);
        $this->expectExceptionMessage('MailZeet base URL is not set.');

        Config::getBaseUrl();
    }

    /**
     * It should get the expected value.
     *
     * @test
     */
    public function it_should_get_the_expected_value(): void
    {
        config(['mailzeet.apiKey' => 'expected-api-key']);

        $this->assertEquals('expected-api-key', Config::getApiKey());
    }

    /**
     * It should throw an exception on empty value.
     *
     * @test
     */
    public function it_should_throw_an_exception_on_empty_value_for_api_key(): void
    {
        config(['mailzeet.apiKey' => '']);

        $this->expectException(InvalidPayloadException::class);
        $this->expectExceptionMessage('MailZeet API key is not set.');

        Config::getApiKey();
    }

    /**
     * It should get the expected value.
     *
     * @test
     */
    public function it_should_get_the_expected_value_for_dev_mode(): void
    {
        config(['mailzeet.devMode' => true]);

        $this->assertTrue(Config::getDevMode());

        config(['mailzeet.devMode' => false]);

        $this->assertFalse(Config::getDevMode());
    }

    /**
     * It should throw an exception if invalid value is set for dev mode.
     *
     * @test
     */
    public function it_should_throw_an_exception_if_invalid_value_is_set_for_dev_mode(): void
    {
        config(['mailzeet.devMode' => 'invalid-dev-mode']);

        $this->expectException(InvalidPayloadException::class);
        $this->expectExceptionMessage('MailZeet dev mode is not a boolean.');

        Config::getDevMode();
    }

    /**
     * It should get the expected value.
     *
     * @test
     */
    public function it_should_get_the_expected_value_for_env(): void
    {
        config(['mailzeet.env' => 'live']);

        $this->assertEquals('live', Config::getEnv());
    }

    /**
     * It should throw an exception if invalid value is set for env.
     *
     * @test
     */
    public function it_should_throw_an_exception_if_invalid_value_is_set_for_env(): void
    {
        config(['mailzeet.env' => 'invalid-env']);

        $this->expectException(InvalidPayloadException::class);
        $this->expectExceptionMessage('MailZeet env is not a valid value. It should be either "live" or "test".');

        Config::getEnv();
    }

    /**
     * It should throw an exception if the Guzzle client is not a valid Guzzle client.
     *
     * @test
     */
    public function it_should_throw_an_exception_if_the_guzzle_client_is_not_a_valid_guzzle_client(): void
    {
        config(['mailzeet.guzzleClient' => 'invalid-guzzle-client']);

        $this->expectException(InvalidPayloadException::class);
        $this->expectExceptionMessage('MailZeet Guzzle client is not a valid Guzzle client.');

        Config::getGuzzleClient();
    }
}
