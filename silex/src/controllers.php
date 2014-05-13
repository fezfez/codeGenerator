<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use CrudGenerator\MetaData\MetaDataSourceFactory;
use CrudGenerator\MetaData\Config\ConfigException;
use CrudGenerator\Generators\Questions\MetaDataSourcesQuestionFactory;
use CrudGenerator\Generators\Questions\MetaDataSourcesConfiguredQuestionFactory;
use CrudGenerator\Generators\Questions\MetaDataQuestionFactory;
use CrudGenerator\Generators\Questions\GeneratorQuestionFactory;
use CrudGenerator\Generators\Parser\GeneratorParserFactory;
use CrudGenerator\Generators\GeneratorFactory;
use CrudGenerator\Generators\Strategies\GeneratorStrategyFactory;
use CrudGenerator\Context\WebContext;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Generators\GeneratorWebConflictException;
use CrudGenerator\MetaData\MetaDataSourceFinderFactory;
use CrudGenerator\MetaData\Config\MetaDataConfigDAO;
use CrudGenerator\MetaData\Config\MetaDataConfigDAOFactory;

$app->match('/', function() use ($app) {
    return $app['twig']->render('index.html.twig');
})->bind('homepage');

// on choice metadata
$app->match('/adapter-form', function (Request $request) use ($app) {
    $context        = new WebContext($app);
    $backendFinder  = MetaDataSourcesQuestionFactory::getInstance($context);

    try {
    	$metadataSource = $backendFinder->ask();
    } catch (InvalidArgumentException $e) {
    	return $app->json($context);
    }

    $metaDataConfigDAO = MetaDataConfigDAOFactory::getInstance($context);
    $metaDataConfigDAO->ask($metadataSource->getConfig());

    return $app->json($context);
})->bind('adapter-form');

$app->match('metadata-save', function (Request $request) use ($app) {

    $context               = new WebContext($app);
    $backendFinder         = MetaDataSourcesQuestionFactory::getInstance($context);
    $backendSelect         = $backendFinder->retrieve();

    $configReader = MetaDataConfigDAOFactory::getInstance($context);
    try {
        $metadataSourceConfigured = $configReader->save($backendSelect->getConfig());
        return $app->json(array('valid' => true), 201);
    } catch (ConfigException $e) {
        return $app->json(array('error' => $e->getMessage()), 201);
    }
})->bind('metadata-save');

// on choice metadata
$app->match('/metadata', function (Request $request) use ($app) {
    $context         = new WebContext($app);
    $questionArray   = (array) $request->request->get('questions');

    $backendFinder   = MetaDataSourcesConfiguredQuestionFactory::getInstance($context);
    $metadataFinder  = MetaDataQuestionFactory::getInstance($context);
    $generatorFinder = GeneratorQuestionFactory::getInstance($context);
    $generatorParser = GeneratorParserFactory::getInstance($context);

    try {
        $metadataSource = $backendFinder->ask();
        $metadata       = $metadataFinder->ask($metadataSource);
        $generatorPath  = $generatorFinder->ask();

        $generator = new GeneratorDataObject();
        $generator->setName($generatorPath);

        $generator = $generatorParser->init($generator, $metadata, $questionArray);

    } catch (InvalidArgumentException $e) {
        return $app->json($context, 201);
    }

    return $app->json(array('generator' => $generator), 201);
})->bind('metadata');

$app->match('view-file', function (Request $request) use ($app) {

    $context         = new WebContext($app);
    $questionArray   = (array) $request->request->get('questions');
    $file            = $request->request->get('file');

    $backendFinder     = MetaDataSourcesConfiguredQuestionFactory::getInstance($context);
    $metadataFinder    = MetaDataQuestionFactory::getInstance($context);
    $generatorFinder   = GeneratorQuestionFactory::getInstance($context);
    $generatorParser   = GeneratorParserFactory::getInstance($context);
    $generatorStrategy = GeneratorStrategyFactory::getInstance($context);
    $generatorEngine   = GeneratorFactory::getInstance($context, $generatorStrategy);

    try {
        $metadataSource = $backendFinder->ask();
        $metadata       = $metadataFinder->ask($metadataSource);
        $generatorPath  = $generatorFinder->ask();

        $generator = new GeneratorDataObject();
        $generator->setName($generatorPath);

        $generator = $generatorParser->init($generator, $metadata, $questionArray);
        $fileGenerate = $generatorEngine->generate($generator, $file);

    } catch (InvalidArgumentException $e) {
        return $app->json($context, 201);
    }

    return $app->json(array('generator' => $fileGenerate), 201);
});

$app->match('generate', function (Request $request) use ($app) {

    $questionArray  = (array) $request->request->get('questions');
    $conflictArray  = (array) $request->request->get('conflict');
    $context        = new WebContext($app);

    $backendFinder          = MetaDataSourcesConfiguredQuestionFactory::getInstance($context);
    $metadataFinder         = MetaDataQuestionFactory::getInstance($context);
    $generatorFinder        = GeneratorQuestionFactory::getInstance($context);
    $generatorParser        = GeneratorParserFactory::getInstance($context);
    $generatorStrategy      = GeneratorStrategyFactory::getInstance($context);
    $generatorEngine        = GeneratorFactory::getInstance($context, $generatorStrategy);

    $metadataSource = $backendFinder->ask();
    $metadata       = $metadataFinder->ask($metadataSource);
    $generatorPath  = $generatorFinder->ask();

    $generator = new GeneratorDataObject();
    $generator->setName($generatorPath);

    $generator = $generatorParser->init($generator, $metadata, $questionArray);

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
