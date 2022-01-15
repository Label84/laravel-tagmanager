# Laravel TagManager

[![Latest Stable Version](https://poser.pugx.org/label84/laravel-tagmanager/v/stable?style=flat-square)](https://packagist.org/packages/label84/laravel-tagmanager)
[![MIT Licensed](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Quality Score](https://img.shields.io/scrutinizer/g/label84/laravel-tagmanager.svg?style=flat-square)](https://scrutinizer-ci.com/g/label84/laravel-tagmanager)
[![Total Downloads](https://img.shields.io/packagist/dt/label84/laravel-tagmanager.svg?style=flat-square)](https://packagist.org/packages/label84/laravel-tagmanager)

An easier way to add Google Tag Manager to your Laravel application. Including recommended GTM events support.

- [Requirements](#requirements)
- [Laravel support](#laravel-support)
- [Installation](#installation)
- [Usage](#usage)
  - [Events](#events)
  - [User-ID](#user-id)
- [Tests](#tests)
- [License](#license)

## Requirements

- Laravel 8.x

## Laravel support

| Version | Release |
|---------|---------|
| 9.x     | 1.2     |
| 8.x     | 1.0     |

## Installation

### 1. Install package

Install the package

```sh
composer require label84/laravel-tagmanager
```

### 2. Add the head and body tag to your (layout) view

Add the head tag directly above the closing ``</head>`` tag and the body tag directly after the opening ``<body>`` tag.

```html
    <x-tagmanager-head />
</head>

<body>
    <x-tagmanager-body />
```

### 3. Add the TagManagerMiddleware to your Kernel

Add the TagManagerMiddleware directly after the StartSession middleware in your 'web' middleware group.

```php
// app/Http/Kernel.php

protected $middlewareGroups = [
    'web' => [
        \App\Http\Middleware\EncryptCookies::class,
        ...
        \Illuminate\Session\Middleware\StartSession::class,
        \Label84\TagManager\Http\Middleware\TagManagerMiddleware::class,
        ...
```

### 4. Publish the config file

Publish the config file. This will generate a ``tagmanager.php`` file in your config directory.

```sh
php artisan vendor:publish --provider="Label84\TagManager\TagManagerServiceProvider" --tag="config"
```

### 5. Add your GTM ID to your .env

```sh
// .env

GOOGLE_TAG_MANAGER_ID=
GOOGLE_TAG_MANAGER_ENABLED=true
```

Go to ``https://tagmanager.google.com`` and copy the 'Container ID' of the account (it looks like GTM-XXXXXXX).

## Usage

```php
// DashboardController.php (example)

use Label84\TagManager\Facades\TagManager;

TagManager::push(['foo' => 'bar']);
```

### Events

You can also use the following methods. These will automatically set the correct event key and value.

```php
use Label84\TagManager\Facades\TagManager;

TagManager::event('kissed', ['status' => 'failed', 'count' => 0]);
TagManager::login(['foo' => 'bar']);
TagManager::register(['foo' => 'bar']);
```

You can find a list of recommended events on: ``https://support.google.com/analytics/answer/9267735?hl=en``

### User-ID

This package also supports the User-ID feature.

To start using the User-ID feature you've to add the TagManagerUserIdMiddleware in your 'web' middleware group directly after the TagManagerMiddleware.

```php
// app/Http/Kernel.php

protected $middlewareGroups = [
    'web' => [
        ...
        \Label84\TagManager\Http\Middleware\TagManagerMiddleware::class,
        \Label84\TagManager\Http\Middleware\TagManagerUserIdMiddleware::class,
        ...
```

By default the 'id' of the User model will be used. You change the key in ``config/tagmanager.php``.

You can find more information about this feature on: ``https://developers.google.com/analytics/devguides/collection/ga4/user-id?technology=tagmanager``

## Tests

```sh
./vendor/bin/phpstan analyze
```

## License

[MIT](https://opensource.org/licenses/MIT)
