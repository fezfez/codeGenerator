<?php
namespace CrudGenerator\Tests\PDO\MetaData\Sources\PDO\SqlManager;

use CrudGenerator\MetaData\Sources\PDO\SqlManager;

class GetAllPrimaryKeysTest extends \PHPUnit_Framework_TestCase
{
    public function testTrue()
    {
        $sUT = new SqlManager();

        $this->assertInternalType(
            'string',
            $sUT->getAllPrimaryKeys('pgsql')
        );
    }

    public function testFail()
    {
        $sUT = new SqlManager();

        $this->setExpectedException('RuntimeException');

        $sUT->getAllPrimaryKeys('dazdazd');
    }
}
