<?php
namespace CrudGenerator\Tests\General\Context\PredefinedResponseCollection;

use CrudGenerator\Context\PredefinedResponse;
use CrudGenerator\Context\PredefinedResponseCollection;
use CrudGenerator\Tests\TestCase;

class AppendTest extends TestCase
{
    public function testAppend()
    {
        $sUT = new PredefinedResponseCollection();

        $sUT->append(new PredefinedResponse('id', 'label', 'response'));

        foreach ($sUT as $tmp) {
            $this->assertEquals(new PredefinedResponse('id', 'label', 'response'), $tmp);
        }
    }
}
