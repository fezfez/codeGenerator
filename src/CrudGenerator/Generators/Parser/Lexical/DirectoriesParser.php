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

use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Utils\PhpStringParser;

class DirectoriesParser implements ParserInterface
{
    /* (non-PHPdoc)
     * @see \CrudGenerator\Generators\Parser\Lexical\ParserInterface::evaluate()
     */
    public function evaluate(array $process, PhpStringParser $parser, GeneratorDataObject $generator, $firstIteration)
    {
        if (isset($process['directories']) === true && is_array($process['directories']) === true) {
            foreach ($process['directories'] as $directory) {
                if (is_string($directory) === false) {
                    throw new MalformedGeneratorException(
                        sprintf('Directory excepts to be an string "%s" given', gettype($directory))
                    );
                }

                $generator->addDirectories($directory, $parser->parse($directory));
            }
        }

        return $generator;
    }
}
