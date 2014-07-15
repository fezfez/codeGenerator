<?php

use CrudGenerator\Backbone\MainBackboneFactory;
use CrudGenerator\Context\WebContext;
use CrudGenerator\Generators\ResponseExpectedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app->match('/', function() use ($app) {
    return $app['twig']->render('index.html.twig');
})->bind('homepage');

$app->match('/generator', function (Request $request) use ($app) {
    $context = new WebContext($app);
    $main    = MainBackboneFactory::getInstance($context);

    try {
        $main->run();
    } catch (ResponseExpectedException $e) {
        $context->log("You may answer the question", "generator_question");
    }

    return $app->json($context, 200);
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
