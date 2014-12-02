<?php
namespace CrudGenerator\Tests\General\Generators\Parser\Lexical\Condition\ConditionValidator;

use CrudGenerator\Generators\Parser\Lexical\Condition\ConditionValidator;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Generators\Parser\Lexical\Condition\ConditionInterface;
use CrudGenerator\Generators\Parser\Lexical\Condition\DependencyCondition;
use CrudGenerator\Generators\Parser\Lexical\Condition\EnvironnementCondition;
use CrudGenerator\Generators\Parser\Lexical\Condition\SimpleCondition;

class IsValidTest extends \PHPUnit_Framework_TestCase
{
    public function testWithoutCondition()
    {
        $dependencyCondition = $this->getMockBuilder(
            'CrudGenerator\Generators\Parser\Lexical\Condition\DependencyCondition'
        )
        ->disableOriginalConstructor()
        ->getMock();

        $dependencyCondition->expects($this->never())
        ->method('isValid');

        $environnementCondition = $this->getMockBuilder(
            'CrudGenerator\Generators\Parser\Lexical\Condition\EnvironnementCondition'
        )
        ->disableOriginalConstructor()
        ->getMock();

        $environnementCondition->expects($this->never())
        ->method('isValid');

        $simpleCondition = $this->getMockBuilder(
            'CrudGenerator\Generators\Parser\Lexical\Condition\SimpleCondition'
        )
        ->disableOriginalConstructor()
        ->getMock();

        $simpleCondition->expects($this->never())
        ->method('isValid');

        $phpStringParser = $this->getMockBuilder(
            'CrudGenerator\Utils\PhpStringParser'
        )
        ->disableOriginalConstructor()
        ->getMock();

        $sUT = new ConditionValidator(array($dependencyCondition, $environnementCondition, $simpleCondition));

        $this->assertEquals(
            true,
            $sUT->isValid(array(), new GeneratorDataObject(), $phpStringParser)
        );

        $this->assertEquals(
            true,
            $sUT->isValid(array(ConditionInterface::CONDITION), new GeneratorDataObject(), $phpStringParser)
        );
    }

    public function testWithDependenciesCondition()
    {
        $dependencyCondition = $this->getMockBuilder(
            'CrudGenerator\Generators\Parser\Lexical\Condition\DependencyCondition'
        )
        ->disableOriginalConstructor()
        ->getMock();

        $dependencyCondition->expects($this->once())
        ->method('isValid');

        $environnementCondition = $this->getMockBuilder(
            'CrudGenerator\Generators\Parser\Lexical\Condition\EnvironnementCondition'
        )
        ->disableOriginalConstructor()
        ->getMock();

        $environnementCondition->expects($this->never())
        ->method('isValid');

        $simpleCondition = $this->getMockBuilder(
            'CrudGenerator\Generators\Parser\Lexical\Condition\SimpleCondition'
        )
        ->disableOriginalConstructor()
        ->getMock();

        $simpleCondition->expects($this->never())
        ->method('isValid');

        $phpStringParser = $this->getMockBuilder(
            'CrudGenerator\Utils\PhpStringParser'
        )
        ->disableOriginalConstructor()
        ->getMock();

        $sUT = new ConditionValidator(array($dependencyCondition, $environnementCondition, $simpleCondition));

        $this->assertEquals(
            true,
            $sUT->isValid(
                array(
                    ConditionInterface::CONDITION => array(
                        DependencyCondition::NAME => array(),
                    ),
                ),
                new GeneratorDataObject(),
                $phpStringParser
            )
        );
    }

    public function testWithEnvironnementCondition()
    {
        $dependencyCondition = $this->getMockBuilder(
            'CrudGenerator\Generators\Parser\Lexical\Condition\DependencyCondition'
        )
        ->disableOriginalConstructor()
        ->getMock();

        $dependencyCondition->expects($this->never())
        ->method('isValid');

        $environnementCondition = $this->getMockBuilder(
            'CrudGenerator\Generators\Parser\Lexical\Condition\EnvironnementCondition'
        )
        ->disableOriginalConstructor()
        ->getMock();

        $environnementCondition->expects($this->once())
        ->method('isValid');

        $simpleCondition = $this->getMockBuilder(
            'CrudGenerator\Generators\Parser\Lexical\Condition\SimpleCondition'
        )
        ->disableOriginalConstructor()
        ->getMock();

        $simpleCondition->expects($this->never())
        ->method('isValid');

        $phpStringParser = $this->getMockBuilder(
            'CrudGenerator\Utils\PhpStringParser'
        )
        ->disableOriginalConstructor()
        ->getMock();

        $sUT = new ConditionValidator(array($dependencyCondition, $environnementCondition, $simpleCondition));

        $this->assertEquals(
            true,
            $sUT->isValid(
                array(
                    ConditionInterface::CONDITION => array(
                        EnvironnementCondition::NAME => array(),
                    ),
                ),
                new GeneratorDataObject(),
                $phpStringParser
            )
        );
    }

    public function testWithSimpleCondition()
    {
        $dependencyCondition = $this->getMockBuilder(
            'CrudGenerator\Generators\Parser\Lexical\Condition\DependencyCondition'
        )
        ->disableOriginalConstructor()
        ->getMock();

        $dependencyCondition->expects($this->never())
        ->method('isValid');

        $environnementCondition = $this->getMockBuilder(
            'CrudGenerator\Generators\Parser\Lexical\Condition\EnvironnementCondition'
        )
        ->disableOriginalConstructor()
        ->getMock();

        $environnementCondition->expects($this->never())
        ->method('isValid');

        $simpleCondition = $this->getMockBuilder(
            'CrudGenerator\Generators\Parser\Lexical\Condition\SimpleCondition'
        )
        ->disableOriginalConstructor()
        ->getMock();

        $simpleCondition->expects($this->once())
        ->method('isValid');

        $phpStringParser = $this->getMockBuilder(
            'CrudGenerator\Utils\PhpStringParser'
        )
        ->disableOriginalConstructor()
        ->getMock();

        $sUT = new ConditionValidator(array($dependencyCondition, $environnementCondition, $simpleCondition));

        $this->assertEquals(
            true,
            $sUT->isValid(
                array(
                    ConditionInterface::CONDITION => array(
                        SimpleCondition::NAME => array(),
                    ),
                ),
                new GeneratorDataObject(),
                $phpStringParser
            )
        );
    }
}
