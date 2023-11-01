<?php

namespace MailZeet\Laravel;

use MailZeet\Laravel\Jobs\SendEmailJob;
use MailZeet\MailZeet as MailZeetClient;
use MailZeet\Objects\Mail;

class MailZeet
{
    public static function send(Mail $mailObject): void
    {
        $mailZeetInstance = self::getMailZeetClientInstance();

        SendEmailJob::dispatch($mailObject, $mailZeetInstance)->onQueue(Config::getQueue());
    }

    public static function sendNow(Mail $mailObject): object
    {
        if (Config::getEnv() === 'test') {
            info(
                message: 'MailZeet: Email not sent because the environment is set to test. ' .
                'To send emails, set the environment to live.',
                context: $mailObject->toArray()
            );
            return new \stdClass();
        }

        return self::getMailZeetClientInstance()->send(emailObject: $mailObject);
    }

    public static function sendAfterResponse(Mail $mailObject): void
    {
        $mailZeetInstance = self::getMailZeetClientInstance();

        SendEmailJob::dispatchAfterResponse($mailObject, $mailZeetInstance);
    }

    public static function getMailZeetClientInstance(): MailZeetClient
    {
        $baseURL = Config::getBaseUrl();
        $apiKey = Config::getApiKey();
        $devMode = Config::getDevMode();
        $guzzleClient = Config::getGuzzleClient();

        return new MailZeetClient(
            apiKey: $apiKey,
            devMode: $devMode,
            baseUrl: $baseURL,
            client: $guzzleClient
        );
    }
}
