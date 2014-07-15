<?php
namespace CrudGenerator\Tests\General\GeneratorsEmbed\FormGenerator\Parse;

use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Generators\Parser\GeneratorParserFactory;
use CrudGenerator\Context\WebContext;
use CrudGenerator\Generators\Strategies\GeneratorStrategyFactory;

class AnalyseTest extends \PHPUnit_Framework_TestCase
{
    public function testOkfezfez()
    {
         $context =  $this->getMockBuilder('CrudGenerator\Context\WebContext')
        ->disableOriginalConstructor()
        ->getMock();


        $generator = new GeneratorDataObject();
        $generator->setName('FormGenerator');

        $generatorParser = GeneratorParserFactory::getInstance($context);

        $generator = $generatorParser->init($generator, $this->getMetadata());

        $generator->getDTO()->setAttributeName('tetze', 'myName')
        ->setAttributeName('myDate', 'madata')
        ->setAttributeName('name', 'name');

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
