<?php
namespace CrudGenerator\Tests\General\MetaData\MetaDataSourceFactory;

use CrudGenerator\MetaData\MetaDataSourceFactory;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testTypdzadaze()
    {
        $sUT = new MetaDataSourceFactory();

        chdir(__DIR__ . '/../../../ZF2');
        $this->assertInstanceOf(
            '\CrudGenerator\MetaData\Sources\Doctrine2\Doctrine2MetaDataDAO',
            $sUT->create('\CrudGenerator\MetaData\Sources\Doctrine2\Doctrine2MetaDataDAOFactory')
        );

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
