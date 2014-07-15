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
namespace CrudGenerator\Generators\Parser;

use CrudGenerator\Utils\Yaml;
use CrudGenerator\Utils\FileManager;
use CrudGenerator\Utils\PhpStringParser;
use CrudGenerator\MetaData\DataObject\MetaData;
use CrudGenerator\Generators\Strategies\GeneratorStrategy;
use CrudGenerator\Generators\Finder\GeneratorFinder;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Generators\Parser\ParserCollection;

/**
 * Find all generator allow in project
 *
 * @author Stéphane Demonchaux
 */
class GeneratorParser
{
    const COMPLEX_QUESTION = 'complex';
    const ENVIRONNEMENT_CONDITION = 'environnementCondition';
    const DEPENDENCY_CONDITION = 'dependencyCondition';
    const CONDITION_ELSE = 'else';
    const DIFFERENT = '!';
    const UNDEFINED = ' == undefined';
    const EQUAL = '==';
    const DIFFERENT_EQUAL = '!=';

    /**
     * @var Yaml YamlParser
     */
    private $yaml = null;
    /**
     * @var FileManager file manager
     */
    private $fileManager = null;
    /**
     * @var PhpStringParser PhpStringParser
     */
    private $phpStringParser = null;
    /**
     * @var ViewFileStategy
     */
    private $viewFile = null;
    /**
     * @var GeneratorFinder
     */
    private $generatorFinder = null;
    /**
     * @var ParserCollection
     */
    private $parserCollection = null;

    /**
     * @param FileManager $fileManager
     * @param Yaml $yaml
     * @param PhpStringParser $phpStringParser
     * @param GeneratorStrategy $viewFile
     * @param GeneratorFinder $generatorFinder
     * @param ParserCollection $parserCollection
     */
    public function __construct(
        FileManager $fileManager,
        Yaml $yaml,
        PhpStringParser $phpStringParser,
        GeneratorStrategy $viewFile,
        GeneratorFinder $generatorFinder,
        ParserCollection $parserCollection)
    {
        $this->fileManager      = $fileManager;
        $this->yaml             = $yaml;
        $this->phpStringParser  = $phpStringParser;
        $this->viewFile         = $viewFile;
        $this->generatorFinder  = $generatorFinder;
        $this->parserCollection = $parserCollection;
    }

    /**
     * @param GeneratorDataObject $generator
     * @param MetaData $metadata
     * @throws \InvalidArgumentException
     * @return GeneratorDataObject
     */
    public function init(GeneratorDataObject $generator, MetaData $metadata)
    {
        $generator = clone $generator;
        $phpParser = clone $this->phpStringParser;

        return $this->analyze($generator->getName(), $phpParser, $generator, $metadata, true);
    }

    /**
     * @param string $name Generator name
     * @param PhpStringParser $phpParser
     * @param GeneratorDataObject $generator
     * @param MetaData $metadata
     * @param boolean $firstIteration
     * @return GeneratorDataObject
     */
    private function analyze($name, PhpStringParser $phpParser, GeneratorDataObject $generator, MetaData $metadata, $firstIteration = false)
    {
        $generator         = clone $generator;
        $generatorFilePath = $this->generatorFinder->findByName($name);
        $process           = $this->yaml->parse($this->fileManager->fileGetContent($generatorFilePath));

        /* @var $dto \CrudGenerator\DataObject */
        $dto = new $process['dto']();
        $dto->setMetadata($metadata);

        $generator->setDTO($dto)
                  ->setPath($generatorFilePath);

        $generator = $this->analyzeDependencies($process, $phpParser, $generator);
        $generator = $this->analyzePreParser($process, $phpParser, $generator, $firstIteration);
        $phpParser->addVariable(lcfirst($process['name']), $generator->getDTO());
        $generator->addTemplateVariable(lcfirst($process['name']), $generator->getDTO());
        $generator = $this->analyzePostParser($process, $phpParser, $generator, $firstIteration);

        return $generator;
    }

    /**
     * Analyze the dependencies generator
     *
     * @param array $process
     * @param PhpStringParser $phpParser
     * @param GeneratorDataObject $generator
     * @return \CrudGenerator\Generators\GeneratorDataObject
     */
    private function analyzeDependencies(array $process, PhpStringParser $phpParser, GeneratorDataObject $generator)
    {
        $generator = clone $generator;

        if (isset($process['dependencies']) && is_array($process['dependencies'])) {
            foreach ($process['dependencies'] as $dependencieName) {
                $generator->addDependency(
                    $this->analyze($dependencieName, $phpParser, $generator, $generator->getDTO()->getMetadata())
                );
            }
            $generator = $this->addDependenciesVariablesToMainGenerator($generator);
        }

        return $generator;
    }

    /**
     * @param GeneratorDataObject $generator
     * @return GeneratorDataObject
     */
    private function addDependenciesVariablesToMainGenerator(GeneratorDataObject $generator)
    {
        $generator = clone $generator;

        foreach ($generator->getDependencies() as $dependencies) {
            /* @var $dependencies GeneratorDataObject */
            foreach ($dependencies->getTemplateVariables() as $templateVariableName => $templateVariableValue) {
                $generator->addTemplateVariable($templateVariableName, $templateVariableValue);
            }
            foreach ($dependencies->getFiles() as $file) {
                $generator->addFile($file['skeletonPath'], $file['name'], $file['fileName']);
            }
        }

        return $generator;
    }

    /**
     * Analyze pre parser
     *
     * @param array $process
     * @param PhpStringParser $phpParser
     * @param GeneratorDataObject $generator
     * @param boolean $firstIteration
     * @return \CrudGenerator\Generators\GeneratorDataObject
     */
    private function analyzePreParser(array $process, PhpStringParser $phpParser, GeneratorDataObject $generator, $firstIteration)
    {
        $generator = clone $generator;

        if ($this->parserCollection->getPreParse()->count() > 0) {
            foreach ($this->parserCollection->getPreParse() as $parser) {
                $generator = $parser->evaluate($process, $phpParser, $generator, $firstIteration);
            }
        }

        return $generator;
    }

    /**
     * Analyze post parser
     *
     * @param array $process
     * @param PhpStringParser $phpParser
     * @param GeneratorDataObject $generator
     * @param boolean $firstIteration
     * @return \CrudGenerator\Generators\GeneratorDataObject
     */
    private function analyzePostParser(array $process, PhpStringParser $phpParser, GeneratorDataObject $generator, $firstIteration)
    {
        $generator = clone $generator;

        if ($this->parserCollection->getPostParse()->count() > 0) {
            foreach ($this->parserCollection->getPostParse() as $parser) {
                $generator = $parser->evaluate($process, $phpParser, $generator, $firstIteration);
            }
        }

        return $generator;
    }
}
