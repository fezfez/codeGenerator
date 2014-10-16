<?php
namespace CrudGenerator\Tests\General\GeneratorsEmbed\CrudGenerator\Parse;

use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Generators\Parser\GeneratorParserFactory;
use CrudGenerator\Generators\Strategies\GeneratorStrategyFactory;

class AnalyseTest extends \PHPUnit_Framework_TestCase
{
    public function testOk()
    {
         $context =  $this->getMockBuilder('CrudGenerator\Context\CliContext')
        ->disableOriginalConstructor()
        ->getMock();

        $generator = new GeneratorDataObject();
        $generator->setName('CrudGenerator');

        $generatorParser = GeneratorParserFactory::getInstance($context);

        $generator = $generatorParser->init(
            $generator,
            $this->getMetadata()
        );

        $generator->addEnvironnementValue('backend', 'PDO')
                  ->addEnvironnementValue('framework', 'zend_framework_2')
                  ->getDto()
                  ->setAttributeName('tetze', 'myName')
                  ->setAttributeName('myDate', 'madata');

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

    /**
     * @return \CrudGenerator\MetaData\DataObject\MetaData
     */
    private function getMetadata()
    {
        return include __DIR__ . '/../../FakeMetaData.php';
    }
}
