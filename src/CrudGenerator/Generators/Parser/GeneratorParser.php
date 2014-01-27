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

use Symfony\Component\Yaml\Yaml;
use CrudGenerator\Utils\FileManager;
use CrudGenerator\Utils\PhpStringParser;
use CrudGenerator\MetaData\DataObject\MetaData;
use CrudGenerator\Generators\Strategies\ViewFileStrategy;
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
     * Find all generator allow in project
     * @param FileManager $fileManager
     * @param ClassAwake $classAwake
     */
    public function __construct(
        FileManager $fileManager,
        Yaml $yaml,
        PhpStringParser $phpStringParser,
        ViewFileStrategy $viewFile,
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
     * @param Generator $generator
     * @param MetaData $metadata
     * @param array $questions
     * @throws \InvalidArgumentException
     * @return Generator
     */
    public function init(GeneratorDataObject $generator, MetaData $metadata, array $questions = array())
    {
        $generator = clone $generator;
        $phpParser = clone $this->phpStringParser;

        return $this->analyse($generator->getName(), $phpParser, $generator, $questions, $metadata, true);
    }

    /**
     * @param string $name
     * @param PhpStringParser $phpParser
     * @param GeneratorDataObject $generator
     * @param array $questions
     * @param MetaData $metadata
     * @param string $firstIteration
     * @return GeneratorDataObject
     */
    private function analyse($name, PhpStringParser $phpParser, GeneratorDataObject $generator, array $questions, MetaData $metadata, $firstIteration = false)
    {
        $generatorFilePath = $this->generatorFinder->findByName($name);
        $process           = Yaml::parse(file_get_contents($generatorFilePath), true);

        $dto = new $process['dto']();
        $dto->setMetadata($metadata);

        $generator->addDependency($name);

        if (isset($process['dependencies'])) {
            foreach ($process['dependencies'] as $dependencieName) {
                $generator = $this->analyse($dependencieName, $phpParser, $generator, $questions, $metadata);
            }
        }

        $generator->setDTO($dto)
                  ->setPath($generatorFilePath);

        foreach ($this->parserCollection->getPreParse() as $parser) {
            $generator = $parser->evaluate($process, $phpParser, $generator, $questions, $firstIteration);
        }

        $phpParser->addVariable(lcfirst($process['name']), $generator->getDTO());
        $generator->addTemplateVariable(lcfirst($process['name']), $generator->getDTO());

        foreach ($this->parserCollection->getPostParse() as $parser) {
            $generator = $parser->evaluate($process, $phpParser, $generator, $questions, $firstIteration);
        }

        return $generator;
    }


}
