<?php

use CrudGenerator\Backbone\MainBackboneFactory;
use CrudGenerator\Context\WebContext;
use CrudGenerator\Generators\ResponseExpectedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Igorw\EventSource\Stream;

$app->match('/', function() use ($app) {
    return $app['twig']->render('index.html.twig');
})->bind('homepage');

$app->match('/generator', function (Request $request) use ($app) {
    $stream = $request->get('stream');
    $stream = (($stream === 'true') ? true : false);

    $runner = function () use($stream, $request) {
        $event   = (($stream === true) ? new Stream() : null);
        $context = new WebContext($request, $event);
        $main    = MainBackboneFactory::getInstance($context);

        try {
            $main->run();
        } catch (ResponseExpectedException $e) {
            $context->log(
                sprintf(
                    "You may answer the question %s %s %s",
                    $e->getMessage(),
                    $e->getFile(),
                    $e->getLine()
                ),
                "generator_question"
            );
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

    if ($stream === true) {
        return $app->stream($runner, 200, Stream::getHeaders());
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
