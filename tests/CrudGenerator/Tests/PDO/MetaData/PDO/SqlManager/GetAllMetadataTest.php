<?php
namespace CrudGenerator\Tests\PDO\MetaData\Sources\PDO\SqlManager;

use CrudGenerator\MetaData\Sources\PDO\SqlManager;

class GetAllMetadataTest extends \PHPUnit_Framework_TestCase
{
    public function testTrue()
    {
        $sUT = new SqlManager();

        $this->assertInternalType(
            'string',
            $sUT->getAllMetadata('pgsql')
        );
    }

    public function testFail()
    {
        $sUT = new SqlManager();

        $this->setExpectedException('RuntimeException');

        $sUT->getAllMetadata('dazdazd');
    }
}
