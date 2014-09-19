<?php
/**
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */
namespace CrudGenerator\Generators\Parser\Lexical;

use CrudGenerator\Utils\FileManager;
use CrudGenerator\Utils\PhpStringParser;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Generators\Parser\GeneratorParser;
use CrudGenerator\Generators\Parser\Lexical\Condition\DependencyCondition;
use CrudGenerator\Generators\Parser\Lexical\Condition\EnvironnementCondition;
use CrudGenerator\Generators\Parser\Lexical\MalformedGeneratorException;
use CrudGenerator\Generators\Parser\Lexical\Condition\ConditionValidator;

class FileParser implements ParserInterface
{
    /**
     * @var FileManager
     */
    private $fileManager = null;
    /**
     * @var ConditionValidator
     */
    private $conditionValidator = null;

    /**
     * @param FileManager $fileManager
     * @param ConditionValidator $conditionValidator
     */
    public function __construct(
        FileManager $fileManager,
        ConditionValidator $conditionValidator
    ) {
        $this->fileManager        = $fileManager;
        $this->conditionValidator = $conditionValidator;
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\Generators\Parser\ParserInterface::evaluate()
     */
    public function evaluate(array $process, PhpStringParser $parser, GeneratorDataObject $generator, $firstIteration)
    {
        $skeletonPath = $generator->getPath() . '/Skeleton/';

        if (false === $this->fileManager->isDir($skeletonPath)) {
            throw new MalformedGeneratorException(
                sprintf('The Skeleton path "%s" is not a valid directory', $skeletonPath)
            );
        }

        if (false === isset($process['filesList'])) {
            throw new MalformedGeneratorException('No file given');
        }

        foreach ($process['filesList'] as $file) {
            if (false === is_array($file)) {
                throw new MalformedGeneratorException(
                    sprintf('File excepts to be an array "%s" given', gettype($file))
                );
            }

            if ($this->conditionValidator->isValid($file, $generator) === true) {
                $this->evaluateFile($file, $parser, $generator, (bool) $firstIteration, $skeletonPath);
            }
        }

        return $generator;
    }

    /**
     * @param array $files
     * @param PhpStringParser $parser
     * @param GeneratorDataObject $generator
     * @param boolean $firstIteration
     * @param string $skeletonPath
     * @return GeneratorDataObject
     */
    private function evaluateFile(
        array $file,
        PhpStringParser $parser,
        GeneratorDataObject $generator,
        $firstIteration,
        $skeletonPath
    ) {
        return $generator->addFile(
            $skeletonPath,
            $file['templatePath'],
            $parser->parse($file['destinationPath'])
        );
    }
}
