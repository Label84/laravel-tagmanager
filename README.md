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
    - [Ecommerce Item](#ecommerce-item)
    - [Ecommerce Events](#ecommerce-events)
  - [Ecommerce (UA)](#ecommerce-ua)
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

More information: ``https://developers.google.com/analytics/devguides/collection/ga4/user-id?technology=tagmanager``

### Ecommerce (GA4)

You can use the following snippets to trigger an Ecommerce event with Google Analytics 4 (GA4).

#### Ecommerce item

The ``TagManagerItem`` class allows you to easily create an Ecommerce item. You can set extra parameters with dynamic calls. Method names are used as keys and automatically converted to underscore case.

```php
use Label84\TagManager\TagManagerItem;

new TagManagerItem(string $id, string $name, float $price, float $quantity);
```

##### Example: create item

```php
use Label84\TagManager\TagManagerItem;

$item1 = new TagManagerItem('12345', 'Triblend Android T-Shirt', 15.25, 1);
$item1->itemBrand('Google')       // will add the item parameter { item_brand: 'Google' }
      ->itemCategory('Apparel')   // will add the item parameter { item_category: 'Apparel' }
      ->itemVariant('Gray');      // will add the item parameter { item_variant: 'Gray' }
```

#### Ecommerce events

The items parameter can be a single ``TagManagerItem`` item or an array of ``TagManagerItem`` items. You can also use plain arrays if you don't want to use the TagManagerItem class.

```php
use Label84\TagManager\Facades\TagManager;

// Product views and interactions
TagManager::viewItemList($items);
TagManager::viewItem($items);
TagManager::selectItem($items);

// Promotion views and interactions
TagManager::viewPromotion($items);
TagManager::selectPromotion($items);

// Pre-purchase interactions
TagManager::addToWishList(string $currency, float $value, $items);
TagManager::addToCart($items);
TagManager::removeFromCart($items);
TagManager::viewCart(string $currency, float $value, $items);

// Purchases, checkouts, and refunds
TagManager::beginCheckout($items);
TagManager::addPaymentInfo(string $currency, float $value, string $paymentType, $items, string $coupon = '');
TagManager::addShippingInfo(string $currency, float $value, string $shippingTier, $items, string $coupon = '');
TagManager::purchase(string $transactionId, string $affiliation, string $currency, float $value, float $tax, float $shipping, $items, string $coupon = '');
TagManager::refund(string $transactionId, string $affiliation, string $currency, float $value, float $tax, float $shipping, $items, string $coupon = '');
```

##### Example: call event with item

```php
use Label84\TagManager\Facades\TagManager;

TagManager::purchase('00001', 'Google', 'EUR', 12.10, 2.10, 0, [
    new TagManagerItem('12345', 'Triblend Android T-Shirt', 10.00, 1),
]);
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
