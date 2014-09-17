<?php
namespace CrudGenerator\Tests\General\Context\WebContext;

use CrudGenerator\Context\WebContext;
use CrudGenerator\Context\QuestionWithPredefinedResponse;
use CrudGenerator\Context\PredefinedResponseCollection;
use CrudGenerator\Context\PredefinedResponse;

class AskCollectionTest extends \PHPUnit_Framework_TestCase
{
    public function testJsonWellReturned()
    {
        $request = new \Symfony\Component\HttpFoundation\Request();

        $sUT = new WebContext($request);

        $responseCollection = new PredefinedResponseCollection();
        $responseCollection->append(
            new PredefinedResponse('key', 'value', 'value')
        );

        $question = new QuestionWithPredefinedResponse(
            "test",
            'my_key',
            $responseCollection
        );

        $this->assertEquals(
            null,
            $sUT->askCollection($question)
        );
        $this->assertEquals(
            '{"question":[{"text":"test","dtoAttribute":"my_key","defaultResponse":null' .
            ',"required":false,"values":[{"id":"key","label":"value"}],"type":"select"}]}',
            json_encode($sUT)
        );
    }
}
