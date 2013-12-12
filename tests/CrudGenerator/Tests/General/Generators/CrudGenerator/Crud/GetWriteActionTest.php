<?php
namespace CrudGenerator\Tests\General\Generators\CrudGenerator\Crud;

use CrudGenerator\Generators\CrudGenerator\Crud;

class GetWriteActionTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $sUT = new Crud();

        $sUT->setWriteAction(true);

        $this->assertEquals(true, $sUT->getWriteAction());
    }
}
