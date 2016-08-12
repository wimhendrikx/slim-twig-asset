# Asset Extension for Slim Framework Twig View

[![Build Status](https://api.travis-ci.org/careysizer/slim-twig-asset.svg?branch=master)](https://travis-ci.org/careysizer/slim-twig-asset)
[![Open Source Love](https://badges.frapsoft.com/os/mit/mit.svg?v=102)](https://github.com/careysizer/slim-twig-asset/blob/master/LICENSE.md)


This is a [Slim Framework](https://github.com/slimphp/Slim) view helper that provides support for [Symfony Assets](https://github.com/symfony/asset) in the [Slim Twig View](https://github.com/slimphp/Twig-View) extension.

## Install

Via [Composer](https://getcomposer.org/)

```bash
$ composer require careysizer/slim-twig-asset
```

Requires Slim Framework 3 and PHP 5.5.0 or newer.

## Usage

```php
/* @var $app \Slim\App */

$container = $app->getContainer();

/**
* Define default asset packages
*
* @param \Slim\Container $c
* @return \Symfony\Component\Asset\Packages
*/
$container['assetPackages'] = function ($c) {
    // You can define a "media domain" (for CDN etc) in your settings
    $basePath = $c->get('settings')['view']['media_domain'];
    return new \Symfony\Component\Asset\Packages(
        new \Symfony\Component\Asset\UrlPackage(
        $basePath . '/scripts/',
        // Choose the version strategy that works for your purposes
        new \Symfony\Component\Asset\VersionStrategy\StaticVersionStrategy(
            $c->get('settings')['view']['asset_version']
        )
    ),
    [
        'image' => new \Symfony\Component\Asset\UrlPackage(
            $basePath . '/img/',
            new \Symfony\Component\Asset\VersionStrategy\StaticVersionStrategy(
                    $c->get('settings')['view']['asset_version']
                )
            )
        ]
    );
};

/**
* Register Twig View helper
*/
$container['view'] = function ($c) {
    $view = new \Slim\Views\Twig('path/to/templates');
}

/**
* Add the Slim Twig Extension
*
* @param \Slim\Container $c
* @return \Slim\Views\TwigExtension
*/
$container['slimTwigExtension'] = function ($c) {
    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $c['request']->getUri()->getBasePath()), '/');
    return new Slim\Views\TwigExtension($c['router'], $basePath);
};

/**
* Add the asset twig extension
*
* @param \Slim\Container $c
* @return \Carey\Twig\Extension\Asset
*/
$container['assetTwigExtension'] = function ($c) {
    return new Carey\Twig\Extension\Asset(
        $c->get('assetPackages')
    );
};

/**
* Add the view (twig) with extensions
*
* @param \Slim\Container $c
* @return \Slim\Views\Twig
*/
$container['view'] = function ($c) {
    $view = new \Slim\Views\Twig($settings['template_path']);
    foreach (['slimTwigExtension', 'assetTwigExtension'] as $extension) {
        $view->addExtension($c->get($extension));
    }
    return $view;
};

```

## Custom template functions

This component exposes a custom `asset()` function to your Twig templates.

Example:
```
{% block head %}
    <link href="{{ asset('vendor/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet" />
{% endblock %}
```