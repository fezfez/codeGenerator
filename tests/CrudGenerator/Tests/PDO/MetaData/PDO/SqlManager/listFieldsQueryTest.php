<?php
namespace CrudGenerator\Tests\MetaData\PDO\SqlManager;

use CrudGenerator\MetaData\PDO\SqlManager;

class listFieldsQueryTest extends \PHPUnit_Framework_TestCase
{
    public function testTrue()
    {
        $sUT = new SqlManager();

        $this->assertInternalType(
            'string',
            $sUT->listFieldsQuery('pgsql')
        );
    }

    public function testFail()
    {
        $sUT = new SqlManager();

        $this->setExpectedException('RuntimeException');

        $sUT->listFieldsQuery('dazdazd');
    }
}

