<?php
/**
  * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Generators\Parser\Lexical;

use CrudGenerator\Utils\PhpStringParser;
use CrudGenerator\Generators\GeneratorDataObject;

interface ParserInterface
{
    /**
     * @param  array               $process
     * @param  PhpStringParser     $parser
     * @param  GeneratorDataObject $generator
     * @param  boolean             $firstIteration
     * @return GeneratorDataObject
     */
    public function evaluate(array $process, PhpStringParser $parser, GeneratorDataObject $generator, $firstIteration);
}
