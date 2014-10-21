<?php
namespace CrudGenerator\Tests\General\MetaData\Config\MetaDataConfigDAO;

use CrudGenerator\MetaData\Config\MetaDataConfigDAO;
use CrudGenerator\Utils\ClassAwake;
use CrudGenerator\MetaData\MetaDataSourceHydrator;
use CrudGenerator\MetaData\MetaDataSource;
use CrudGenerator\MetaData\Sources\Json\JsonMetaDataDAOFactory;

class AskTest extends \PHPUnit_Framework_TestCase
{
    public function testWithJsonMetadataDAOSource()
    {
        $fileManager = $this->createMock('CrudGenerator\Utils\FileManager');
        $context     = $this->createMock('CrudGenerator\Context\CliContext');


        $sUT = new MetaDataConfigDAO(new ClassAwake(), $fileManager, new MetaDataSourceHydrator(), $context);

        $this->assertInstanceOf(
            'CrudGenerator\MetaData\Driver\DriverConfig',
            $sUT->ask(JsonMetaDataDAOFactory::getDescription())
        );
    }

    /**
     * @param string $class
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function createMock($class)
    {
        return $this->getMockBuilder($class)
        ->disableOriginalConstructor()
        ->getMock();
    }
}
