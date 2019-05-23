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
+ *`::AUTOCALLBACKADDER`* - AutoComplete add list of items to be referenced via an ajax callback with an selectable option
+ *`::AUTOCALLBACK`* - AutoComplete add a single to be referenced via an ajax callback with a selction option
+ *`::AUTOCOMPLETE`* - Standardised Autocomplete
+ *`::BOOL`* - On off swith for the  form
+ *`::DATEPICKER`* - Renders an input type date
+ *`::DROPDOWN`* - renders a dropdown
+ *`::FILE`* - File upload
+ *`::HEADING`* - Heading property default render with an h2
+ *`::HTML`* -  Html WYSIWYG area
+ *`::IDENTITY`* - Autocomplete linked to Users List to assign n author to a  for example
+ *`::IMG`* - Image uploader
+ *`::METATEXT`* - Internal mainly - allows for description / keywords header options,
+ *`::STRING`* - standard text input
+ *`::SUBHEADING`* - SubHeading text input - default render wiht an h3
+ *`::TEXT`* -  textarea input

## Property Reference

| Key			| Required 	| Type			|	Details |
| ---			| ---		|	---			|	---		|
| **name**		|	Yes 	| String 		| Public name of the element rendered as the form label	|
| **type** 		|	Yes		| String		| Type of asset being used: one of the default properties or your custom one.	|
| **required**	|	No 		| Boolean		| Default true, set to false if the field is an optional field	|
| **help**		|	No		| String		| Helptip for your input field	|
| **options**	|	Yes *	| Array			| Only used on: *DROPDOWN*, *AUTOCALLBACK* & *AUTOCALLBACKADDER*, holds the array of options for the select dropdown	|
| **callback**	|	Yes *	| String/Array	| Only used on: *AUTOCALLBACKADDER*, *AUTOCALLBACK*, *AUTOCOMPLETE*, holds the callback url string or array format ['url' => 'url/endpoint', 'method'=> '(post/get)']

## Asset Reference (Content Item)

All asset classes extend the `Phpsa\Datastore\Ams\Asset` Class. Available class properties / methods are set as follows

| Property  		| Visibility | Type 	| Required	|	Default | Description |
| --- 				| ---		 | ---		| ---	  	|	---		| ---		  |
| name				| 	public	 | String	| Yes		|			|	Friendly Name of the component - public |
| shortname			| 	public	 | String	| Yea		|			| 	Short name (unique) for teh component use for internal reference	|
| private			| 	public	 | Boolean	| No		|	false	|	Whether will be a public faeing component - true disabled page & slug section	|
| children			| 	public	 | String	| No		|	null	|	Links to another asset type as a set of children	|
| is_child			| 	public	 | boolean	| no		| 	false	|	Is child - is this a child asset, -- this will forceably hide it from the list - usefull for private assets |
| accept			| 	public	 | String	| No		|	null	|	Asset that can be assigned to the aset	|
| accept_limit		| 	public	 | Int		| No		|	null	|	Set to 1 or higher to limit, null = unlimited	|
| max_instances		| 	public	 | Int		| No		|	0		|	Max number of assets of the type that can be generated.	|
| value_equals		| 	public	 | String	| Yes		|			|	Which property is the main identifier	|
| namespace			|	public	 | String	| Yes		|	'asset'	|
| type				|	public	 | String	| Yes		| 	'asset`	|
| properties		|	public	 | Array	| No		| 	array	|
| meta_description	|	public	 | String	| No		|	null	|	Set to off to disable this, any other string will be the default for the form
| meta_keywords		|	public	 | String	| No		|	null	|	Set to off to disable this, any other string will be the default for the form
| page_css			|	public	 | String	| No		| 	null	| 	Set to off to disable this, any other string will be the default for the form
| page_js			|	public	 | String	| No		| 	null	| 	Set to off to disable this, any other string will be the default for the form
| status_equals		|	public	 | String	| No		|	null	|	set to the property key which will be controlling your publish status
| start_date		|	public	 | String	| No		| 	null	|	set this to the property key to which will be containing this value
| end_date			|	public	 | String	| No		| 	null	|	set this to the property key to which will be containing this value
| comment_equals	|	public	 | String	| No		| 	null	|	set this to the property key to which will be containing this value

| Static Method  	|	Params	| Required	| Description 	|
|	---				|	---		|	---		|	---			|
| about				|	none	|	Yes		|	this hold teh about information on the asset	|
| route				| DatastorePages $page, $route | No	| this will allow you to set the route for the public url |
| valueEquals		|	$value	|	No		|	This allows you to format the value for display on the frontend, value passed is the value from the record. |
| html				|	$data	|	Yes (property)	| Required for a property, returns the pre-formatted response for the asset type	|


### List of Available AMS Assets:
* BladeDirective `@forDatastores(true)` ... `@endforDatastores` returns grouped array (false) will return a single tree list.
* `Phpsa/Datastore/Helpers::getAssetList(true||false)` within php code


## Security

If you discover any security related issues, please email
instead of using the issue tracker.

## Credits

- [](https://github.com/phpsa/datastore)
- [All contributors](https://github.com/phpsa/datastore/graphs/contributors)

This package is bootstrapped with the help of
[melihovv/laravel-package-generator](https://github.com/melihovv/laravel-package-generator).
