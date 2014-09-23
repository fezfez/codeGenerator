<?php
namespace CrudGenerator\Tests\General\Generators\Parser\Lexical\Condition\DependencyCondition;

use CrudGenerator\Generators\Parser\Lexical\Condition\ConditionValidatorFactory;

class GetInstanceTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $this->assertInstanceOf(
            'CrudGenerator\Generators\Parser\Lexical\Condition\ConditionValidator',
            ConditionValidatorFactory::getInstance()
        );
    }
}
