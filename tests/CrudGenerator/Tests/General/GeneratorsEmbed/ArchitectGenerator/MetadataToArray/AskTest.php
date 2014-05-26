<?php
namespace CrudGenerator\Tests\General\GeneratorsEmbed\ArchitectGenerator\MetadataToArray;

use CrudGenerator\GeneratorsEmbed\ArchitectGenerator\Architect;
use CrudGenerator\GeneratorsEmbed\ArchitectGenerator\MetadataToArray;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Context\WebContext;

class AskTest extends \PHPUnit_Framework_TestCase
{
    public function testOk()
    {
        $DTO = new Architect();
        $DTO->setMetadata($this->getMetadata())
        ->setNamespace('namespace');

    	$context =  $this->getMockBuilder('CrudGenerator\Context\WebContext')
    	->disableOriginalConstructor()
    	->getMock();

        $sUT = new MetadataToArray($context);

        $generator = new GeneratorDataObject();
        $generator->setDTO($DTO);

        $DTO = $sUT->ask($generator, array());
    }

    private function getMetadata()
    {
        return include __DIR__ . '/../../FakeMetaData.php';
    }
}
