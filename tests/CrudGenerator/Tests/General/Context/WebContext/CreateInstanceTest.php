<?php
namespace CrudGenerator\Tests\General\Context\WebContext;

use CrudGenerator\Context\WebContext;

class CreateInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
		$application = $this->getMockBuilder('Silex\Application')
		->disableOriginalConstructor()
		->getMock();

		$sUT = new WebContext($application);

		/*$this->assertInstanceOf(
			'Silex\Application',
			$sUT->getApplication()
		);*/
    }
}
