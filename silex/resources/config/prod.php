<?php

$app['debug'] = false;
// Local
$app['locale'] = 'fr';
$app['session.default_locale'] = $app['locale'];

if (strstr($_SERVER['SCRIPT_FILENAME'], 'code-generator-web.php')) {
	$app['base_path'] = './../fezfez/code-generator/web/';
} else {
	$app['base_path'] = './';
}

// Cache
$app['cache.path'] = __DIR__ . '/../cache';

// Http cache
$app['http_cache.cache_dir'] = $app['cache.path'] . '/http';

// Twig cache
$app['twig.options.cache'] = $app['cache.path'] . '/twig';