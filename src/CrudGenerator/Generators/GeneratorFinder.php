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

use CrudGenerator\EnvironnementResolver\EnvironnementResolverException;
use CrudGenerator\EnvironnementResolver\ZendFramework2Environnement;
use CrudGenerator\FileManager;

/**
 * Find all generator allow in project
 *
 * @author Stéphane Demonchaux
 */
class GeneratorFinder
{
    /**
     * @var array Paths to search generator
     */
    private $paths = array();
    /**
     * @var string File extension to search
     */
    private $fileExtension = 'php';
    /**
     * @var FileManager File manager
     */
    private $fileManager = null;

    /**
     * Find all generator allow in project
     * @param FileManager $fileManager
     */
    public function __construct(FileManager $fileManager)
    {
        $this->fileManager = $fileManager;
    }

    /**
     * Find all adapters allow in project
     *
     * @return array
     */
    public function getAllClasses()
    {
        $this->paths = array(
            __DIR__ . '/'
        );

        $this->paths = $this->checkZf2Configuration($this->paths);

        foreach ($this->paths as $path) {
            if (!is_dir($path)) {
                throw new \RuntimeException('invalid path ' . $path);
            }

            $iterator = new \RegexIterator(
                new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS),
                    \RecursiveIteratorIterator::LEAVES_ONLY
                ),
                '/^.+' . preg_quote($this->fileExtension) . '$/i',
                \RecursiveRegexIterator::GET_MATCH
            );

            foreach ($iterator as $file) {
                $sourceFile = realpath($file[0]);

                require_once $sourceFile;

                $includedFiles[] = $sourceFile;
            }
        }

        $declared = get_declared_classes();

        foreach ($declared as $className) {
            $rc = new \ReflectionClass($className);
            $sourceFile = $rc->getFileName();
            $parentClass = $rc->getParentClass();
            if (is_object($parentClass)
                && in_array($sourceFile, $includedFiles)
                && $parentClass->name == 'CrudGenerator\Generators\BaseCodeGenerator') {
                $classes[] = $className;
            }
        }

        return $classes;
    }

    /**
     * Add Zend Framework 2 paths
     *
     * @param string $paths
     * @throws \RuntimeException
     * @return Ambiguous
     */
    private function checkZf2Configuration($paths)
    {
        try {
            $serviceManager = ZendFramework2Environnement::getDependence($this->fileManager);
            $config         = $serviceManager->get('config');

            if (isset($config['crudGenerator']) && isset($config['crudGenerator']['path'])) {
                foreach ($config['crudGenerator']['path'] as $path) {
                    $paths[] = $path;
                }
            }
        } catch (EnvironnementResolverException $e) {
        }

        return $paths;
    }
}
