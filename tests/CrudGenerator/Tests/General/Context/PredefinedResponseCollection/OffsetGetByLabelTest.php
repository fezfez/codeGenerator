<?php
namespace CrudGenerator\Tests\General\Context\PredefinedResponseCollection;

use CrudGenerator\Context\PredefinedResponseCollection;
use CrudGenerator\Tests\TestCase;
use CrudGenerator\Context\PredefinedResponse;

class OffsetGetByLabelTest extends TestCase
{
    public function testOk()
    {
        $sUT = new PredefinedResponseCollection();

        $sUT->append(new PredefinedResponse('id', 'label', 'response'));

        $this->assertEquals(new PredefinedResponse('id', 'label', 'response'), $sUT->offsetGetByLabel('label'));
    }

    public function testFail()
    {
        $sUT = new PredefinedResponseCollection();

        $this->setExpectedException('CrudGenerator\Context\PredefinedResponseException');

        $sUT->offsetGetByLabel('id');
    }
}
