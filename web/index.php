<?php
use CrudGenerator\MetaData\Sources\Doctrine2\MetadataDataObjectDoctrine2;
use CrudGenerator\MetaData\DataObject\MetaDataColumn;
use CrudGenerator\MetaData\DataObject\MetaDataColumnCollection;
use CrudGenerator\MetaData\DataObject\MetaDataRelationColumn;
use CrudGenerator\MetaData\DataObject\MetaDataRelationCollection;

set_time_limit(0);
error_reporting(-1);
ini_set('memory_limit', '1G');

if (is_file(__DIR__ . '/../vendor/autoload.php')) {
    chdir(__DIR__ . '/../'); // standalone
} elseif (is_file(__DIR__ . '/../../../autoload.php')) {
    chdir(__DIR__ . '/../../../../'); // install with composer
} else {
    throw new RuntimeException('Error: vendor/autoload.php could not be found. Did you run php composer.phar install?');
}

include_once 'vendor/autoload.php';

Symfony\Component\Debug\Debug::enable();

$app = new Silex\Application();

require __DIR__.'/../silex/resources/config/prod.php';
require __DIR__.'/../silex/src/app.php';
require __DIR__.'/../silex/src/controllers.php';
/*
$architectGenerator = new CrudGenerator\GeneratorsEmbed\ArchitectGenerator\Architect();
$architectGenerator->setMetadata(new MetadataDataObjectDoctrine2(new MetaDataColumnCollection(), new MetaDataRelationCollection()));
$relationColumn = new MetaDataRelationColumn();
$relationColumn->setFullName('totototo');
$architectGenerator->getMetadata()->appendRelation($relationColumn);
$test = '$architectGenerator->getMetadata()->getRelationCollection()';

$phpInterpretStatic = function($test, $variableVariable) {
	$testExplode = explode('->', $test);

	$cleanMethodName = function($value) {
		return str_replace('()', '', $value);
	};

	$variableName = str_replace('$', '', $testExplode[0]);
	$method       = $cleanMethodName($testExplode[1]);
	$instance     = $variableVariable[$variableName]->$method();

	foreach ($testExplode as $key => $value) {
		if ($key === 0 || $key === 1) {
			continue;
		}

		if ($instance === null) {
			throw new \InvalidArgumentException(sprintf('method %s return null', $method));
		} else {
			$method = $cleanMethodName($value);
			$instance = $instance->$method();
		}
	}

	return $instance;
};

$instance = $phpInterpretStatic($test, array('architectGenerator' => $architectGenerator));


if (($instance instanceof Traversable) === false) {
	throw new \InvalidArgumentException(
		sprintf(
        	'The result of "%s" is not an instance of Traversable',
			$test
		)
	);
}

$questionRaw        = 'Attribute name for "%s", $iteration->getName()';
$questionRawExplode = explode(',', $questionRaw);
$questionText       = array_shift($questionRawExplode);
$questionVariable   = array_map('trim', $questionRawExplode);

foreach ($instance as $iteration) {
	$placeholder = array();
	foreach ($questionVariable as $question) {
		$placeholder[] = $phpInterpretStatic($question, array('iteration' => $iteration));
	}
	echo vsprintf($questionText, $placeholder);
}

exit;

*/
$app->run();
