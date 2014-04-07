<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use CrudGenerator\MetaData\MetaDataSourceFactory;
use CrudGenerator\MetaData\Config\ConfigException;
use CrudGenerator\Generators\Questions\MetaDataSourcesQuestionFactory;
use CrudGenerator\Generators\Questions\MetaDataQuestionFactory;
use CrudGenerator\Generators\Questions\GeneratorQuestionFactory;
use CrudGenerator\Generators\Parser\GeneratorParserFactory;
use CrudGenerator\Generators\GeneratorFactory;
use CrudGenerator\Generators\Strategies\GeneratorStrategyFactory;
use CrudGenerator\Context\WebContext;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Generators\GeneratorWebConflictException;

/**
 * @param string $ressouce
 * @return array
 */
function urlToArray($ressouce) {
    parse_str($ressouce, $ressouceParsed);

    if (!is_array($ressouceParsed)) {
        $ressouceParsed = array();
    }

    return $ressouceParsed;
}

$app->match('/', function() use ($app) {
    return $app['twig']->render('index.html.twig');
})->bind('homepage');

$app->match('/list-backend', function() use ($app) {
    $context       = new WebContext($app);
    $backendFinder = MetaDataSourcesQuestionFactory::getInstance($context);

    return $app->json(array('backend' => $backendFinder->ask()), 201);
})->bind('list-backend');

$app->match('/list-generator', function() use ($app) {

    $context         = new WebContext($app);
    $generatorFinder = GeneratorQuestionFactory::getInstance($context);

    return $app->json(array('generators' => $generatorFinder->ask()), 201);
})->bind('list-generator');

// on choice metadata
$app->match('/metadata', function (Request $request) use ($app) {
    $backendString = $request->request->get('backend');

    $context        = new WebContext($app);
    $backendFinder  = MetaDataSourcesQuestionFactory::getInstance($context);
    $metadataFinder = MetaDataQuestionFactory::getInstance($context);

    $metadataSource = $backendFinder->ask($backendString);

    try {
        return $app->json(array('metadatas' => $metadataFinder->ask($metadataSource)), 201);
    } catch (\CrudGenerator\MetaData\Config\ConfigException $e) {
        $formReader = \CrudGenerator\MetaData\Config\MetaDataConfigReaderFormFactory::getInstance();

        return $app->json(
            array(
                'config' => $formReader->getForm($metadataSource->getConfig())
            )
        );
    }
})->bind('metadata');

$app->match('generator', function (Request $request) use ($app) {

    $questionArray  = urlToArray($request->request->get('questions'));
    $metadataSource = $request->request->get('backend');
    $metadata       = $request->request->get('metadata');
    $context        = new WebContext($app);

    $generatorParser        = GeneratorParserFactory::getInstance($context);
    $matadataSourceFinder   = MetaDataSourcesQuestionFactory::getInstance($context);
    $metadataFinder         = MetaDataQuestionFactory::getInstance($context);

    $metadataSourceSelect   = $matadataSourceFinder->ask($metadataSource);
    $metaData               = $metadataFinder->ask($metadataSourceSelect, $metadata);

    $generator = new GeneratorDataObject();
    $generator->setName($request->request->get('generator'));

    $generator = $generatorParser->init($generator, $metaData, $questionArray);

    return $app->json(array('generator' => $generator), 201);
});

$app->match('view-file', function (Request $request) use ($app) {

	$questionArray  = urlToArray($request->request->get('questions'));
    $metadataSource = $request->request->get('backend');
    $metadata       = $request->request->get('metadata');
    $file           = $request->request->get('file');
    $context        = new WebContext($app);

    $generatorParser        = GeneratorParserFactory::getInstance($context);
    $matadataSourceFinder   = MetaDataSourcesQuestionFactory::getInstance($context);
    $metadataFinder         = MetaDataQuestionFactory::getInstance($context);
    $generatorStrategy      = GeneratorStrategyFactory::getInstance($context);
    $generatorEngine        = GeneratorFactory::getInstance($context, $generatorStrategy);

    $metadataSourceSelect   = $matadataSourceFinder->ask($metadataSource);
    $metaData               = $metadataFinder->ask($metadataSourceSelect, $metadata);

    $generator = new GeneratorDataObject();
    $generator->setName($request->request->get('generator'));

    $generator    = $generatorParser->init($generator, $metaData, $questionArray);
    $fileGenerate = $generatorEngine->generate($generator, $file);

    return $app->json(array('generator' => $fileGenerate), 201);
});

$app->match('generate', function (Request $request) use ($app) {

	$questionArray  = urlToArray($request->request->get('questions'));
	$conflictArray  = urlToArray($request->request->get('conflict'));
    $metadataSource = $request->request->get('backend');
    $metadata       = $request->request->get('metadata');
    $file           = $request->request->get('file');
    $context        = new WebContext($app);

    $generatorParser        = GeneratorParserFactory::getInstance($context);
    $matadataSourceFinder   = MetaDataSourcesQuestionFactory::getInstance($context);
    $metadataFinder         = MetaDataQuestionFactory::getInstance($context);
    $generatorStrategy      = GeneratorStrategyFactory::getInstance($context);
    $generatorEngine        = GeneratorFactory::getInstance($context, $generatorStrategy);

    $metadataSourceSelect   = $matadataSourceFinder->ask($metadataSource);
    $metaData               = $metadataFinder->ask($metadataSourceSelect, $metadata);

    $generator = new GeneratorDataObject();
    $generator->setName($request->request->get('generator'));

    $generator    = $generatorParser->init($generator, $metaData, $questionArray);

    try {
        if(!empty($conflictArray)) {
            $generator = $generatorEngine->handleConflict($generator, $conflictArray);
        }
        $generationLog = $generatorEngine->generate($generator);
        $toReturn = array('generationLog' => $generationLog);
    } catch (\CrudGenerator\Generators\GeneratorWebConflictException $e) {
        $conflict = $generatorEngine->checkConflict($generator);
        $toReturn = array('conflict' => $conflict);
    }

    return $app->json($toReturn, 201);
});

$app->match('metadata-save', function (Request $request) use ($app) {

    $formDatas       = $request->request->get('form');
    $backendString   = isset($formDatas['Backend']) ? $formDatas['Backend'] : null;
    $context         = new WebContext($app);
    $backendFinder   = MetaDataSourcesQuestionFactory::getInstance($context);
    $backendSelect   = $backendFinder->ask($request->request->get('backend'));
    $metadataSourceFactory = new MetaDataSourceFactory();

    $configReader = CrudGenerator\MetaData\Config\MetaDataConfigReaderFormFactory::getInstance();
    try {
        $metadataSourceConfigured = $configReader->write($backendSelect->getConfig(), $formDatas);
        $configReader->isValid($metadataSourceConfigured);
        $metadataSourceFactory->create($backendSelect->getFactory(), $metadataSourceConfigured);
        return $app->json(array('valid' => true), 201);
    } catch (ConfigException $e) {
        return $app->json(array('error' => $e->getMessage()), 201);
    }


})->bind('metadata-save');

$app->error(function (\Exception $e, $code) use ($app) {
    $request = $app['request'];

    $datas = array(
        'error'   => $e,
        'code'    => $code,
        'request' => $request
    );

    if (0 === strpos($request->headers->get('Accept'), 'application/json')) {
        return $app->json(
            array(
                'error' => $app['twig']->render('404.html.twig', $datas)
            ),
            $code
        );
    } else {
        return new Response(
            $app['twig']->render('404layout.html.twig', $datas),
            $code
        );
    }
});

return $app;
