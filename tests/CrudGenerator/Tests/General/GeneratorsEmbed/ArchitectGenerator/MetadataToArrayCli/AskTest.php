<?php
namespace CrudGenerator\Tests\General\GeneratorsEmbed\ArchitectGenerator\MetadataToArrayCli;

use CrudGenerator\GeneratorsEmbed\ArchitectGenerator\Architect;
use CrudGenerator\GeneratorsEmbed\ArchitectGenerator\MetadataToArrayCli;
use CrudGenerator\Generators\GeneratorDataObject;

class AskTest extends \PHPUnit_Framework_TestCase
{
    public function testOk()
    {
        $DTO = new Architect();
        $DTO->setEntity('TestZf2\Entities\NewsEntity')
        ->setMetadata($this->getMetadata())
        ->setNamespace('namespace')
        ->setGenerateUnitTest(true);

        $stubDialog = $this->getMock('\Symfony\Component\Console\Helper\DialogHelper');
        $stubDialog->expects($this->any())
        ->method('askAndValidate')
        ->will($this->returnValue(__DIR__));

        $stubDialog->expects($this->any())
        ->method('askConfirmation')
        ->will($this->returnValue(true));
        $stubDialog->expects($this->any())
        ->method('select')
        ->will($this->returnValue('retrieve'));

        $stubDialog->expects($this->any())
        ->method('ask')
        ->will($this->returnValue('maValeur'));
        $stubOutput =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
        ->disableOriginalConstructor()
        ->getMock();
        $stubOutput->expects($this->any())
        ->method('writeln')
        ->will($this->returnValue(''));

        $sUT = new MetadataToArrayCli($stubDialog, $stubOutput);

        $generator = new GeneratorDataObject();
        $generator->setDTO($DTO);

        $DTO = $sUT->ask($generator);

        $generator->getDTO()->getAttributeName();
    }

    private function getMetadata()
    {
        return include __DIR__ . '/../../FakeMetaData.php';
    }
}
