<?php
namespace CrudGenerator\Tests\General\MetaData\MetaDataSourceFactory;

use CrudGenerator\MetaData\MetaDataSourceFactory;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateInstance()
    {
        $sUT = new MetaDataSourceFactory();

        $PostgreSQLStub = $this->getMockBuilder('CrudGenerator\MetaData\Sources\PostgreSQL\PostgreSQLConfig')
        ->disableOriginalConstructor()
        ->getMock();

        $pdoStub = $this->getMock('CrudGenerator\Tests\General\MetaData\MetaDataSourceFactory\MockPDO');

        $PostgreSQLStub->expects($this->once())
        ->method('getConnection')
        ->will($this->returnValue($pdoStub));

        $this->assertInstanceOf(
            '\CrudGenerator\MetaData\Sources\MetaDataDAOCache',
            $sUT->create('\CrudGenerator\MetaData\Sources\PostgreSQL\PostgreSQLMetaDataDAOFactory', $PostgreSQLStub)
        );
    }
}
