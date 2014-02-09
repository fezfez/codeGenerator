<?php
namespace CrudGenerator\Tests\General\Generators\Parser\Lexical\Cli\QuestionParser;

use CrudGenerator\Generators\Parser\Lexical\Cli\AskQuestionParser;
use CrudGenerator\Utils\PhpStringParser;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Generators\Parser\GeneratorParser;
use CrudGenerator\Generators\Parser\Lexical\Condition\DependencyCondition;
use CrudGenerator\Context\CliContext;
use CrudGenerator\GeneratorsEmbed\ArchitectGenerator\Architect;

class EvaluateTest extends \PHPUnit_Framework_TestCase
{
    public function testMalformedVar()
    {
        $dependencyCondition =  $this->getMockBuilder('CrudGenerator\Generators\Parser\Lexical\Condition\DependencyCondition')
        ->disableOriginalConstructor()
        ->getMock();

        $directoryQuestion =  $this->getMockBuilder('CrudGenerator\Generators\Questions\Cli\DirectoryQuestion')
        ->disableOriginalConstructor()
        ->getMock();

        $ConsoleOutputStub =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
        ->disableOriginalConstructor()
        ->getMock();

        $dialog = $this->getMockBuilder('Symfony\Component\Console\Helper\DialogHelper')
        ->disableOriginalConstructor()
        ->getMock();

        $context = new CliContext($dialog, $ConsoleOutputStub);

        $phpParser =  $this->getMockBuilder('CrudGenerator\Utils\PhpStringParser')
        ->disableOriginalConstructor()
        ->getMock();

        $sUT = new AskQuestionParser($context, $directoryQuestion, $dependencyCondition);

        $generator = new GeneratorDataObject();

        $process = array(
            'questions' => array(
                'MyQuestion' => 'myQuestionValue'
            )
        );

        $this->setexpectedexception('CrudGenerator\Generators\Parser\Lexical\MalformedGeneratorException');
        $sUT->evaluate($process, $phpParser, $generator, array(), true);
    }

    public function testWithFiles()
    {
        $dependencyCondition =  $this->getMockBuilder('CrudGenerator\Generators\Parser\Lexical\Condition\DependencyCondition')
        ->disableOriginalConstructor()
        ->getMock();

        $directoryQuestion =  $this->getMockBuilder('CrudGenerator\Generators\Questions\Cli\DirectoryQuestion')
        ->disableOriginalConstructor()
        ->getMock();

        $ConsoleOutputStub =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
        ->disableOriginalConstructor()
        ->getMock();

        $dialog = $this->getMockBuilder('Symfony\Component\Console\Helper\DialogHelper')
        ->disableOriginalConstructor()
        ->getMock();

        $context = new CliContext($dialog, $ConsoleOutputStub);

        $phpParser =  $this->getMockBuilder('CrudGenerator\Utils\PhpStringParser')
        ->disableOriginalConstructor()
        ->getMock();

        $sUT       = new AskQuestionParser($context, $directoryQuestion, $dependencyCondition);
        $generator = new GeneratorDataObject();

        $process = array(
            'questions' => array(
                array(
                    'dtoAttribute'    => 'test',
                    'text'            => 'test',
                    'defaultResponse' => 'myDefaultResponse'
                )
            )
        );

        $this->assertEquals(
            $generator->addQuestion(
                array(
                    'dtoAttribute'    => 'setTest',
                    'text'            => 'test',
                    'value'           => '',
                    'defaultResponse' => 'myDefaultResponse'
                )
            ),
            $sUT->evaluate($process, $phpParser, $generator, array(), false)
        );
    }

