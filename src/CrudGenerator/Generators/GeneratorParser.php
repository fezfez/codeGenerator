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
namespace CrudGenerator\Generators;

use Symfony\Component\Yaml\Yaml;
use CrudGenerator\Utils\FileManager;
use CrudGenerator\Utils\PhpStringParser;
use CrudGenerator\Generators\Strategies\ViewFileStategy;
use CrudGenerator\MetaData\DataObject\MetaData;
use CrudGenerator\Generators\GeneratorFinder;
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
    const CONDITION_ELSE = 'else';

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
        ViewFileStategy $viewFile,
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
    public function init(Generator $generator, MetaData $metadata, array $questions)
    {
        $generator = clone $generator;
        $phpParser = clone $this->phpStringParser;

        return $this->analyse($generator->getName(), $phpParser, $generator, $questions, $metadata);
    }

    /**
     * @param string $name
     * @param PhpStringParser $phpParser
     * @param Generator $generator
     * @param array $questions
     * @param MetaData $metadata
     * @return Generator
     */
    private function analyse($name, PhpStringParser $phpParser, Generator $generator, array $questions, MetaData $metadata)
    {
        $generatorFilePath = $this->generatorFinder->findByName($name);
        $process           = Yaml::parse(file_get_contents($generatorFilePath), true);

        $dto = new $process['dto']();
        $dto->setMetadata($metadata);

        if (isset($process['dependencies'])) {
            foreach ($process['dependencies'] as $dependencieName) {
                $generator = $this->analyse($dependencieName, $phpParser, $generator, $questions, $metadata);
            }
        }

        $generator->setDTO($dto)
                  ->setPath($generatorFilePath);

        foreach ($this->parserCollection->getPreParse() as $parser) {
            $generator = $parser->evaluate($process, $phpParser, $generator, $questions);
        }

        $phpParser->addVariable(lcfirst($process['name']), $generator->getDTO());
        $generator->addTemplateVariable(lcfirst($process['name']), $generator->getDTO());

        foreach ($this->parserCollection->getPostParse() as $parser) {
            $generator = $parser->evaluate($process, $phpParser, $generator, $questions);
        }

        return $generator;
    }

    /**
     * @param Generator $generator
     * @param string $fileName
     * @throws \Exception
     * @return string
     */
    public function viewFile(Generator $generator, $fileName)
    {
        $files = $generator->getFiles();
        if(!isset($files[$fileName])) {
            throw new \Exception(sprintf('File "%s" does not exist', $fileName));
        }

        return $this->viewFile->generateFile(
            $generator->getDTO(),
            $files[$fileName]['skeletonPath'],
            $files[$fileName]['name'],
            '',
            $generator->getTemplateVariables()
        );
    }
}
