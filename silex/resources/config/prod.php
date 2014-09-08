<?php

$app['debug']                  = false;
$app['locale']                 = 'fr';
$app['session.default_locale'] = $app['locale'];

if (strstr($_SERVER['SCRIPT_FILENAME'], 'code-generator-web.php') !== false) {
    $app['base_path'] = './../fezfez/code-generator/web/';
} else {
    $app['base_path'] = './';
}

$app['cache.path']           = __DIR__ . '/../../var/cache'; // Cache
$app['http_cache.cache_dir'] = $app['cache.path'] . '/http'; // Http cache
$app['twig.options.cache']   = $app['cache.path'] . '/twig'; // Twig cache
