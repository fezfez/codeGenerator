<?php
namespace CrudGenerator\Tests\General\Command\Questions\HistoryQuestion;

use CrudGenerator\Command\Questions\HistoryQuestion;
use CrudGenerator\History\HistoryCollection;
use CrudGenerator\History\History;
use CrudGenerator\Generators\ArchitectGenerator\Architect;

class AskTest extends \PHPUnit_Framework_TestCase
{
    public function testOk()
    {
        $ConsoleOutputStub =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
        ->disableOriginalConstructor()
        ->getMock();
        $ConsoleOutputStub->expects($this->any())
        ->method('writeln')
        ->will($this->returnValue(''));

        $dialog = $this->getMockBuilder('Symfony\Component\Console\Helper\DialogHelper', array('select'))
        ->disableOriginalConstructor()
        ->getMock();

        $dialog->expects($this->once())
        ->method('select')
        ->will($this->returnValue(0));

        $dto = new Architect();
        $HistoryCollection = new HistoryCollection();
        $history = new History();
        $history->setName('MyEntity')
                ->addDataObject($dto);

        $HistoryCollection->append($history);

        $HistoryStub =  $this->getMockBuilder('CrudGenerator\History\HistoryManager')
        ->disableOriginalConstructor()
        ->getMock();
        $HistoryStub->expects($this->once())
        ->method('findAll')
        ->will($this->returnValue($HistoryCollection));

        $sUT = new HistoryQuestion($HistoryStub, $ConsoleOutputStub, $dialog);
        $this->assertEquals($dto, $sUT->ask());
    }

    public function testEmptyHistory()
    {
        $ConsoleOutputStub =  $this->getMockBuilder('Symfony\Component\Console\Output\ConsoleOutput')
        ->disableOriginalConstructor()
        ->getMock();
        $ConsoleOutputStub->expects($this->any())
        ->method('writeln')
        ->will($this->returnValue(''));

        $dialog = $this->getMockBuilder('Symfony\Component\Console\Helper\DialogHelper', array('select'))
        ->disableOriginalConstructor()
        ->getMock();

        $HistoryCollection = new HistoryCollection();

        $HistoryStub =  $this->getMockBuilder('CrudGenerator\History\HistoryManager')
        ->disableOriginalConstructor()
        ->getMock();
        $HistoryStub->expects($this->once())
        ->method('findAll')
        ->will($this->returnValue($HistoryCollection));

        $sUT = new HistoryQuestion($HistoryStub, $ConsoleOutputStub, $dialog);
        $this->setExpectedException('RuntimeException');
        $sUT->ask();
    }
}