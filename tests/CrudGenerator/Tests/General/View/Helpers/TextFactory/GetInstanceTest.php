<?php
namespace CrudGenerator\Tests\General\View\Helpers\TextFactory;

use CrudGenerator\View\Helpers\TextFactory;
use CrudGenerator\Generators\ArchitectGenerator\Architect;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
    	$dto = new Architect();

    	$this->assertInstanceOf(
    		'CrudGenerator\View\Helpers\Text',
    		TextFactory::getInstance($dto)
		);

    }
}
