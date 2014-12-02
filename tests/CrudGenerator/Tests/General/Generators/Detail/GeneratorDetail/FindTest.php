<?php
namespace CrudGenerator\Tests\General\Generators\Detail\GeneratorDetail;

use CrudGenerator\Generators\Detail\GeneratorDetail;
use Packagist\Api\Result\Result;

class FindTest extends \PHPUnit_Framework_TestCase
{
    public function testFind()
    {
        $repo = $this->getMockBuilder('Github\Api\Repo')
        ->disableOriginalConstructor()
        ->getMock();
        $markdown = $this->getMockBuilder('Github\Api\Markdown')
        ->disableOriginalConstructor()
        ->getMock();

        $repo->expects($this->once())
        ->method('readme')
        ->willReturn(array('content' => 'test'));

        $markdown->expects($this->once())
        ->method('render');

        $suT = new GeneratorDetail($repo, $markdown);

        $dto = new Result();
        $dto->fromArray(array(
            'name' => 'test',
            'repository' => 'https://github.com/test/test',
        ));

        $results = $suT->find($dto);

        $this->assertTrue(array_key_exists('github', $results));
        $this->assertTrue(array_key_exists('readme', $results));
    }
}
