<?php
namespace CrudGenerator\Tests\PostgreSQL\MetaData\Sources\PostgreSQL\SqlManager;

use CrudGenerator\MetaData\Sources\PostgreSQL\SqlManager;

class GetAllPrimaryKeysTest extends \PHPUnit_Framework_TestCase
{
    public function testTrue()
    {
        $sUT = new SqlManager();

        $this->assertInternalType(
            'string',
            $sUT->getAllPrimaryKeys()
        );
    }
}
