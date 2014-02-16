<?php

use Silex\Provider\HttpCacheServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;

/*
 * @var $app Silex\Application
 */

$app->register(new UrlGeneratorServiceProvider());

$app->register(new Silex\Provider\HttpCacheServiceProvider(), array(
		'http_cache.cache_dir' => __DIR__.'/cache/',
));

$app->register(new TwigServiceProvider(), array(
    'twig.options'        => array(
        'cache'            => isset($app['twig.options.cache']) ? $app['twig.options.cache'] : false,
        'strict_variables' => true
    ),
    'twig.path'           => array(__DIR__ . '/../resources/views')
));

$app['twig']->addFunction(new \Twig_SimpleFunction('path', function($url) use ($app) {
	return $app['url_generator']->generate($url);
}));


return $app;