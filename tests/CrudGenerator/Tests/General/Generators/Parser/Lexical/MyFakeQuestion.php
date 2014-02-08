<?php
namespace CrudGenerator\Tests\General\Generators\Parser\Lexical;

use CrudGenerator\Generators\GeneratorDataObject;

class MyFakeQuestion
{
    /**
     * @param GeneratorDataObject
     * @return GeneratorDataObject
     */
    public function ask(GeneratorDataObject $generator, $question)
    {
        return $generator;
    }
}
