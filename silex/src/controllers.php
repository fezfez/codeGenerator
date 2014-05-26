<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use CrudGenerator\MetaData\Config\ConfigException;
use CrudGenerator\Generators\Questions\MetaDataSourcesQuestionFactory;
use CrudGenerator\Generators\GeneratorFactory;
use CrudGenerator\Generators\Strategies\GeneratorStrategyFactory;
use CrudGenerator\Context\WebContext;
use CrudGenerator\Generators\GeneratorWebConflictException;
use CrudGenerator\MetaData\Config\MetaDataConfigDAOFactory;
use CrudGenerator\Backbone\PreapreForGenerationBackboneFactory;
use CrudGenerator\Generators\ResponseExpectedException;

$app->match('/', function() use ($app) {
    return $app['twig']->render('index.html.twig');
})->bind('homepage');

// on choice metadata
$app->match('/adapter-form', function (Request $request) use ($app) {
    $context        = new WebContext($app);
    $backendFinder  = MetaDataSourcesQuestionFactory::getInstance($context);

    try {
        $metadataSource = $backendFinder->ask();

        $metaDataConfigDAO = MetaDataConfigDAOFactory::getInstance($context);
        $metaDataConfigDAO->ask($metadataSource->getConfig());
    } catch (ResponseExpectedException $e) {
        return $app->json($context);
    }

    return $app->json($context);
})->bind('adapter-form');

$app->match('metadata-save', function (Request $request) use ($app) {

    $context               = new WebContext($app);
    $backendFinder         = MetaDataSourcesQuestionFactory::getInstance($context);
    $backendSelect         = $backendFinder->ask();

    $configReader = MetaDataConfigDAOFactory::getInstance($context);
    try {
        $metadataSourceConfigured = $configReader->save($backendSelect->getConfig());
        return $app->json(array('valid' => true), 201);
    } catch (ConfigException $e) {
        return $app->json(array('error' => $e->getMessage()), 201);
    }
})->bind('metadata-save');

$mergeJsonSerealise = function($one, $two) {
    $oneArray = (array) json_decode(json_encode($one));
    $twoArray = (array) json_decode(json_encode($two));

    return array_merge($oneArray, $twoArray);
};

// on choice metadata
$app->match('/metadata', function (Request $request) use ($app, $mergeJsonSerealise) {
    $context           = new WebContext($app);
    $prepareGeneration = PreapreForGenerationBackboneFactory::getInstance($context);

    try {
        $generator = $prepareGeneration->run();
    } catch (ResponseExpectedException $e) {
        return $app->json($context, 201);
    }

    return $app->json($mergeJsonSerealise($context, $generator), 201);
})->bind('metadata');

$app->match('view-file', function (Request $request) use ($app) {

    $context           = new WebContext($app);
    $file              = $request->request->get('file');
    $prepareGeneration = PreapreForGenerationBackboneFactory::getInstance($context);
    $generatorEngine   = GeneratorFactory::getInstance($context, GeneratorStrategyFactory::getInstance($context));

    try {
        $generator    = $prepareGeneration->run();
        $fileGenerate = $generatorEngine->generate($generator, $file);
    } catch (ResponseExpectedException $e) {
        return $app->json($context, 201);
    }

    return $app->json(array('generator' => $fileGenerate), 201);
});

$app->match('generate', function (Request $request) use ($app) {

    $conflictArray  = (array) $request->request->get('conflict');
    $context        = new WebContext($app);

    $prepareGeneration = PreapreForGenerationBackboneFactory::getInstance($context);
    $generatorEngine   = GeneratorFactory::getInstance($context, GeneratorStrategyFactory::getInstance($context));

    try {
        $generator = $prepareGeneration->run();
        if(!empty($conflictArray)) {
            $generator = $generatorEngine->handleConflict($generator, $conflictArray);
        }
        $generationLog = $generatorEngine->generate($generator);
        $toReturn = array('generationLog' => $generationLog);
    } catch (\CrudGenerator\Generators\GeneratorWebConflictException $e) {
        $conflict = $generatorEngine->checkConflict($generator);
        $toReturn = array('conflict' => $conflict);
    } catch (ResponseExpectedException $e) {
        return $app->json($context, 201);
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
