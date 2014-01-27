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

class FileParser implements ParserInterface
{
    /**
     * @var FileManager
     */
    private $fileManager = null;
    /**
     * @var DependencyCondition
     */
    private $dependencyCondition = null;
    /**
     * @var EnvironnementCondition
     */
    private $environnementCondition = null;

    /**
     * @param FileManager $fileManager
     */
    public function __construct(FileManager $fileManager, DependencyCondition $dependencyCondition, EnvironnementCondition $environnementCondition)
    {
        $this->fileManager             = $fileManager;
        $this->dependencyCondition    = $dependencyCondition;
        $this->environnementCondition = $environnementCondition;
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\Generators\Parser\ParserInterface::evaluate()
     */
    public function evaluate(array $process, PhpStringParser $parser, GeneratorDataObject $generator, array $questions, $firstIteration)
    {
        $skeletonPath = dirname($generator->getPath()) . '/Skeleton/';

        if (!isset($process['filesList'])) {
			throw new MalformedGeneratorException('No file given');
        }

        foreach ($process['filesList'] as $files) {
        	if (!is_array($files)) {
        		throw new MalformedGeneratorException('File excepts to be an array "' . gettype($files) . "' given");
        	}

            $this->evaluateFile($files, $parser, $generator, $questions, $firstIteration, $skeletonPath);
        }

        return $generator;
    }

    private function evaluateFile(array $files, PhpStringParser $parser, GeneratorDataObject $generator, array $questions, $firstIteration, $skeletonPath)
    {
        foreach ($files as $templateName => $tragetFile) {
            if($templateName === GeneratorParser::ENVIRONNEMENT_CONDITION) {

                $matches = $this->environnementCondition->evaluate($tragetFile, $parser, $generator, $questions, $firstIteration);
                foreach ($matches as $matchesEnvironnement) {
                    $generator = $this->evaluateFile($matchesEnvironnement, $parser, $generator, $questions, $firstIteration, $skeletonPath);
                }
            } elseif($templateName === GeneratorParser::DEPENDENCY_CONDITION) {
                $matches = $this->dependencyCondition->evaluate($tragetFile, $parser, $generator, $questions, $firstIteration);

                foreach ($matches as $matchesDependency) {
                    $generator = $this->evaluateFile($matchesDependency, $parser, $generator, $questions, $firstIteration, $skeletonPath);
                }
            } else {
                $generator->addFile($skeletonPath, $templateName, $parser->parse($tragetFile));
            }
        }

        return $generator;
    }
}
