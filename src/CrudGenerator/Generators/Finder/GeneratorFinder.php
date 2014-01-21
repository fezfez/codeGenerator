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
namespace CrudGenerator\Generators\Finder;

use CrudGenerator\EnvironnementResolver\EnvironnementResolverException;
use CrudGenerator\EnvironnementResolver\ZendFramework2Environnement;
use CrudGenerator\Utils\FileManager;
use CrudGenerator\Utils\ClassAwake;
use Symfony\Component\Yaml\Yaml;

/**
 * Find all generator allow in project
 *
 * @author Stéphane Demonchaux
 */
class GeneratorFinder
{
    /**
     * @var FileManager File manager
     */
    private $fileManager = null;
    /**
     * @var ClassAwake Class awake
     */
    private $classAwake = null;

    /**
     * Find all generator allow in project
     * @param FileManager $fileManager
     * @param ClassAwake $classAwake
     */
    public function __construct(FileManager $fileManager, ClassAwake $classAwake)
    {
        $this->fileManager = $fileManager;
        $this->classAwake  = $classAwake;
    }

    /**
     * Find all adapters allow in project
     *
     * @return array
     */
    public function getAllClasses()
    {
        $directories = $this->checkZf2Configuration(
            array(
                __DIR__ . '/../../' // @TODO fix path
            )
        );

        $generators = array();
        foreach ($directories as $directorie) {
        	$iterator = new \RegexIterator(
        		new \RecursiveIteratorIterator(
        				new \RecursiveDirectoryIterator($directorie, \FilesystemIterator::SKIP_DOTS),
        				\RecursiveIteratorIterator::LEAVES_ONLY
        		),
        		'/^.+' . preg_quote('.generator.yaml') . '$/i',
        		\RecursiveRegexIterator::GET_MATCH
        	);


        	foreach ($iterator as $file) {
				$yaml = Yaml::parse(file_get_contents($file[0]), true);
        		$generators[$file[0]] = $yaml['name'];
        	}
        }

        return $generators;
    }

    /**
     * @param string $name
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

    /**
     * Add Zend Framework 2 paths
     *
     * @param array $paths
     * @throws \RuntimeException
     * @return array
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
