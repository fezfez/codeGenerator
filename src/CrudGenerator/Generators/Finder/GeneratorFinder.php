<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Generators\Finder;

use CrudGenerator\MetaData\DataObject\MetaDataInterface;
use CrudGenerator\Utils\Transtyper;
use CrudGenerator\Generators\Validator\GeneratorValidator;
use CrudGenerator\Utils\FileManager;

/**
 * Find generator
 *
 * @author Stéphane Demonchaux
 */
class GeneratorFinder implements GeneratorFinderInterface
{
    /**
     * @var Transtyper
     */
    private $transtyper = null;
    /**
     * @var GeneratorValidator
     */
    private $generatorValidator = null;
    /**
     * @var FileManager
     */
    private $fileManager = null;
    /**
     * @var array
     */
    private $allClasses = null;

    /**
     * Constructor.
     *
     * @param Transtyper $transtyper
     * @param GeneratorValidator $generatorValidator
     * @param FileManager $fileManager
     */
    public function __construct(
        Transtyper $transtyper,
        GeneratorValidator $generatorValidator,
        FileManager $fileManager
    ) {
        $this->transtyper         = $transtyper;
        $this->generatorValidator = $generatorValidator;
        $this->fileManager        = $fileManager;
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\Generators\Finder\GeneratorFinderInterface::getAllClasses()
     */
    public function getAllClasses(MetaDataInterface $metadata = null)
    {
        if ($this->allClasses === null) {
            $generators = array();

            foreach ($this->fileManager->searchFileByRegex('/^.+\.generator\.json$/i') as $file) {
                $fileName = $file[0];
                $process  = $this->transtyper->decode($this->fileManager->fileGetContent($fileName));

                try {
                    $this->generatorValidator->isValid($process, $metadata);
                    $generators[$fileName] = $process['name'];
                } catch (\InvalidArgumentException $e) {
                    // Generator no valid
                }
            }

            $this->allClasses = $generators;
        }

        return $this->allClasses;
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\Generators\Finder\GeneratorFinderInterface::findByName()
     */
    public function findByName($name)
    {
        $generatorCollection = $this->getAllClasses();

        foreach ($generatorCollection as $generatorFile => $generatorName) {
            if ($generatorName === $name) {
                return $generatorFile;
            }
        }

        throw new \InvalidArgumentException(sprintf('Generator name "%s" does not exist', $name));
    }
}
