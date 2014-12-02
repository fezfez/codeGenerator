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

use CrudGenerator\Utils\FileManager;
use CrudGenerator\Utils\PhpStringParser;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Generators\Parser\Lexical\Condition\ConditionValidator;
use CrudGenerator\Generators\Parser\Lexical\Iterator\IteratorValidator;

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
     * @var IteratorValidator
     */
    private $iterationValidator = null;

    /**
     * @param FileManager        $fileManager
     * @param ConditionValidator $conditionValidator
     * @param IteratorValidator  $iterationValidator
     */
    public function __construct(
        FileManager $fileManager,
        ConditionValidator $conditionValidator,
        IteratorValidator $iterationValidator
    ) {
        $this->fileManager        = $fileManager;
        $this->conditionValidator = $conditionValidator;
        $this->iterationValidator = $iterationValidator;
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\Generators\Parser\ParserInterface::evaluate()
     */
    public function evaluate(array $process, PhpStringParser $parser, GeneratorDataObject $generator, $firstIteration)
    {
        $skeletonPath = $generator->getPath().'/Skeleton/';

        if (false === $this->fileManager->isDir($skeletonPath)) {
            throw new MalformedGeneratorException(
                sprintf('The Skeleton path "%s" is not a valid directory', $skeletonPath)
            );
        }

        if (false === isset($process['filesList']) || false === is_array($process['filesList'])) {
            throw new MalformedGeneratorException('No file given');
        }

        foreach ($process['filesList'] as $file) {
            $file = $this->checkIsFileIsWellFormed($file, $skeletonPath);

            if ($this->conditionValidator->isValid($file, $generator, $parser) === true) {
                $generator = $this->evaluateFile($file, $parser, $generator, $skeletonPath);
            }
        }

        return $generator;
    }

    /**
     * @param  mixed                       $file
     * @param  string                      $skeletonPath
     * @throws MalformedGeneratorException
     * @return array
     */
    private function checkIsFileIsWellFormed($file, $skeletonPath)
    {
        if (false === is_array($file)) {
            throw new MalformedGeneratorException(
                sprintf('File excepts to be an array "%s" given', gettype($file))
            );
        }
        if (isset($file['templatePath']) === false) {
            throw new MalformedGeneratorException(
                sprintf('No template provided in file "%s"', json_encode($file))
            );
        }
        if (isset($file['destinationPath']) === false) {
            throw new MalformedGeneratorException(
                sprintf('No destinationPath provided in file "%s"', json_encode($file))
            );
        }
        if ($this->fileManager->isFile($skeletonPath.$file['templatePath']) === false) {
            throw new MalformedGeneratorException(
                sprintf('TemplatePath does not exist in file "%s"', json_encode($file))
            );
        }

        return $file;
    }

    /**
     * @param  array               $file
     * @param  PhpStringParser     $parser
     * @param  GeneratorDataObject $generator
     * @param  string              $skeletonPath
     * @return GeneratorDataObject
     */
    private function evaluateFile(
        array $file,
        PhpStringParser $parser,
        GeneratorDataObject $generator,
        $skeletonPath
    ) {
        $generator = clone $generator;
        if (isset($file['iteration']) === true) {
            $iterator           = $this->iterationValidator->retrieveValidIteration($file, $generator, $parser);
            $overIteratorParser = clone $parser;

            foreach ($iterator as $iteration) {
                $overIteratorParser->addVariable('iteration', $iteration);

                $generator->addFile(
                    $skeletonPath,
                    $file['templatePath'],
                    $overIteratorParser->parse($file['destinationPath'])
                );
            }
        } else {
            $generator->addFile(
                $skeletonPath,
                $file['templatePath'],
                $parser->parse($file['destinationPath'])
            );
        }

        return $generator;
    }
}
