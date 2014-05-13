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
namespace CrudGenerator\MetaData;

use CrudGenerator\MetaData\MetaDataSourceCollection;
use CrudGenerator\MetaData\MetaDataSource;
use CrudGenerator\EnvironnementResolver\EnvironnementResolverException;
use CrudGenerator\Utils\FileManager;
use CrudGenerator\Utils\ClassAwake;
use ReflectionClass;

/**
 * Find all MetaDataSource allow in project
 *
 * @author Stéphane Demonchaux
 */
class MetaDataSourceHydrator
{
    private $fileManager = null;

    /**
     * Find all adapters allow in project
     * @param FileManager $fileManager
     * @param ClassAwake $classAwake
     */
    public function __construct(FileManager $fileManager)
    {
        $this->fileManager = $fileManager;
    }

    /**
     * Build a MetaDataSourceDataobject with all his dependencies
     *
     * @param string $adapterClassName
     * @param MetaDataSource $adapterDataObject
     * @return MetaDataSource
     */
    public function adapterNameToMetaDataSource($adapterClassName, MetaDataSource $adapterDataObject)
    {
        $adapter = clone $adapterDataObject;

        $adapter->setMetaDataDAO($adapterClassName);
        $doc = $this->getDocBlockFromFactory($adapter, '@CodeGenerator\Description');
        $env = $this->getDocBlockFromFactory($adapter, '@CodeGenerator\Environnement');
        $adapter->setDefinition($doc . (($env !== null) ? ' in ' . $env : ''));

        $configName  = $this->getDocBlockFromFactory($adapter, '@CodeGenerator\Config');
        $factoryName = $this->getDocBlockFromFactory($adapter, '@CodeGenerator\Factory');

        if (!empty($configName)) {
        	if (!class_exists($configName)) {
        		throw new \InvalidArgumentException(sprintf('The config class "%s" does not exist', $configName));
        	}
        	$config = new $configName();
        	$config->setMetaDataDAOFactory($factoryName);
            $adapter->setConfig($config);
        }

        $adapter->setMetaDataDAOFactory($factoryName);

        try {
            foreach ($this->getDocBlockFromFactory($adapter, '@CodeGenerator\Environnement', false) as $environnementString) {
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
     * @param MetaDataSource $adapter
     * @param string $string
     * @return array
     */
    private function getDocBlockFromFactory(MetaDataSource $adapter, $string, $one = true)
    {
        $reflectionClass = new ReflectionClass($adapter->getMetaDataDAO());

        $sDocComment = $reflectionClass->getDocComment();
        $sDocComment = trim(
                preg_replace(
                        "/(^[\\s]*\\/\\*\\*)
                |(^[\\s]\\*\\/)
                |(^[\\s]*\\*?\\s)
                |(^[\\s]*)
                |(^[\\t]*)/ixm",
                        "",
                        $sDocComment
                )
        );

        $sDocComment = str_replace("\r", "", $sDocComment);
        $sDocComment = preg_replace("/([\\t])+/", "\t", $sDocComment);
        $aDocCommentLines = explode("\n", $sDocComment);
        $factoryEnv = array();

        foreach ($aDocCommentLines as $commentLine) {
            if (substr($commentLine, 0, strlen($string)) === $string) {
                $factoryEnv[] = trim(str_replace($string, '', $commentLine));
            }
        }

        if (empty($factoryEnv) && $one === true) {
        	return null;
        } elseif($one === true) {
			return $factoryEnv[0];
        }

        return $factoryEnv;
    }
}
