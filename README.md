<p align="center">
<a href="https://github.com/jeffepereira/real-estate-laravel" target="_blank">
<img src="https://cdn.jsdelivr.net/gh/jeffepereira/real-estate-laravel@1/dist/img/logorealestate.png" width="150">
</a>
</p>

<p align="center">
<a href="https://packagist.org/packages/jeffersonpereira/realestatelaravel">
<img src="https://img.shields.io/packagist/dt/jeffersonpereira/realestatelaravel" alt="Total Downloads">
</a>
<a href="https://packagist.org/packages/jeffersonpereira/realestatelaravel">
<img src="https://img.shields.io/packagist/v/jeffersonpereira/realestatelaravel" alt="Latest Stable Version">
</a>
<a href="https://packagist.org/packages/jeffersonpereira/realestatelaravel">
<img src="https://img.shields.io/packagist/l/jeffersonpereira/realestatelaravel" alt="License">
</a>
</p>

## Description

Package used to manage real estate / brokerage websites, built with the Laravel framework

## Instalation

```php
composer require jeffersonpereira/realestatelaravel
```

After installing the package, it is necessary to run the command to publish the configuration file.

```bash
php artisan realestatelaravel:install
```

_Note:_

##### We strongly advise using ​​environment variables, since when the package install command is executed, the configuration file will be overwritten.

The package has migrations and you will need to run:

```bash
php artisan migrate
```

## Importants Notes:

When using your template, import assets with the directive blade and cdn to font-awesome.

_Obs: We use **bootstrap 5.1** on ours assets_

```html
<html>
  <head>
    ...
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
      integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    @realestatelaravelStyles
  </head>
  <body>
    ...
    <!-- in finishing body -->
    @realestatelaravelScripts
  </body>
</html>
```

## Features available in the package

#### Property

The package is under development and will be added new features over time. At the moment the package has all the management of a property.

#### Routes to views

Use the `Menu::class` class to get all the view routes to get started, when used your template.

```php
use Jeffpereira\RealEstate\Utilities\Helpers\RouteHelper;

$routes = RouteHelper::allView();
```

#### Config

Define as configs in your `.env`

```php
/**
*  Defines whether to use the package `template`
*  -   When set to false, you will need to define a `template` and
*      section_content for the content.
*  .env >> RE_USE_TEMPLATE
*/
'use_template' => true
/**
*  The name of the `template` used to extend the package view - .env >> RE_TEMPLATE
*/
'template' => 'template',
/**
*  Template content section name - .env >> RE_SECTION_CONTENT
*/
'section_content' => 'content',
```

More options, check the `config/realestatelaravel.php` config file

#### Events

- **Jeffpereira\RealEstate\Events\BusinessPropertyFinalizedEvent** - When the business of the property was finalized

## License

Real Estate Laravel is an open source project, licensed by [MIT](https://opensource.org/licenses/MIT).
