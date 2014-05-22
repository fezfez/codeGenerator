<?php
namespace CrudGenerator\Tests\General\GeneratorsEmbed\CrudGenerator\Parse;

use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Generators\Parser\GeneratorParserFactory;
use CrudGenerator\Context\WebContext;
use CrudGenerator\Generators\Strategies\GeneratorStrategyFactory;

class AnalyseTest extends \PHPUnit_Framework_TestCase
{
    public function testOk()
    {
         $context =  $this->getMockBuilder('CrudGenerator\Context\WebContext')
        ->disableOriginalConstructor()
        ->getMock();

        $generator = new GeneratorDataObject();
        $generator->setName('CrudGenerator');

        $generatorParser = GeneratorParserFactory::getInstance($context);

        $generator = $generatorParser->init(
            $generator,
            $this->getMetadata()
        );

        $generator->getDTO()
                  ->setAttributeName('tetze', 'myName')
                  ->setAttributeName('myDate', 'madata')
                  ->addEnvironnementValue('backend', 'PDO')
                  ->addEnvironnementValue('framework', 'zend_framework_2');


        $fileGenerator = GeneratorStrategyFactory::getInstance($context);

        foreach ($generator->getFiles() as $file) {
            $this->assertInternalType(
                'string',
                 $fileGenerator->generateFile(
                     $generator->getTemplateVariables(),
                     $file['skeletonPath'],
                     $file['name'],
                     $file['fileName']
                 )
            );
        }
    }

    private function getMetadata()
    {
        return include __DIR__ . '/../../FakeMetaData.php';
    }
}
