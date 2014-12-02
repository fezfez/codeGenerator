<?php
namespace CrudGenerator\Tests\General\Context\SimpleQuestion;

use CrudGenerator\Context\SimpleQuestion;
use CrudGenerator\Generators\Parser\Lexical\QuestionResponseTypeEnum;
use CrudGenerator\Tests\TestCase;

class GetterSetterTest extends TestCase
{
    public function testBoolean()
    {
        $sUT = new SimpleQuestion('My question', 'my uniquekey');

        $this->assertFalse($sUT->isConsumeResponse());
        $this->assertFalse($sUT->isRequired());
        $this->assertFalse($sUT->isShutdownWithoutResponse());

        $sUT->setConsumeResponse(true);
        $sUT->setRequired(true);
        $sUT->setShutdownWithoutResponse(true);

        $this->assertTrue($sUT->isConsumeResponse());
        $this->assertTrue($sUT->isRequired());
        $this->assertTrue($sUT->isShutdownWithoutResponse());

        $sUT->setConsumeResponse(false);
        $sUT->setRequired(false);
        $sUT->setShutdownWithoutResponse(false);

        $this->assertFalse($sUT->isConsumeResponse());
        $this->assertFalse($sUT->isRequired());
        $this->assertFalse($sUT->isShutdownWithoutResponse());
    }

    public function testString()
    {
        $sUT = new SimpleQuestion('My question', 'my uniquekey');

        $this->assertEmpty($sUT->getDefaultResponse());
        $this->assertEmpty($sUT->getHelpMessage());
        $this->assertEquals('My question', $sUT->getText());
        $this->assertEmpty($sUT->getType());
        $this->assertEquals('my uniquekey', $sUT->getUniqueKey());

        $sUT->setDefaultResponse('test');
        $sUT->setHelpMessage('toto');
        $sUT->setText('test');
        $sUT->setType('fez');
        $sUT->setUniqueKey('toto');
        $sUT->setResponseType(new QuestionResponseTypeEnum());

        $this->assertEquals('test', $sUT->getDefaultResponse());
        $this->assertEquals('toto', $sUT->getHelpMessage());
        $this->assertEquals('test', $sUT->getText());
        $this->assertEquals('fez', $sUT->getType());
        $this->assertEquals('toto', $sUT->getUniqueKey());
    }
}
