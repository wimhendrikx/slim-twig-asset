# Asset Extension for Slim Framework Twig View

This is a Slim Framework view helper that provides support for Symfony Assets in the Slim Twig View extension.

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
$view = new \Slim\Views\Twig('path/to/templates', [
    'cache' => 'path/to/cache'
]);

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
    $settings = $c->get('settings')['view'];
    $twigSettings = [];
    if ($c->get('settings')['mode'] !== 'dev') {
        $twigSettings = ['cache' => $settings['cache_path']];
    }
    $view = new \Slim\Views\Twig($settings['template_path'], $twigSettings);
    $view->offsetSet('session', $c->get('session'));
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