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
  - [Ecommerce (GA4)](#ecommerce-ga4)
  - [Ecommerce (UA)](#ecommerce-ua)
- [Tests](#tests)
- [License](#license)

## Requirements

- Laravel 8.x

## Laravel support

| Version | Release |
|---------|---------|
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

More information: ``https://developers.google.com/analytics/devguides/collection/ga4/user-id?technology=tagmanager``

### Ecommerce (GA4)

You can use the following snippets to trigger an Ecommerce event with Google Analytics 4 (GA4).

```php
use Label84\TagManager\Facades\TagManager;

$item1 = [
    'item_name' => 'Triblend Android T-Shirt',
    'item_id' => '12345',
    'price' => 15.25,
    'item_brand' => 'Google',
    'item_category' => 'Apparel',
    'item_variant' => 'Gray',
    'quantity' => 1,
];

$item2 = [
    'item_name' => 'Donut Friday Scented T-Shirt',
    'item_id' => '67890',
    'price' => 33.75,
    'item_brand' => 'Google',
    'item_category' => 'Apparel',
    'item_variant' => 'Black',
    'quantity' => 1
];

$items = [
    $item1,
    $item2,
];

// Product views and interactions
TagManager::viewItemList(array $items);
TagManager::viewItem(array $items);
TagManager::selectItem(array $items);

// Promotion views and interactions
TagManager::viewPromotion(array $items);
TagManager::selectPromotion(array $items);

// Pre-purchase interactions
TagManager::addToWishList(string $currency, float $value, array $items);
TagManager::addToCart(array $items);
TagManager::removeFromCart(array $items);
TagManager::viewCart(string $currency, float $value, array $items);

// Purchases, checkouts, and refunds
TagManager::beginCheckout(array $items);
TagManager::addPaymentInfo(string $currency, float $value, string $coupon, string $paymentType, array $items);
TagManager::addShippingInfo(string $currency, float $value, string $coupon, string $shippingTier, array $items);
TagManager::purchase(string $transactionId, string $affiliation, string $currency, float $value, float $tax, float $shipping, string $coupon, array $items);
TagManager::refund(string $transactionId, string $affiliation, string $currency, float $value, float $tax, float $shipping, string $coupon, array $items);
```

More information: ``https://developers.google.com/analytics/devguides/collection/ga4/ecommerce?client_type=gtm``

### Ecommerce (UA)

You can use the following snippet to trigger an Ecommerce purchase event with Universal Analytics (UA).

```php
use Label84\TagManager\Facades\TagManager;

TagManager::push(['ecommerce' => [
    'purchase' => [
        'actionField' => [
            'id' => 'T12345',
            'affiliation' => 'Online Store',
            'revenue' => '35.43',
            'tax' => '4.90',
            'shipping' => '5.99',
            'coupon' => 'SUMMER_SALE',
        ],
        'products' => [[
            'name' => 'Triblend Android T-Shirt',
            'id' => '12345',
            'price' => '15.25',
            'brand' => 'Google',
            'category' => 'Apparel',
            'variant' => 'Gray',
            'quantity' => 1,
            'coupon' => '',
        ], [
            // more items..
        ]],
    ],
]]);
```

More information: ``https://developers.google.com/analytics/devguides/collection/ua/gtm/enhanced-ecommerce#purchases``

## Tests

```sh
./vendor/bin/phpstan analyze
```

## License

[MIT](https://opensource.org/licenses/MIT)
