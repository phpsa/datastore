# Datastore

[![Build Status](https://travis-ci.org/phpsa/datastore.svg?branch=master)](https://travis-ci.org/phpsa/datastore)
[![styleci](https://styleci.io/repos/CHANGEME/shield)](https://styleci.io/repos/CHANGEME)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/phpsa/datastore/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/phpsa/datastore/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/CHANGEME/mini.png)](https://insight.sensiolabs.com/projects/CHANGEME)
[![Coverage Status](https://coveralls.io/repos/github/phpsa/datastore/badge.svg?branch=master)](https://coveralls.io/github/phpsa/datastore?branch=master)

[![Packagist](https://img.shields.io/packagist/v/phpsa/datastore.svg)](https://packagist.org/packages/phpsa/datastore)
[![Packagist](https://poser.pugx.org/phpsa/datastore/d/total.svg)](https://packagist.org/packages/phpsa/datastore)
[![Packagist](https://img.shields.io/packagist/l/phpsa/datastore.svg)](https://packagist.org/packages/phpsa/datastore)

Package description: CHANGE ME

## Installation

Install via composer
```bash
composer require phpsa/datastore
```

### Register Service Provider

**Note! This and next step are optional if you use laravel>=5.5 with package
auto discovery feature.**

Add service provider to `config/app.php` in `providers` section
```php
Phpsa\Datastore\ServiceProvider::class,
```

### Register Facade

Register package facade in `config/app.php` in `aliases` section
```php
Phpsa\Datastore\Facades\Datastore::class,
```

### Publish Configuration/Translations/Js/scss File

```bash
php artisan vendor:publish --provider="Phpsa\Datastore\ServiceProvider"
```

## Using `rappasoft/laravel-5-boilerplate`
Add
```php
@include('phpsa-datastore::backend.sidebar')
```
to `resources/views/backend/includes/sidebar.blade.php` where you would like the assets to appear.

## Methods

### List of Available AMS Assets:
* BladeDirective `@forDatastores(true)` returns grouped array (false) will return a single tree list.
* `Phpsa/Datastore/Helpers::getAssetList(true||false)` within php code

## Usage

CHANGE ME

## Security

If you discover any security related issues, please email
instead of using the issue tracker.

## Credits

- [](https://github.com/phpsa/datastore)
- [All contributors](https://github.com/phpsa/datastore/graphs/contributors)

This package is bootstrapped with the help of
[melihovv/laravel-package-generator](https://github.com/melihovv/laravel-package-generator).
