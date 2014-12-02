<?php
namespace CrudGenerator\Tests\General\MetaData\Sources\MetadataDAOCache;

use CrudGenerator\Metadata\Sources\MetadataDAOCache;

class GetAllMetadataTest extends \PHPUnit_Framework_TestCase
{
    public function testWithCached()
    {
        $metadataDAO = $this->getMock('CrudGenerator\Metadata\Sources\MetaDataDAOInterface');
        $fileManager = $this->getMock('CrudGenerator\Utils\FileManager');

        $fileManager->expects($this->once())
        ->method('isFile')
        ->willReturn(true);

        $data = 'my data !';

        $fileManager->expects($this->once())
        ->method('fileGetContent')
        ->willReturn(serialize($data));

        $sUT = new MetadataDAOCache($metadataDAO, $fileManager);

        $this->assertEquals($data, $sUT->getAllMetadata());
    }

    public function testWithCachedAndNoCacheTrue()
    {
        $metadataDAO = $this->getMock('CrudGenerator\Metadata\Sources\MetaDataDAOInterface');
        $fileManager = $this->getMock('CrudGenerator\Utils\FileManager');

        $fileManager->expects($this->never())
        ->method('fileGetContent');

        $fileManager->expects($this->once())
        ->method('isFile')
        ->willReturn(true);

        $data = 'my data !';

        $metadataDAO->expects($this->once())
        ->method('getAllMetadata')
        ->willReturn($data);

        $fileManager->expects($this->once())
        ->method('filePutsContent')
        ->with($this->isType('string'), serialize($data));

        $sUT = new MetadataDAOCache($metadataDAO, $fileManager, null, true);

        $this->assertEquals($data, $sUT->getAllMetadata());
    }
}
