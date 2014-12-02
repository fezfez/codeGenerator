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

use CrudGenerator\Metadata\DataObject\MetaDataInterface;
use CrudGenerator\Utils\FileManager;

/**
 * Find all generator allow in project
 *
 * @author Stéphane Demonchaux
 */
class GeneratorFinderCache implements GeneratorFinderInterface
{
    /**
     * @var GeneratorFinder
     */
    private $generatorFinder = null;
    /**
     * @var array
     */
    private $directories = null;
    /**
     * @var FileManager
     */
    private $fileManager = null;
    /**
     * @var boolean
     */
    private $noCache = null;

    /**
     * Constructor.
     * @param GeneratorFinder $generatorFinder
     * @param array           $directories
     * @param FileManager     $fileManager
     * @param boolean         $noCache
     */
    public function __construct(
        GeneratorFinder $generatorFinder,
        array $directories,
        FileManager $fileManager,
        $noCache = false
    ) {
        $this->generatorFinder = $generatorFinder;
        $this->directories     = $directories;
        $this->fileManager     = $fileManager;
        $this->noCache         = $noCache;
    }

    /**
     * Find all adapters allow in project
     *
     * @return array
     */
    public function getAllClasses(MetaDataInterface $metadata = null)
    {
        $cacheFilename  = $this->directories['Cache'].DIRECTORY_SEPARATOR;
        $cacheFilename .= md5('genrator_getAllClasses'.($metadata !== null) ? get_class($metadata) : '');

        if ($this->fileManager->isFile($cacheFilename) === true && $this->noCache === false) {
            $data = unserialize($this->fileManager->fileGetContent($cacheFilename));
        } else {
            $data = $this->generatorFinder->getAllClasses($metadata);
            $this->fileManager->filePutsContent($cacheFilename, serialize($data));
        }

        return $data;
    }

    /**
     * @param  string                    $name
     * @throws \InvalidArgumentException
     * @return string
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
