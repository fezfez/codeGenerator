<?php
namespace CrudGenerator\Tests\General\Generators\Finder\GeneratorFinderCache;

use CrudGenerator\Generators\Finder\GeneratorFinder;
use CrudGenerator\Generators\Finder\GeneratorFinderCache;
use CrudGenerator\Tests\TestCase;
use CrudGenerator\Utils\Installer;

class GetAllAdaptersTest extends TestCase
{
    public function testWithCacheExist()
    {
        $finder      = $this->createMock('CrudGenerator\Generators\Finder\GeneratorFinder');
        $directories = Installer::getDirectories();
        $fileManager = $this->createMock('CrudGenerator\Utils\FileManager');
        $noCache     = false;

        $fileManagerExpectsIsFile = $fileManager->expects($this->once());
        $fileManagerExpectsIsFile->method('isFile');
        $fileManagerExpectsIsFile->will($this->returnValue(true));

        $fileManagerExpectsGetContent = $fileManager->expects($this->once());
        $fileManagerExpectsGetContent->method('fileGetContent');
        $fileManagerExpectsGetContent->will($this->returnValue(serialize('valid')));

        $sUT = new GeneratorFinderCache($finder, $directories, $fileManager, $noCache);

        $this->assertEquals('valid', $sUT->getAllClasses());
    }

    public function testWithCacheExistAndNoCacheTrue()
    {
        $finder       = $this->createMock('CrudGenerator\Generators\Finder\GeneratorFinder');
        $directories  = Installer::getDirectories();
        $fileManager  = $this->createMock('CrudGenerator\Utils\FileManager');
        $noCache      = true;
        $returnedData = 'returned';

        $fileManagerExpectsIsFile = $fileManager->expects($this->once());
        $fileManagerExpectsIsFile->method('isFile');
        $fileManagerExpectsIsFile->will($this->returnValue(true));

        $fileManagerExpectsGetContent = $fileManager->expects($this->never());
        $fileManagerExpectsGetContent->method('fileGetContent');

        $finderExpectsGetAllClasses = $finder->expects($this->once());
        $finderExpectsGetAllClasses->method('getAllClasses');
        $finderExpectsGetAllClasses->will($this->returnValue($returnedData));

        $fileManagerExpectsPutContent = $fileManager->expects($this->once());
        $fileManagerExpectsPutContent->method('filePutsContent');

        $sUT = new GeneratorFinderCache($finder, $directories, $fileManager, $noCache);

        $this->assertEquals($returnedData, $sUT->getAllClasses());
    }

    public function testWithoutCache()
    {
        $finder       = $this->createMock('CrudGenerator\Generators\Finder\GeneratorFinder');
        $directories  = Installer::getDirectories();
        $fileManager  = $this->createMock('CrudGenerator\Utils\FileManager');
        $noCache      = false;
        $returnedData = 'returned';

        $fileManagerExpectsIsFile = $fileManager->expects($this->once());
        $fileManagerExpectsIsFile->method('isFile');
        $fileManagerExpectsIsFile->will($this->returnValue(false));

        $fileManagerExpectsGetContent = $fileManager->expects($this->never());
        $fileManagerExpectsGetContent->method('fileGetContent');

        $finderExpectsGetAllClasses = $finder->expects($this->once());
        $finderExpectsGetAllClasses->method('getAllClasses');
        $finderExpectsGetAllClasses->will($this->returnValue($returnedData));

        $fileManagerExpectsPutContent = $fileManager->expects($this->once());
        $fileManagerExpectsPutContent->method('filePutsContent');

        $sUT = new GeneratorFinderCache($finder, $directories, $fileManager, $noCache);

        $this->assertEquals($returnedData, $sUT->getAllClasses());
    }

    public function testWithoutCacheAndNoCacheTrue()
    {
        $finder       = $this->createMock('CrudGenerator\Generators\Finder\GeneratorFinder');
        $directories  = Installer::getDirectories();
        $fileManager  = $this->createMock('CrudGenerator\Utils\FileManager');
        $noCache      = true;
        $returnedData = 'returned';

        $fileManagerExpectsIsFile = $fileManager->expects($this->once());
        $fileManagerExpectsIsFile->method('isFile');
        $fileManagerExpectsIsFile->will($this->returnValue(false));

        $fileManagerExpectsGetContent = $fileManager->expects($this->never());
        $fileManagerExpectsGetContent->method('fileGetContent');

        $finderExpectsGetAllClasses = $finder->expects($this->once());
        $finderExpectsGetAllClasses->method('getAllClasses');
        $finderExpectsGetAllClasses->will($this->returnValue($returnedData));

        $fileManagerExpectsPutContent = $fileManager->expects($this->once());
        $fileManagerExpectsPutContent->method('filePutsContent');

        $sUT = new GeneratorFinderCache($finder, $directories, $fileManager, $noCache);

        $this->assertEquals($returnedData, $sUT->getAllClasses());
    }
}
