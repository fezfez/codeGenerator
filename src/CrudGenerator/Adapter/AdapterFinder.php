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
namespace CrudGenerator\Adapter;

use CrudGenerator\Adapter\AdapterCollection;
use CrudGenerator\Adapter\AdapterDataObject;
use CrudGenerator\EnvironnementResolver\EnvironnementResolverException;
use CrudGenerator\FileManager;

use ReflectionClass;
use RuntimeException;
use RegexIterator;
use RecursiveDirectoryIterator;
use RecursiveRegexIterator;
use RecursiveIteratorIterator;
use FilesystemIterator;

/**
 * Find all adapters allow in project
 *
 * @author Stéphane Demonchaux
 */
class AdapterFinder
{
    /**
     * @var array Paths to search adapter
     */
    private $paths = array();
    /**
     * @var string File extension to find
     */
    private $fileExtension = 'php';
    /**
     * @var FileManager File manager
     */
    private $fileManager = null;

    /**
     * Find all adapters allow in project
     * @param FileManager $fileManager
     */
    public function __construct(FileManager $fileManager)
    {
        $this->fileManager = $fileManager;
    }

    /**
     * Find all adpater in the projects
     *
     * @return AdapterCollection
     */
    public function getAllAdapters()
    {
        $this->paths = array(
            __DIR__ . '/../MetaData/'
        );

        foreach ($this->paths as $path) {

            $iterator = new RegexIterator(
                new RecursiveIteratorIterator(
                    new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS),
                    RecursiveIteratorIterator::LEAVES_ONLY
                ),
                '/^.+' . preg_quote($this->fileExtension) . '$/i',
                RecursiveRegexIterator::GET_MATCH
            );

            foreach ($iterator as $file) {
                $sourceFile = realpath($file[0]);

                require_once $sourceFile;

                $includedFiles[] = $sourceFile;
            }
        }

        $declared = get_declared_classes();

        $adapterCollection = new AdapterCollection();
        $adapterDataObject = new AdapterDataObject();
        foreach ($declared as $className) {
            $reflectionClass = new ReflectionClass($className);
            $sourceFile = $reflectionClass->getFileName();
            $interfaces = $reflectionClass->getInterfaces();

            if (is_array($interfaces) && !empty($interfaces)
                && in_array($sourceFile, $includedFiles)
                && isset($interfaces['CrudGenerator\MetaData\MetaDataDAOInterface'])) {

                $adapterCollection->append(
                    $this->buildAdapterDataObject(
                        $className,
                        $adapterDataObject
                    )
                );
            }
        }

        return $adapterCollection;
    }

    /**
     * Build a AdapaterDataobject with all his dependencies
     *
     * @param string $adapterClassName
     * @param AdapterDataObject $adapterDataObject
     * @return AdapterDataObject
     */
    private function buildAdapterDataObject(
        $adapterClassName,
        AdapterDataObject $adapterDataObject)
    {
        $adapter = clone $adapterDataObject;

        $adapter->setName($adapterClassName);
        $doc = $this->getDocBlockFromFactory($adapter, '@CodeGenerator\Description');
        $env = $this->getDocBlockFromFactory($adapter, '@CodeGenerator\Environnement');
        $adapter->setDefinition((isset($doc[0]) ? $doc[0] : '') . (isset($env[0]) ? ' in ' . $env[0] : ''));
        $configName = str_replace('MetaDataDAO', '', $adapterClassName) . 'Config';

        if(class_exists($configName)) {
            $adapter->setConfig(new $configName());
        }

        try {
            foreach($this->getDocBlockFromFactory($adapter, '@CodeGenerator\Environnement') as $environnementString) {
                $environementClass = 'CrudGenerator\EnvironnementResolver\\' . $environnementString;
                $environementClass::getDependence($this->fileManager);
            }
        } catch (EnvironnementResolverException $e) {
            $adapter->setFalseDependencie($e->getMessage());
        }

        return $adapter;
    }

    /**
     * Find a particularie string in docblock and parse it
     *
     * @param AdapterDataObject $adapter
     * @param string $string
     * @return array
     */
    private function getDocBlockFromFactory(AdapterDataObject $adapter, $string)
    {
        $reflectionClass = new ReflectionClass($adapter->getFactory());

        $sDocComment = $reflectionClass->getDocComment();
        $sDocComment = trim(preg_replace("/(^[\\s]*\\/\\*\\*)
                        |(^[\\s]\\*\\/)
                        |(^[\\s]*\\*?\\s)
                        |(^[\\s]*)
                        |(^[\\t]*)/ixm", "", $sDocComment));

        $sDocComment = str_replace("\r", "", $sDocComment);
        $sDocComment = preg_replace("/([\\t])+/", "\t", $sDocComment);
        $aDocCommentLines = explode("\n", $sDocComment);
        $factoryEnv = array();

        foreach($aDocCommentLines as $commentLine) {
            if(substr($commentLine, 0, strlen($string)) === $string) {
                $factoryEnv[] = trim(str_replace($string, '', $commentLine));
            }
        }

        return $factoryEnv;
    }
}
