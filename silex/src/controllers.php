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
use CrudGenerator\Backbone\MainBackbone;
use CrudGenerator\Backbone\MainBackboneFactory;

$app->match('/', function() use ($app) {
    return $app['twig']->render('index.html.twig');
})->bind('homepage');

// on choice metadata
$app->match('/generator', function (Request $request) use ($app) {
    $context = new WebContext($app);
    $main    = MainBackboneFactory::getInstance($context);

    try {
        $main->run();
    } catch (ResponseExpectedException $e) {

    }

    return $app->json($context, 201);
})->bind('generator');

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
