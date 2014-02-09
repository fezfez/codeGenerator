<?php

use Silex\Provider\FormServiceProvider;
use Silex\Provider\HttpCacheServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use SilexAssetic\AsseticServiceProvider;
use Symfony\Component\Translation\Loader\YamlFileLoader;

/*
 * @var $app Silex\Application
 */

$app->register(new FormServiceProvider());
$app->register(new UrlGeneratorServiceProvider());
$app->register(new AsseticServiceProvider());

$app->register(new Silex\Provider\HttpCacheServiceProvider(), array(
		'http_cache.cache_dir' => __DIR__.'/cache/',
));

$app->register(new TranslationServiceProvider());
$app['translator'] = $app->share($app->extend('translator', function($translator, $app) {
	$translator->addLoader('yaml', new YamlFileLoader());

	$translator->addResource('yaml', __DIR__.'/../resources/locales/fr.yml', 'fr');

	return $translator;
}));

$app->register(new TwigServiceProvider(), array(
    'twig.options'        => array(
        'cache'            => isset($app['twig.options.cache']) ? $app['twig.options.cache'] : false,
        'strict_variables' => true
    ),
    'twig.form.templates' => array('form_div_layout.html.twig', 'common/form_div_layout.html.twig'),
    'twig.path'           => array(__DIR__ . '/../resources/views')
));

$app['twig']->addFunction(new \Twig_SimpleFunction('path', function($url) use ($app) {
	return $app['url_generator']->generate($url);
}));


if (isset($app['assetic.enabled']) && $app['assetic.enabled']) {
    $app->register(new AsseticServiceProvider(), array(
        'assetic.options' => array(
            'debug'            => $app['debug'],
            'auto_dump_assets' => $app['debug'],
        )
    ));

    $app['assetic.filter_manager'] = $app->share(
        $app->extend('assetic.filter_manager', function($fm, $app) {
            $fm->set('lessphp', new Assetic\Filter\LessphpFilter());

            return $fm;
        })
    );

    $app['assetic.asset_manager'] = $app->share(
        $app->extend('assetic.asset_manager', function($am, $app) {
            $am->set('styles', new Assetic\Asset\AssetCache(
                new Assetic\Asset\GlobAsset(
                    $app['assetic.input.path_to_css'],
                    array($app['assetic.filter_manager']->get('lessphp'))
                ),
                new Assetic\Cache\FilesystemCache($app['assetic.path_to_cache'])
            ));
            $am->get('styles')->setTargetPath($app['assetic.output.path_to_css']);

            $am->set('scripts', new Assetic\Asset\AssetCache(
                new Assetic\Asset\GlobAsset($app['assetic.input.path_to_js']),
                new Assetic\Cache\FilesystemCache($app['assetic.path_to_cache'])
            ));
            $am->get('scripts')->setTargetPath($app['assetic.output.path_to_js']);

            return $am;
        })
    );

}

$app->register(new Silex\Provider\DoctrineServiceProvider());

return $app;