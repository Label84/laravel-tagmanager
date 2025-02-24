# Laravel TagManager

[![Latest Stable Version](https://poser.pugx.org/label84/laravel-tagmanager/v/stable?style=flat-square)](https://packagist.org/packages/label84/laravel-tagmanager)
[![MIT Licensed](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Total Downloads](https://img.shields.io/packagist/dt/label84/laravel-tagmanager.svg?style=flat-square)](https://packagist.org/packages/label84/laravel-tagmanager)

Easier way to add Google Tag Manager to your Laravel application. Including support for User-ID, E-commerce and Server Side Events (Measurement Protocol).

- [Laravel support](#laravel-support)
- [Installation](#installation)
- [Usage](#usage)
  - [Events](#events)
  - [User-ID](#user-id)
  - [Ecommerce (GA4)](#ecommerce-ga4)
    - [Ecommerce Item](#ecommerce-item)
    - [Ecommerce Events](#ecommerce-events)
- [Server Side Events](#server-side-events)
  - [Measurement Protocol](#measurement-protocol)
  - [Measurement Protocol Debug Mode](#measurement-protocol-debug-mode)
- [Tests](#tests)
- [License](#license)

## Laravel support

| Version | Release |
|---------|---------|
| 12.x    | ^1.5    |
| 11.x    | ^1.5    |

## Installation

Read the article on Medium.com for a more detailed instruction: [Medium: Add Google Analytics to your Laravel application with Google Tag Manager](https://tjardo.medium.com/add-google-analytics-to-your-laravel-application-with-google-tag-manager-c32eb0e1dc9a).

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

GOOGLE_TAG_MANAGER_ID=GTM-XXXXXX
GOOGLE_TAG_MANAGER_ENABLED=true
```

Go to ``https://tagmanager.google.com`` and copy the 'Container ID' of the account (it looks like GTM-XXXXXXX).

## Usage

```php
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

You can find a list of recommended events on: [https://support.google.com/analytics/answer/9267735?hl=en](https://support.google.com/analytics/answer/9267735?hl=en)

### User-ID

This package also supports the User-ID feature.

To start using the User-ID feature you've to add the ``TagManagerUserIdMiddleware`` in your 'web' middleware group directly after the ``TagManagerMiddleware``.

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

More information: [https://developers.google.com/analytics/devguides/collection/ga4/user-id?technology=tagmanager](https://developers.google.com/analytics/devguides/collection/ga4/user-id?technology=tagmanager)

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

More information: [https://developers.google.com/analytics/devguides/collection/ga4/ecommerce?client_type=gtm](https://developers.google.com/analytics/devguides/collection/ga4/ecommerce?client_type=gtm)

## Server Side Events

The Google Analytics Measurement Protocol allows developers to make HTTP requests to send raw user interaction data directly to Google Analytics servers. This allows developers to measure how users interact with their business from almost any environment. Developers can then use the Measurement Protocol to:

- Measure user activity in new environments.
- Tie online to offline behavior.
- Send data from both the client and server.

### Measurement Protocol

You need complete the general installation steps first, such as adding the ``.env`` variables, adding the head and body tags to your layout file and adding the ``TagManagerMiddleware``.

Add the following extra variables to your .env file.

```sh
// .env

# Found in the Google Analytics UI under Admin > Data Streams > choose your stream > Measurement ID. The measurement_id isn't your Stream ID.
GOOGLE_MEASUREMENT_ID=G-XXXXXX
# Found in the Google Analytics UI under Admin > Data Streams > choose your stream > Measurement Protocol API Secrets
GOOGLE_MEASUREMENT_PROTOCOL_API_SECRET=XXXXXX
```

Add the following snippet to the head of your blade layout file (below the existing ``x-tagmanager-head`` tag).

```html
    <x-tagmanager-head />
    <x-tagmanager-measurement-protocol-client-id />
</head>

<body>
```

```php
use Label84\TagManager\Facades\MeasurementProtocol;

MeasurementProtocol::event('some_event', ['foo' => 'bar']);

// Set a specific User-ID for this event (you can customize the key in the config file)
MeasurementProtocol::user($someUser)->event('some_event', ['foo' => 'bar']);
```

You can view the events directly in the Google Analytics UI under Realtime > Events.

### Measurement Protocol Debug Mode

You can enable the debug mode by calling the ``debug()`` method. This will return a JSON validation response instead of sending the request to Google Analytics.
If there are any errors, they will be returned in the validation messages response. If there are no errors, the validation response array will be empty.

```php
use Label84\TagManager\Facades\MeasurementProtocol;

dd(
    MeasurementProtocol::debug()->event('some_event', ['foo' => 'bar'])
);
```

## Tests

```sh
./vendor/bin/phpstan analyze
```

## License

[MIT](https://opensource.org/licenses/MIT)
