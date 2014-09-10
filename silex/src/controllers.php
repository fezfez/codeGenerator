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
    $stream  = $request->get('stream');

    $runner = function () use($stream, $request) {
        $event   = (($stream == 'true') ? new Igorw\EventSource\Stream() : null);
        $context = new WebContext($request, $event);
        $main    = MainBackboneFactory::getInstance($context);

        try {
            $main->run();
        } catch (ResponseExpectedException $e) {
            $context->log("You may answer the question " . $e->getMessage() . $e->getFile() . $e->getLine(), "generator_question");
        }

        if ($event !== null) {
            $event
            ->event()
            ->setEvent('end')
            ->setData('end')
            ->end()
            ->flush();
        }

        return $context;
    };

    if ($stream == 'true') {
        return $app->stream($runner, 200, \Igorw\EventSource\Stream::getHeaders());
    } else {
        return $app->json($runner(), 200);
    }
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
