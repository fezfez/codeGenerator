<?php
namespace CrudGenerator\Tests\General\MetaData\MetaDataSourceFactory;

use CrudGenerator\MetaData\MetaDataSourceFactory;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testTypdzadaze()
    {
        $sUT = new MetaDataSourceFactory();

        $PostgreSQLStub = $this->getMockBuilder('CrudGenerator\MetaData\Sources\PostgreSQL\PostgreSQLConfig')
        ->disableOriginalConstructor()
        ->getMock();

        $PostgreSQLStub->expects($this->once())
        ->method('getType')
        ->will($this->throwException(new \InvalidArgumentException()));

        $this->setExpectedException('InvalidArgumentException');

        $this->assertInstanceOf(
            '\CrudGenerator\MetaData\Sources\PostgreSQL\PostgreSQLMetaDataDAOFactory',
            $sUT->create('\CrudGenerator\MetaData\Sources\PostgreSQL\PostgreSQLMetaDataDAOFactory', $PostgreSQLStub)
        );
    }
}
