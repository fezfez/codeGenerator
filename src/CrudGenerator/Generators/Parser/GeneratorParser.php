<?php
/**
  * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Generators\Parser;

use CrudGenerator\DataObject;
use CrudGenerator\Utils\Transtyper;
use CrudGenerator\Utils\FileManager;
use CrudGenerator\Utils\PhpStringParser;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Generators\Finder\GeneratorFinderInterface;
use CrudGenerator\Generators\Validator\GeneratorValidator;
use CrudGenerator\MetaData\DataObject\MetaDataInterface;

/**
 * Find all generator allow in project
 *
 * @author Stéphane Demonchaux
 */
class GeneratorParser implements GeneratorParserInterface
{
    /**
     * @var Transtyper
     */
    private $transtyper = null;
    /**
     * @var FileManager file manager
     */
    private $fileManager = null;
    /**
     * @var PhpStringParser PhpStringParser
     */
    private $phpStringParser = null;
    /**
     * @var GeneratorFinderInterface
     */
    private $generatorFinder = null;
    /**
     * @var ParserCollection
     */
    private $parserCollection = null;
    /**
     * @var GeneratorValidator
     */
    private $generatorValidator = null;

    /**
     * @param FileManager              $fileManager
     * @param Transtyper               $transtyper
     * @param PhpStringParser          $phpStringParser
     * @param GeneratorFinderInterface $generatorFinder
     * @param ParserCollection         $parserCollection
     */
    public function __construct(
        FileManager $fileManager,
        Transtyper $transtyper,
        PhpStringParser $phpStringParser,
        GeneratorFinderInterface $generatorFinder,
        ParserCollection $parserCollection,
        GeneratorValidator $generatorValidator
    ) {
        $this->fileManager        = $fileManager;
        $this->transtyper         = $transtyper;
        $this->phpStringParser    = $phpStringParser;
        $this->generatorFinder    = $generatorFinder;
        $this->parserCollection   = $parserCollection;
        $this->generatorValidator = $generatorValidator;
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\Generators\Parser\GeneratorParserInterface::init()
     */
    public function init(GeneratorDataObject $generator, MetaDataInterface $metadata)
    {
        $generator = clone $generator;
        $phpParser = clone $this->phpStringParser;

        return $this->analyze($generator->getName(), $phpParser, $generator, $metadata, true);
    }

    /**
     * @param  string                                        $name
     * @param  PhpStringParser                               $phpParser
     * @param  GeneratorDataObject                           $generator
     * @param  MetaDataInterface                             $metadata
     * @param  boolean                                       $firstIteration
     * @return \CrudGenerator\Generators\GeneratorDataObject
     */
    private function analyze(
        $name,
        PhpStringParser $phpParser,
        GeneratorDataObject $generator,
        MetaDataInterface $metadata,
        $firstIteration = false
    ) {
        $generator         = clone $generator;
        $generatorFilePath = $this->generatorFinder->findByName($name);
        $process           = $this->transtyper->decode($this->fileManager->fileGetContent($generatorFilePath));

        $this->generatorValidator->isValid($process, $metadata);

        $dto = new DataObject();

        $generator->setDto($dto->setMetadata($metadata))
                  ->setPath($generatorFilePath);

        $generator = $this->analyzeDependencies($process, $phpParser, $generator);
        $generator = $this->analyzePreParser($process, $phpParser, $generator, $firstIteration);
        $phpParser->addVariable(lcfirst($process['name']), $generator->getDto());
        $generator->addTemplateVariable(lcfirst($process['name']), $generator->getDto());
        $generator = $this->analyzePostParser($process, $phpParser, $generator, $firstIteration);

        return $generator;
    }

    /**
     * Analyze the dependencies generator
     *
     * @param  array                                         $process
     * @param  PhpStringParser                               $phpParser
     * @param  GeneratorDataObject                           $generator
     * @return \CrudGenerator\Generators\GeneratorDataObject
     */
    private function analyzeDependencies(array $process, PhpStringParser $phpParser, GeneratorDataObject $generator)
    {
        $generator = clone $generator;

        if (isset($process['dependencies']) === true && is_array($process['dependencies']) === true) {
            foreach ($process['dependencies'] as $dependencieName) {
                $generator->addDependency(
                    $this->analyze($dependencieName, $phpParser, $generator, $generator->getDto()->getMetadata())
                );
            }
            $generator = $this->addDependenciesVariablesToMainGenerator($generator);
        }

        return $generator;
    }

    /**
     * @param  GeneratorDataObject $generator
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
     * @param  array                                         $process
     * @param  PhpStringParser                               $phpParser
     * @param  GeneratorDataObject                           $generator
     * @param  boolean                                       $firstIteration
     * @return \CrudGenerator\Generators\GeneratorDataObject
     */
    private function analyzePreParser(
        array $process,
        PhpStringParser $phpParser,
        GeneratorDataObject $generator,
        $firstIteration
    ) {
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
     * @param  array                                         $process
     * @param  PhpStringParser                               $phpParser
     * @param  GeneratorDataObject                           $generator
     * @param  boolean                                       $firstIteration
     * @return \CrudGenerator\Generators\GeneratorDataObject
     */
    private function analyzePostParser(
        array $process,
        PhpStringParser $phpParser,
        GeneratorDataObject $generator,
        $firstIteration
    ) {
        $generator = clone $generator;

        if ($this->parserCollection->getPostParse()->count() > 0) {
            foreach ($this->parserCollection->getPostParse() as $parser) {
                $generator = $parser->evaluate($process, $phpParser, $generator, $firstIteration);
            }
        }

        return $generator;
    }
}
