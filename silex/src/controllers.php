<?php

use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Yaml\Yaml;
use CrudGenerator\MetaData\MetaDataSourceFactory;
use CrudGenerator\MetaData\Config\MetaDataConfigReaderFactory;
use Symfony\Component\Console\Output\StreamOutput;
use Symfony\Component\Console\Helper\DialogHelper;
use CrudGenerator\MetaData\Config\ConfigException;

$app->match('/', function() use ($app) {
    return $app['twig']->render('index.html.twig');
})->bind('homepage');

$app->match('/list-backend', function() use ($app) {
	$backendFinder     = CrudGenerator\MetaData\MetaDataSourceFinderFactory::getInstance();
	$backendArray = array();
	foreach ($backendFinder->getAllAdapters() as $backend) {
		if(!$backend->getFalseDependencies()) {
			$backendArray[] = array('id' => $backend->getFactory(), 'label' => $backend->getDefinition());
		}
	}
	return $app->json(array('backend' => $backendArray), 201);
})->bind('list-backend');

$app->match('/list-generator', function() use ($app) {
    $generatorFinder = CrudGenerator\Generators\GeneratorFinderFactory::getInstance();
    $generatorArray = array();
    foreach ($generatorFinder->getAllClasses() as $path => $name) {
    	$generatorArray[] = array('id' => $path, 'label' => $name);
    }
	return $app->json(array('generators' => $generatorArray), 201);
})->bind('list-generator');

$backendChoice = function($backendString) {
    $backendFinder = CrudGenerator\MetaData\MetaDataSourceFinderFactory::getInstance();
    $backendSelect = null;
    foreach ($backendFinder->getAllAdapters() as $backend) {
        if($backend->getFactory() === $backendString) {
            $backendSelect = $backend;
        }
    }

    if (null === $backendSelect) {
        throw new InvalidArgumentException(sprintf('Invalid %s', $backendString));
    }

    return $backendSelect;
};

$metadataChoice = function($backendSelect) use($app) {
	$metadataSourceFactory = new MetaDataSourceFactory();
	$metadataSourceFactoryName = $backendSelect->getFactory();
	$metadataSourceConfig      = $backendSelect->getConfig();

	if (null !== $metadataSourceConfig) {
		$configReader = CrudGenerator\MetaData\Config\MetaDataConfigReaderFormFactory::getInstance($app);
		try {
			$metadataSourceConfigured = $configReader->config($metadataSourceConfig);
			$metaDataDAO = $metadataSourceFactory->create($metadataSourceFactoryName, $metadataSourceConfigured);
		} catch (ConfigException $e) {
			$form = $configReader->getForm($metadataSourceConfig);
			return $app->json(
					array(
							'config' =>
							$app['twig']->render(
									'metadata-config.html.twig',
									array(
											'form' => $form->createView()
									)
							)
					)
			);
		}
	} else {
		$metaDataDAO = $metadataSourceFactory->create($metadataSourceFactoryName);
	}

	$metaDataChoices = array();
	foreach ($metaDataDAO->getAllMetadata() as $metaData) {
		$metaDataChoices[$metaData->getOriginalName()] = $metaData;
	}

	return $metaDataChoices;
};

// on choice metadata
$app->match('/metadata', function (Request $request) use ($app, $backendChoice, $metadataChoice) {
    $backendString = $request->request->get('backend');

    $backendSelect = $backendChoice($backendString);
    $metaDataChoicesTmp = $metadataChoice($backendSelect);
    $metaDataChoices = array();
    foreach ($metaDataChoicesTmp as $name => $metaData) {
    	$metaDataChoices[] = array('id' => $name, 'label' => $name);
    }

    return $app->json(array('metadatas' => $metaDataChoices), 201);
})->bind('metadata');

$app->match('generator', function (Request $request) use ($app, $backendChoice, $metadataChoice) {

	$generatorParser = CrudGenerator\Generators\GeneratorParserFactory::getInstance();
	$backendSelect   = $backendChoice($request->request->get('backend'));
	$metaDataChoices = $metadataChoice($backendSelect);
	$metadata        = isset($metaDataChoices[$request->request->get('metadata')]) ? $metaDataChoices[$request->request->get('metadata')] : null;

	$generator = new CrudGenerator\Generators\Generator();
	$generator->setName($request->request->get('generator'));
	$generator = $generatorParser->init($generator);
	$dto = $generator->getDTO()->setMetaData($metadata);

	$questionString = $request->request->get('questions');
	if(!empty($questionString)) {
		$questionString = urldecode($questionString);
		$questions = explode('&', $questionString);
		foreach($questions as $question) {
			$questionValue = explode('=', $question);
			$questionName = $questionValue[0];
			$dto->$questionName($questionValue[1]);
		}
	}
	$generator->setDTO($dto);
	$generator = $generatorParser->analyse($generator);


	return $app->json(array('generator' => $generator), 201);
});

$app->match('view-file', function (Request $request) use ($app, $backendChoice, $metadataChoice) {

	$generatorParser = CrudGenerator\Generators\GeneratorParserFactory::getInstance();
	$backendSelect   = $backendChoice($request->request->get('backend'));
	$metaDataChoices = $metadataChoice($backendSelect);
	$metadata        = isset($metaDataChoices[$request->request->get('metadata')]) ? $metaDataChoices[$request->request->get('metadata')] : null;

	$generator = new CrudGenerator\Generators\Generator();
	$generator->setName($request->request->get('generator'));
	$generator = $generatorParser->init($generator);
	$dto = $generator->getDTO()->setMetaData($metadata);
	$questionString = $request->request->get('questions');
	if(!empty($questionString)) {
		$questionString = urldecode($questionString);
		$questions = explode('&', $questionString);
		foreach($questions as $question) {
			$questionValue = explode('=', $question);
			$questionName = $questionValue[0];
			$dto->$questionName($questionValue[1]);
		}
	}
	$generator->setDTO($dto);
	$generator = $generatorParser->analyse($generator);

	return $app->json(array('generator' => $generatorParser->viewFile($generator, $request->request->get('file'))), 201);
});

$app->match('metadata-save', function (Request $request) use ($app, $backendChoice) {

    $formDatas             = $request->request->get('form');
    $backendString         = isset($formDatas['Backend']) ? $formDatas['Backend'] : null;
    $backendSelect         = $backendChoice($backendString);
    $metadataSourceFactory = new MetaDataSourceFactory();

    $configReader = CrudGenerator\MetaData\Config\MetaDataConfigReaderFormFactory::getInstance($app);
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
    if ($app['debug']) {
        return;
    }

    var_dump($e);exit;

    switch ($code) {
        case 404:
            $message = 'The requested page could not be found.';
            break;
        default:
            $message = 'We are sorry, but something went terribly wrong.';
    }

    return new Response($message, $code);
});

return $app;