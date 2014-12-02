<?php
namespace CrudGenerator\Tests\General\Generators\Finder\GeneratorFinder;

use CrudGenerator\Generators\Finder\GeneratorFinder;
use CrudGenerator\Tests\TestCase;

class FindByNameTest extends TestCase
{
    public function testFail()
    {
        $rawMock = $this->createSut('CrudGenerator\Generators\Finder\GeneratorFinder');

        $fileFound = array(
            array(0 => 'fileName2'),
        );

        $fileManagerExpectsSearch = $rawMock['mocks']['fileManager']->expects($this->once());
        $fileManagerExpectsSearch->method('searchFileByRegex');
        $fileManagerExpectsSearch->will($this->returnValue($fileFound));

        $fileManagerExpectsGetContent = $rawMock['mocks']['fileManager']->expects($this->once());
        $fileManagerExpectsGetContent->method('fileGetContent');
        $fileManagerExpectsGetContent->with($fileFound[0][0]);
        $fileManagerExpectsGetContent->will($this->returnValue('notvalid'));

        $transtyperExpectsDecode = $rawMock['mocks']['transtyper']->expects($this->once());
        $transtyperExpectsDecode->method('decode');
        $transtyperExpectsDecode->with('notvalid');
        $transtyperExpectsDecode->will($this->returnValue(array('name' => 'notvalid')));

        $transtyperExpectsDecode = $rawMock['mocks']['generatorValidator']->expects($this->once());
        $transtyperExpectsDecode->method('isValid');
        $transtyperExpectsDecode->with(array('name' => 'notvalid'), null);
        $transtyperExpectsDecode->will($this->throwException(new \InvalidArgumentException()));

        /* @var $sUT \CrudGenerator\Generators\Finder\GeneratorFinder */
        $sUT = $rawMock['instance']($rawMock['mocks']);

        $this->setExpectedException('InvalidArgumentException');

        $sUT->findByName('fail');
    }

    public function testOk()
    {
        $rawMock = $this->createSut('CrudGenerator\Generators\Finder\GeneratorFinder');

        $fileFound = array(
            array(0 => 'fileName'),
        );

        $fileManagerExpectsSearch = $rawMock['mocks']['fileManager']->expects($this->once());
        $fileManagerExpectsSearch->method('searchFileByRegex');
        $fileManagerExpectsSearch->will($this->returnValue($fileFound));

        $fileManagerExpectsGetContent = $rawMock['mocks']['fileManager']->expects($this->once());
        $fileManagerExpectsGetContent->method('fileGetContent');
        $fileManagerExpectsGetContent->with($fileFound[0][0]);
        $fileManagerExpectsGetContent->will($this->returnValue('valid'));

        $transtyperExpectsDecode = $rawMock['mocks']['transtyper']->expects($this->once());
        $transtyperExpectsDecode->method('decode');
        $transtyperExpectsDecode->with('valid');
        $transtyperExpectsDecode->will($this->returnValue(array('name' => 'ArchitectGenerator')));

        $transtyperExpectsDecode = $rawMock['mocks']['generatorValidator']->expects($this->once());
        $transtyperExpectsDecode->method('isValid');
        $transtyperExpectsDecode->with(array('name' => 'ArchitectGenerator'), null);

        /* @var $sUT \CrudGenerator\Generators\Finder\GeneratorFinder */
        $sUT = $rawMock['instance']($rawMock['mocks']);

        $this->assertEquals(
            'fileName',
            $sUT->findByName('ArchitectGenerator')
        );
    }
}
