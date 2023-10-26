<?php

namespace MailZeet\Laravel;

use GuzzleHttp\Client;
use MailZeet\Exceptions\InvalidPayloadException;

final class Config
{
    public static function getQueue(): string
    {
        $queue = config('mailzeet.queue');

        if (empty($queue)) {
            throw new InvalidPayloadException('MailZeet queue is not set.');
        }

        return $queue;
    }

    public static function getBaseUrl(): string
    {
        $baseURL = config('mailzeet.devMode') === true
            ? config('mailzeet.devBaseUrl')
            : 'https://api.mailzeet.com';

        if (empty($baseURL)) {
            throw new InvalidPayloadException('MailZeet base URL is not set.');
        }

        return $baseURL;
    }

    public static function getApiKey(): string
    {
        $apiKey = config('mailzeet.apiKey');

        if (empty($apiKey)) {
            throw new InvalidPayloadException('MailZeet API key is not set.');
        }

        return $apiKey;
    }

    public static function getDevMode(): bool
    {
        $devMode = config('mailzeet.devMode');

        if (empty($devMode)) {
            return false;
        }

        if (! is_bool($devMode)) {
            throw new InvalidPayloadException('MailZeet dev mode is not a boolean.');
        }

        return true;
    }

    public static function getEnv(): string
    {
        $env = config('mailzeet.env');

        if (empty($env)) {
            return 'live';
        }

        if ((! in_array($env, ['live', 'test']))) {
            throw new InvalidPayloadException('MailZeet env is not a valid value. It should be either "live" or "test".');
        }

        return $env;
    }

    public static function getGuzzleClient(): null|Client
    {
        $client = config('mailzeet.guzzleClient');
        if (empty($client)) {
            return null;
        }

        if (! $client instanceof Client) {
            throw new InvalidPayloadException('MailZeet Guzzle client is not a valid Guzzle client.');
        }

        return $client;
    }
}
