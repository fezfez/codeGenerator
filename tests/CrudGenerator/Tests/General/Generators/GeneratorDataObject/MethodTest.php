<?php
namespace CrudGenerator\Tests\General\Generators\GeneratorDataObject;

use CrudGenerator\Generators\GeneratorDataObject;

class MethodTest extends \PHPUnit_Framework_TestCase
{
    public function testMethods()
    {
        $sUT = new GeneratorDataObject();

        $questions = array('dtoAttribute' => 'test');

        $sUT->addQuestion($questions);

        $this->assertEquals(
            array($questions),
            $sUT->getQuestion()
        );

        $this->assertEquals(
            '{"templateVariable":[],"files":[],"directories":[],"name":null,"questions":[{"dtoAttribute":"test"}],"environnement":[]}',
            json_encode($sUT)
        );
    }
}
