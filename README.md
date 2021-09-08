# Real Estate Laravel - With Bootstrap 5

<p><a href="https://packagist.org/packages/jeffersonpereira/address" rel="nofollow noindex noopener external ugc"><img src="https://img.shields.io/static/v1?label=packagist&message=v1.3.0&color=blue&style=%3CSTYLE%3E&logo=%3CLOGO%3E" alt="Latest Version on Packagist"></a>
<a href="#" rel="nofollow noindex noopener external ugc"><img src="https://img.shields.io/static/v1?label=license&message=MIT&color=success&style=%3CSTYLE%3E&logo=%3CLOGO%3E" alt="Software License"></a>
</p>

## Description

Package used to manage real estate / brokerage websites, built with the Laravel framework

## Instalation

```php
composer require jeffersonpereira/realestatelaravel
```

After installing the package it is necessary to run the command to publish assets and the configuration file.

```bash
php artisan realestatelaravel:install
```

The package has migrations and you will need to run:

```bash
php artisan migrate
```

## _Importants Notes:_

You needed to add separately cdn links for bootstrap in your project.
We use bootstrap in version 5.1.

```html
<script
  src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ"
  crossorigin="anonymous"
></script>
```

```html
<link
  href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css"
  rel="stylesheet"
  integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU"
  crossorigin="anonymous"
/>
```

When using your own `template`, you will need to define a `section` of the view's content, the default is `content`, as well as two blade stacks ['cssrealestate', scriptsrealestate], and meta tag viewport

```html
<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU"
      crossorigin="anonymous"
    />
    @stack('cssrealestate')
  </head>

  <body>
    @yield('content')

    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min. js"
      integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ"
      crossorigin="anonymous"
    ></script>
    @stack('scriptsrealestate')
  </body>
</html>
```

##### We strongly advise using ​​environment variables, since when the package install command is executed, the configuration file will be overwritten.

# Features available in the package

## Property

The package is under development and will be added new features over time. At the moment the package has all the management of a property.

## Routes to views

Use the `Menu::class` class to get all the view routes to get started, when used your template.

```php
use Jeffpereira\RealEstate\Utilities\Helpers\RouteHelper;

$routes = RouteHelper::allView();
```

## License

Real Estate Laravel is an open source project, licensed by [MIT](https://opensource.org/licenses/MIT).
