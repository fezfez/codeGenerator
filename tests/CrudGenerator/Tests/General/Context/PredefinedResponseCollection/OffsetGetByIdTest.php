<?php
namespace CrudGenerator\Tests\General\Context\PredefinedResponseCollection;

use CrudGenerator\Context\PredefinedResponse;
use CrudGenerator\Context\PredefinedResponseCollection;
use CrudGenerator\Tests\TestCase;

class OffsetGetByIdTest extends TestCase
{
    public function testOk()
    {
        $sUT = new PredefinedResponseCollection();

        $sUT->append(new PredefinedResponse('id', 'label', 'response'));

        $this->assertEquals(new PredefinedResponse('id', 'label', 'response'), $sUT->offsetGetById('id'));
    }

    public function testFail()
    {
        $sUT = new PredefinedResponseCollection();

        $this->setExpectedException('CrudGenerator\Context\PredefinedResponseException');

        $sUT->offsetGetById('id');
    }
}
