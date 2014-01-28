<?php

$app['debug'] = true;
// Local
$app['locale'] = 'fr';
$app['session.default_locale'] = $app['locale'];

if (strstr($_SERVER['SCRIPT_FILENAME'], 'crud-generator-web.php')) {
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

// Assetic
$app['assetic.enabled']              = false;
$app['assetic.path_to_cache']        = $app['cache.path'] . '/assetic' ;
$app['assetic.path_to_web']          = __DIR__ . '/../../../web/assets';
$app['assetic.input.path_to_assets'] = __DIR__ . '/../../../vendor/twitter/bootstrap/';

$app['assetic.input.path_to_css']       = $app['assetic.input.path_to_assets'] . '/less/*.less';
$app['assetic.output.path_to_css']      = 'css/styles.css';
$app['assetic.input.path_to_js']        = array(
	$app['assetic.input.path_to_assets'] . '/js/tooltip.js',
	$app['assetic.input.path_to_assets'] . '/js/popover.js',
    $app['assetic.input.path_to_assets'] . '/js/modal.js',
	$app['assetic.input.path_to_assets'] . '/js/transition.js',
          $app['assetic.input.path_to_assets'] . '/js/alert.js',
          $app['assetic.input.path_to_assets'] . '/js/button.js',
          $app['assetic.input.path_to_assets'] . '/js/carousel.js',
          $app['assetic.input.path_to_assets'] . '/js/collapse.js',
          $app['assetic.input.path_to_assets'] . '/js/dropdown.js',
          $app['assetic.input.path_to_assets'] . '/js/modal.js',
          $app['assetic.input.path_to_assets'] . '/js/tooltip.js',
          $app['assetic.input.path_to_assets'] . '/js/popover.js',
          $app['assetic.input.path_to_assets'] . '/js/scrollspy.js',
          $app['assetic.input.path_to_assets'] . '/js/tab.js',
          $app['assetic.input.path_to_assets'] . '/js/affix.js'
);

$app['assetic.output.path_to_js']       = 'js/scripts.js';

// Doctrine (db)
$app['db.options'] = array(
    'driver'   => 'pdo_mysql',
    'host'     => 'localhost',
    'dbname'   => 'silex_kitchen',
    'user'     => 'root',
    'password' => '',
);
