<?php
namespace CrudGenerator\Tests\General\Generators\Parser\GeneratorParser;

use CrudGenerator\Generators\Parser\Lexical\DirectoriesParser;
use CrudGenerator\Utils\FileManager;
use CrudGenerator\Utils\PhpStringParser;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Generators\Parser\GeneratorParser;
use CrudGenerator\Generators\Parser\Lexical\Condition\EnvironnementCondition;
use CrudGenerator\Generators\Parser\Lexical\Condition\DependencyCondition;
use CrudGenerator\MetaData\Sources\PDO\MetadataDataObjectPDO;
use CrudGenerator\MetaData\DataObject\MetaDataColumn;
use CrudGenerator\MetaData\DataObject\MetaDataColumnCollection;
use CrudGenerator\MetaData\DataObject\MetaDataRelationColumn;
use CrudGenerator\MetaData\DataObject\MetaDataRelationCollection;

class InitTest extends \PHPUnit_Framework_TestCase
{
    public function testEmpty()
    {
    	$fileManager =  $this->getMockBuilder('CrudGenerator\Utils\FileManager')
    	->disableOriginalConstructor()
    	->getMock();

    	$yaml =  $this->getMockBuilder('Symfony\Component\Yaml\Yaml')
    	->disableOriginalConstructor()
    	->getMock();

    	$phpParser =  $this->getMockBuilder('CrudGenerator\Utils\PhpStringParser')
    	->disableOriginalConstructor()
    	->getMock();

    	$viewFile =  $this->getMockBuilder('CrudGenerator\Generators\Strategies\ViewFileStrategy')
    	->disableOriginalConstructor()
    	->getMock();

    	$generatorFinder =  $this->getMockBuilder('CrudGenerator\Generators\Finder\GeneratorFinder')
    	->disableOriginalConstructor()
    	->getMock();

    	$parserCollection =  new \CrudGenerator\Generators\Parser\ParserCollection();

    	$process = array(
    		'dto' => 'CrudGenerator\GeneratorsEmbed\ArchitectGenerator\Architect',
    		'name' => 'test'
    	);

    	$yaml::staticExpects($this->once())
    	->method('parse')
    	->will($this->returnValue($process));

    	$sUT       = new GeneratorParser($fileManager, $yaml, $phpParser, $viewFile, $generatorFinder, $parserCollection);
    	$generator = new GeneratorDataObject();
    	$metadata  = new MetadataDataObjectPDO(new MetaDataColumnCollection(), new MetaDataRelationCollection());

        $sUT->init($generator, $metadata);
    }
}
