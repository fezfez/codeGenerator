<?php
namespace CrudGenerator\Tests\General\Context\CliContext;

use CrudGenerator\Context\CliContext;
use CrudGenerator\DataObject;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Tests\TestCase;

class PublishGeneratorTest extends TestCase
{
    public function testPublishingFailWithoutDto()
    {
        $outputStub        = $this->createMock('Symfony\Component\Console\Output\ConsoleOutput');
        $inputStub         = $this->createMock('Symfony\Component\Console\Input\ArrayInput');
        $questionHelper    = $this->createMock('Symfony\Component\Console\Helper\QuestionHelper');
        $createCommandMock = $this->createMock('CrudGenerator\Command\CreateCommand');

        $sUT = new CliContext($questionHelper, $outputStub, $inputStub, $createCommandMock);

        $this->setExpectedException('Exception');

        $sUT->publishGenerator(new GeneratorDataObject());
    }

    public function testPublishingOk()
    {
        $outputStub        = $this->createMock('Symfony\Component\Console\Output\ConsoleOutput');
        $inputStub         = $this->createMock('Symfony\Component\Console\Input\ArrayInput');
        $questionHelper    = $this->createMock('Symfony\Component\Console\Helper\QuestionHelper');
        $createCommandMock = $this->createMock('CrudGenerator\Command\CreateCommand');

        $sUT = new CliContext($questionHelper, $outputStub, $inputStub, $createCommandMock);

        $generatorDto = new GeneratorDataObject();
        $generatorDto->setDto(new DataObject());

        $this->assertEmpty($sUT->publishGenerator($generatorDto));
    }
}
