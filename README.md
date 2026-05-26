<div align="center">
<a href="https://mailzeet.com" title="MailZeet - Payment stack for Africa">
    <img src="/art/cover.png" alt="MailZeet website">
</a>

# MailZeet Laravel SDK

<!-- Nav header - Start -->

<a href="https://www.mailzeet.com/">Website</a>
·
<a href="https://www.mailzeet.com/contact">Contact</a>
·
<a href="https://docs.mailzeet.com/">Documentation</a>

<!-- Nav header - END -->

<!-- Badges - Start -->
[![PHP Version](https://img.shields.io/packagist/php-v/mailzeet/mailzeet-laravel.svg)](https://packagist.org/packages/mailzeet/mailzeet-laravel)
[![Build Status](https://github.com/mailzeet/mailzeet-laravel/actions/workflows/run-tests.yml/badge.svg?branch=main)](https://github.com/mailzeethq/mailzeet-laravel/actions?query=branch%3Amain)
[![Latest Stable Version](https://poser.pugx.org/mailzeethq/mailzeet-laravel/v/stable.svg)](https://packagist.org/packages/mailzeet/mailzeet-laravel)
[![Total Downloads](https://poser.pugx.org/mailzeet/mailzeet-laravel/downloads.svg)](https://packagist.org/packages/mailzeet/mailzeet-laravel)
[![License](https://poser.pugx.org/mailzeet/mailzeet-laravel/license.svg)](https://packagist.org/packages/mailzeet/mailzeet-laravel)
<!-- Badges - END -->

</div>

## Requirements
Laravel 9 and later.
PHP Requirements: PHP 8.1 and later. (Not tested on PHP 8.0, but it should work)

For PHP 7.4 and 8.0 or Laravel 8 and below , please use the [mailzeet-php](http://github.com/mailzeetHQ/mailzeet-php) package.

## Installation

You can install the package via composer:

```shell
composer require mailzeet/mailzeet-laravel
```

### Configuration

After you've installed the package via composer, you can run this command:

```bash
php artisan mailzeet:install
```

This command will:

1. Publish a `mailzeet.php` file in your config directory
2. Append your `.env` file with the `MONEROO_PUBLIC_KEY` and `MONEROO_SECRET_KEY` variables if they don't already exist.

You will have to replace 'your-public-key' and 'your-secret-key' with your actual MailZeet public key and secret key respectively.

```env
MAILZEET_PUBLIC_KEY=your-public-key
MAILZEET_SECRET_KEY=your-secret-key
```

Please keep in mind that these are sensitive keys and should not be publicly exposed.
Laravel .env file is ignored by Git, which makes it a good place to store sensitive information.


## Documentation

See the Laravel SDK [documentation](https://docs.mailzeet.com/).

## Development

1- Perform the tests
```shell
 composer test
```
2- Format and analyze your code before commit and push.
```shell
    composer format # Format your code with the required code style
    composer unused # check if there is an unused dependency
    composer analyze # Analyze your code with phpstan
```

### DEV Mode
You can set (or add) `mailzeet.devMode` to `true` in your `config/mailzeet.php` file to enable the dev mode.
After enabling the dev mode, you can set `mailzeet.devBaseUrl` to customize the base URL of the MailZeet API you want to use.
In dev mode, the SDK will use the `mailzeet.devBaseUrl` instead of the default base URL `https://api.mailzeet.com`.

## Notes
- The project is based on the KISS principle.
- Each time you make a change, you must run the tests and format your code.
- Each time you make a change, you must update the documentation.
- Each time you make a change, you must update the changelog.
- Each time you make a change, you must add test cases.
- Each time you make a change, you must update the version number.
- Each time you make a change, you must update the API documentation.
- Each time you make a change, you must update the README.md file.

## Security Vulnerabilities

If you discover a security vulnerability within MailZeet Laravel SDK, please send an e-mail to MailZeet Security via [hello@mailzeet.com](mailto:security@mailzeet.com). All security vulnerabilities will be promptly addressed.

## License

The MailZeet Laravel SDK is open-sourced software licensed under the [MIT license](LICENSE.md).
