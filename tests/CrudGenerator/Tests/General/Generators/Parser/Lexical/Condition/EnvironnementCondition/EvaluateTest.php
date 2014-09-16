<?php
namespace CrudGenerator\Tests\General\Generators\Parser\Lexical\Condition\EnvironnementCondition;

use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Generators\Parser\Lexical\Condition\EnvironnementCondition;
use Symfony\Component\Yaml\Yaml;
use CrudGenerator\DataObject;

class EvaluateTest extends \PHPUnit_Framework_TestCase
{
    public function testEquals()
    {
        $phpParser =  $this->getMockBuilder('CrudGenerator\Utils\PhpStringParser')
        ->disableOriginalConstructor()
        ->getMock();

        $sUT       = new EnvironnementCondition();
        $generator = new GeneratorDataObject();
        $generator->setDTO(new DataObject());
        $generator->addEnvironnementValue('backend', 'pdo');

        $string = '
questions :
    - environnementCondition :
        - backend == pdo :
            - /form/DataObject.phtml        : <?php $formGenerator->getFormPath(); ?>DataObject.phtml';

        $result = Yaml::parse($string, true);

        foreach ($result['questions'] as  $files) {
            foreach ($files as $templateName => $tragetFile) {
                if ($templateName === 'environnementCondition') {
                    $this->assertEquals(
                        array(
                            array(
                                '/form/DataObject.phtml' => '<?php $formGenerator->getFormPath(); ?>DataObject.phtml'
                            )
                        ),
                        $sUT->evaluate($tragetFile, $phpParser, $generator, true)
                    );
                }
            }
        }
    }

    public function testDifferent()
    {
        $phpParser =  $this->getMockBuilder('CrudGenerator\Utils\PhpStringParser')
        ->disableOriginalConstructor()
        ->getMock();

        $sUT       = new EnvironnementCondition();
        $generator = new GeneratorDataObject();
        $generator->setDTO(new DataObject());
        $generator->addEnvironnementValue('backend', 'test');

        $string = '
questions :
    - environnementCondition :
        - backend != pdo :
            - /form/DataObject.phtml        : <?php $formGenerator->getFormPath(); ?>DataObject.phtml';

        $result = Yaml::parse($string, true);

        foreach ($result['questions'] as  $files) {
            foreach ($files as $templateName => $tragetFile) {
                if ($templateName === 'environnementCondition') {
                    $this->assertEquals(
                        array(
                            array(
                                '/form/DataObject.phtml' => '<?php $formGenerator->getFormPath(); ?>DataObject.phtml'
                            )
                        ),
                        $sUT->evaluate($tragetFile, $phpParser, $generator, true)
                    );
                }
            }
        }
    }

    public function testNotCatchDifferent()
    {
        $phpParser =  $this->getMockBuilder('CrudGenerator\Utils\PhpStringParser')
        ->disableOriginalConstructor()
        ->getMock();

        $sUT       = new EnvironnementCondition();
        $generator = new GeneratorDataObject();
        $generator->setDTO(new DataObject());
        $generator->addEnvironnementValue('backend', 'pdo');

        $string = '
questions :
    - environnementCondition :
        - backend != pdo :
            - /form/DataObject.phtml        : <?php $formGenerator->getFormPath(); ?>DataObject.phtml';

        $result = Yaml::parse($string, true);

        foreach ($result['questions'] as  $files) {
            foreach ($files as $templateName => $tragetFile) {
                if ($templateName === 'environnementCondition') {
                    $this->assertEquals(
                        array(),
                        $sUT->evaluate($tragetFile, $phpParser, $generator, true)
                    );
                }
            }
        }
    }

    public function testNotCatchEquals()
    {
        $phpParser =  $this->getMockBuilder('CrudGenerator\Utils\PhpStringParser')
        ->disableOriginalConstructor()
        ->getMock();

        $sUT       = new EnvironnementCondition();
        $generator = new GeneratorDataObject();
        $generator->setDTO(new DataObject());
        $generator->addEnvironnementValue('backend', 'test');

        $string = '
questions :
    - environnementCondition :
        - backend == pdo :
            - /form/DataObject.phtml        : <?php $formGenerator->getFormPath(); ?>DataObject.phtml';

        $result = Yaml::parse($string, true);

        foreach ($result['questions'] as  $files) {
            foreach ($files as $templateName => $tragetFile) {
                if ($templateName === 'environnementCondition') {
                    $this->assertEquals(
                        array(),
                        $sUT->evaluate($tragetFile, $phpParser, $generator, true)
                    );
                }
            }
        }
    }

    public function testMalformedExpression()
    {
        $phpParser =  $this->getMockBuilder('CrudGenerator\Utils\PhpStringParser')
        ->disableOriginalConstructor()
        ->getMock();

        $sUT       = new EnvironnementCondition();
        $generator = new GeneratorDataObject();
        $generator->setDTO(new DataObject());

        $string = '
questions :
    - environnementCondition :
        - backend  pdo :
            - /form/DataObject.phtml        : <?php $formGenerator->getFormPath(); ?>DataObject.phtml';

        $result = Yaml::parse($string, true);

        foreach ($result['questions'] as  $files) {
            foreach ($files as $templateName => $tragetFile) {
                if ($templateName === 'environnementCondition') {
                    $this->setExpectedException('InvalidArgumentException');
                    $sUT->evaluate($tragetFile, $phpParser, $generator, true);
                }
            }
        }
    }
}