    public function testWithDependencyCondiction()
    {
        $dependencyCondition =  $this->getMockBuilder('CrudGenerator\Generators\Parser\Lexical\Condition\DependencyCondition')
        ->disableOriginalConstructor()
        ->getMock();

        $directoryQuestion =  $this->getMockBuilder('CrudGenerator\Generators\Questions\Cli\DirectoryQuestion')
        ->disableOriginalConstructor()
        ->getMock();

        $ConsoleOutputStub =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
        ->disableOriginalConstructor()
        ->getMock();

        $dialog = $this->getMockBuilder('Symfony\Component\Console\Helper\DialogHelper')
        ->disableOriginalConstructor()
        ->getMock();

        $dialog->expects($this->once())
        ->method('ask')
        ->with($ConsoleOutputStub, '<question>MyTest</question> ', 'MyDefaultReponse')
        ->will($this->returnValue('myReponse'));

        $context = new CliContext($dialog, $ConsoleOutputStub);

        $phpParser =  $this->getMockBuilder('CrudGenerator\Utils\PhpStringParser')
        ->disableOriginalConstructor()
        ->getMock();

        $phpParser->expects($this->once())
        ->method('parse')
        ->with('MyDefaultReponse')
        ->will($this->returnValue('MyDefaultReponse'));

        $dependencyCondition->expects($this->once())
        ->method('evaluate')
        ->with(
            array(
                '!ArchitedGenerator' => array(
                    'dtoAttribute'    => 'Namespace',
                    'text'            => 'MyTest',
                    'defaultResponse' => 'MyDefaultReponse'
                )
            )
        )
        ->will(
            $this->returnValue(
                array(
                    array(
                        'dtoAttribute'    => 'Namespace',
                        'text'            => 'MyTest',
                        'defaultResponse' => 'MyDefaultReponse'
                    )
                )
            )
        );

        $sUT       = new AskQuestionParser($context, $directoryQuestion, $dependencyCondition);
        $generator = new GeneratorDataObject();
        $generator->setDTO(new Architect());

        new \CrudGenerator\Tests\General\Generators\Parser\Lexical\MyFakeQuestionFactory();

        $process = array(
            'questions' => array(
                array(
                    GeneratorParser::DEPENDENCY_CONDITION => array(
                        '!ArchitedGenerator' => array(
                            'dtoAttribute'    => 'Namespace',
                            'text'            => 'MyTest',
                            'defaultResponse' => 'MyDefaultReponse'
                        )
                    ),
                ),
                array(
                    'type'    => GeneratorParser::COMPLEX_QUESTION,
                    'factory' => 'CrudGenerator\Tests\General\Generators\Parser\Lexical\MyFakeQuestionFactory'
                )
            )
        );

        $result = $sUT->evaluate(
            $process,
            $phpParser,
            $generator,
            array(),
            false
        );

        $this->assertEquals(
            'myReponse',
            $result->getDTO()->getNamespace()
        );
    }

    /*public function testWithEnvironnemetnCondiction()
    {
        $fileManager =  $this->getMockBuilder('CrudGenerator\Utils\FileManager')
        ->disableOriginalConstructor()
        ->getMock();

        $environnementCondition =  $this->getMockBuilder('CrudGenerator\Generators\Parser\Lexical\Condition\EnvironnementCondition')
        ->disableOriginalConstructor()
        ->getMock();

        $dependencyCondition =  $this->getMockBuilder('CrudGenerator\Generators\Parser\Lexical\Condition\DependencyCondition')
        ->disableOriginalConstructor()
        ->getMock();

        $dependencyCondition->expects($this->once())
        ->method('evaluate')
        ->will($this->returnValue(array(array('MyVar' => 'MyValue'))));

        $phpParser =  $this->getMockBuilder('CrudGenerator\Utils\PhpStringParser')
        ->disableOriginalConstructor()
        ->getMock();


        $sUT = new TemplateVariableParser($fileManager, $environnementCondition, $dependencyCondition);

        $generator = new GeneratorDataObject();

        $process = array(
            'templateVariables' => array(
                array(
                    GeneratorParser::ENVIRONNEMENT_CONDITION => array(
                        '!ArchitedGenerator' => array(
                            array('MyVar' => 'MyValue')
                        )
                    )
                )
            )
        );

        $this->assertEquals(
                $generator->addTemplateVariable('MyVar', 'MyValue'),
                $sUT->evaluate($process, $phpParser, $generator, array(), true)
        );
    }*/
}
