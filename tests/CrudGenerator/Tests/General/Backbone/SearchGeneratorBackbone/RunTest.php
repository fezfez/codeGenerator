<?php
namespace CrudGenerator\Tests\General\Backbone\SearchGeneratorBackbone;

use CrudGenerator\Backbone\SearchGeneratorBackbone;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Tests\TestCase;

class RunTest extends TestCase
{
    public function testViewDetailAndInstall()
    {
        $generatorSearch    = $this->createMock('CrudGenerator\Generators\Search\GeneratorSearch');
        $generatorInstaller = $this->createMock('CrudGenerator\Generators\Installer\GeneratorInstallerInterface');
        $generatorDetail    = $this->createMock('CrudGenerator\Generators\Detail\GeneratorDetail');
        $context            = $this->createMock('CrudGenerator\Context\CliContext');

        $contextExpects = $context->expects($this->exactly(2));
        $contextExpects->method('confirm');
        $contextExpects->willReturn(true);

        $generatorSearchExpects = $generatorSearch->expects($this->once());
        $generatorSearchExpects->method('ask');
        $generatorSearchExpects->willReturn(new \Packagist\Api\Result\Result());

        $generatorDetailExpects = $generatorDetail->expects($this->once());
        $generatorDetailExpects->method('find');;

        $generatorInstallerExpects = $generatorInstaller->expects($this->once());
        $generatorInstallerExpects->method('install');

        $sUT = new SearchGeneratorBackbone(
            $generatorSearch,
            $generatorInstaller,
            $generatorDetail,
            $context
        );

        $sUT->run();
    }
}
