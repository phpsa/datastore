# Datastore

[![Build Status](https://travis-ci.org/phpsa/datastore.svg?branch=master)](https://travis-ci.org/phpsa/datastore)
[![styleci](https://styleci.io/repos/CHANGEME/shield)](https://styleci.io/repos/CHANGEME)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/phpsa/datastore/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/phpsa/datastore/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/CHANGEME/mini.png)](https://insight.sensiolabs.com/projects/CHANGEME)
[![Coverage Status](https://coveralls.io/repos/github/phpsa/datastore/badge.svg?branch=master)](https://coveralls.io/github/phpsa/datastore?branch=master)

[![Packagist](https://img.shields.io/packagist/v/phpsa/datastore.svg)](https://packagist.org/packages/phpsa/datastore)
[![Packagist](https://poser.pugx.org/phpsa/datastore/d/total.svg)](https://packagist.org/packages/phpsa/datastore)
[![Packagist](https://img.shields.io/packagist/l/phpsa/datastore.svg)](https://packagist.org/packages/phpsa/datastore)

Package description: Content Asset Management System (CAMS) Datastore package for a simplistic yet powerfull and extendable CMS for Laravel 5.8 and up

Package was developed over [Laravel-5-boilerplace](https://github.com/rappasoft/laravel-5-boilerplate) however should be able to work independantly (another package may be built to deal with this if there is any demand)

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

Optionally Register package facade in `config/app.php` in `aliases` section
```php
Phpsa\Datastore\Facades\Datastore::class,
```

### Publish Configuration/Translations/Js/scss File

```bash
php artisan vendor:publish --provider="Phpsa\Datastore\ServiceProvider"
```

## Adding to `rappasoft/laravel-5-boilerplate`
Add
```php
@include('phpsa-datastore::backend.sidebar')
```
to `resources/views/backend/includes/sidebar.blade.php` where you would like the content managemtn menu to appear.

## Configuration Options

+ *`assets`* - Which content assets are enabled
+ *`urlprefix`* - url prefix (default ams) to prefix the content asset path with

## Default Assets
Each page type is called an Asset, Each Asset can have 1 or more Properties, along with children and accepted keys to allow joining 1 or more asset types together

The Default package is shipped with the following:
+ *`ContentAsset`* Default page type, with a Title & Body
+ *`BlockAsset`* Simplistic Non-page asset this will allow you to add to a standard view to allow managing prebuilt content in non CMS pages (ie login / register / contact pages)
+ *`TabsAsset`* Tab view asset, allows you to build a page with tabbed content blocks.
+ *`ArticleCategoryAsset` Categories for ArticleItemsAssets
+ *`ArticleItemAsset`* Article Items that can be assigned to articleCategoryAsset(s)

## Default Properties

+ *`Boolean`* - On off swith for the asset form
+ *`Datepicker`* - Renders an input type date
+ *`Dropdown`* - renders a dropdown
+ *`File`* - File upload
+ *`Heading`* - Heading property default render with an h2
+ *`Html`* -  Html WYSIWYG area
+ *`Identity`* - Autocomplete linked to Users List to assign n author to a asset for example
+ *`Image`* - Image uploader
+ *`Metatext`* - Internal mainly - allows for description / keywords header options,
+ *`String`* - standard text input
+ *`SubHeading`* - SubHeading text input - default render wiht an h3
+ *`Text`* -  textarea input

## Methods

### List of Available AMS Assets:
* BladeDirective `@forDatastores(true)` ... `@endforDatastores` returns grouped array (false) will return a single tree list.
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
