<?php
namespace CrudGenerator\Tests\General\MetaData\Sources\PostgreSQL\SqlManager;

use CrudGenerator\MetaData\Sources\PostgreSQL\SqlManager;

/**
 * @requires extension pdo_pgsql
 */
class ListFieldsQueryTest extends \PHPUnit_Framework_TestCase
{
    public function testTrue()
    {
        $sUT = new SqlManager();

        $this->assertInternalType(
            'string',
            $sUT->listFieldsQuery()
        );
    }
}
