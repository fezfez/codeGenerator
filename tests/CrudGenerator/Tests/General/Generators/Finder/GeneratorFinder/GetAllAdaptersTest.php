<?php
namespace CrudGenerator\Tests\General\Generators\Finder\GeneratorFinder;

use CrudGenerator\Generators\Finder\GeneratorFinder;
use CrudGenerator\Tests\TestCase;

class GetAllAdaptersTest extends TestCase
{
    public function testType()
    {
        $rawMock = $this->createSut('CrudGenerator\Generators\Finder\GeneratorFinder');

        $fileFound = array(
            array(0 => 'fileName'),
            array(0 => 'fileName2'),
        );

        $fileManagerExpectsSearch = $rawMock['mocks']['fileManager']->expects($this->once());
        $fileManagerExpectsSearch->method('searchFileByRegex');
        $fileManagerExpectsSearch->will($this->returnValue($fileFound));

        $fileManagerExpectsGetContent = $rawMock['mocks']['fileManager']->expects($this->at(1));
        $fileManagerExpectsGetContent->method('fileGetContent');
        $fileManagerExpectsGetContent->with($fileFound[0][0]);
        $fileManagerExpectsGetContent->will($this->returnValue('valid'));

        $fileManagerExpectsGetContent = $rawMock['mocks']['fileManager']->expects($this->at(2));
        $fileManagerExpectsGetContent->method('fileGetContent');
        $fileManagerExpectsGetContent->with($fileFound[1][0]);
        $fileManagerExpectsGetContent->will($this->returnValue('notvalid'));

        $transtyperExpectsDecode = $rawMock['mocks']['transtyper']->expects($this->at(0));
        $transtyperExpectsDecode->method('decode');
        $transtyperExpectsDecode->with('valid');
        $transtyperExpectsDecode->will($this->returnValue(array('name' => 'valid')));

        $transtyperExpectsDecode = $rawMock['mocks']['transtyper']->expects($this->at(1));
        $transtyperExpectsDecode->method('decode');
        $transtyperExpectsDecode->with('notvalid');
        $transtyperExpectsDecode->will($this->returnValue(array('name' => 'notvalid')));

        $transtyperExpectsDecode = $rawMock['mocks']['generatorValidator']->expects($this->at(0));
        $transtyperExpectsDecode->method('isValid');
        $transtyperExpectsDecode->with(array('name' => 'valid'), null);

        $transtyperExpectsDecode = $rawMock['mocks']['generatorValidator']->expects($this->at(1));
        $transtyperExpectsDecode->method('isValid');
        $transtyperExpectsDecode->with(array('name' => 'notvalid'), null);
        $transtyperExpectsDecode->will($this->throwException(new \InvalidArgumentException()));

        /* @var $sUT \CrudGenerator\Generators\Finder\GeneratorFinder */
        $sUT = $rawMock['instance']($rawMock['mocks']);

        $results = $sUT->getAllClasses();

        $this->assertInternalType('array', $results);
        $this->assertCount(1, $results);

        $this->assertEquals(array($fileFound[0][0] => 'valid'), $results);
    }
}
