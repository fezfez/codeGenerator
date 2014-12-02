<?php
namespace CrudGenerator\Tests\General\Metadata\Sources\PostgreSQL\SqlManager;

use CrudGenerator\Metadata\Sources\PostgreSQL\SqlManager;

/**
 * @requires extension pdo_pgsql
 */
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
