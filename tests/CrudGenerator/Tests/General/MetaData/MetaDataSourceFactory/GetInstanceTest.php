<?php
namespace CrudGenerator\Tests\General\MetaData\MetaDataSourceFactory;

use CrudGenerator\MetaData\MetaDataSourceFactory;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testTypdzadaze()
    {
        $sUT = new MetaDataSourceFactory();

        $pdoStub = $this->getMockBuilder('CrudGenerator\MetaData\Sources\PDO\PDOConfig')
        ->disableOriginalConstructor()
        ->getMock();

        $pdoStub->expects($this->once())
        ->method('getType')
        ->will($this->throwException(new \InvalidArgumentException()));

        $this->setExpectedException('InvalidArgumentException');

        $this->assertInstanceOf(
            '\CrudGenerator\MetaData\Sources\PDO\PDOMetaDataDAOFactory',
            $sUT->create('\CrudGenerator\MetaData\Sources\PDO\PDOMetaDataDAOFactory', $pdoStub)
        );
    }
}
