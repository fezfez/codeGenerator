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
namespace CrudGenerator\View\Helpers;

use CrudGenerator\DataObject;
use CrudGenerator\View\Helpers\TemplateDatabaseConnectorStrategies\StrategyInterface;
use CrudGenerator\View\Helpers\TemplateDatabaseConnectorStrategies\PDOStrategy;
use CrudGenerator\View\Helpers\TemplateDatabaseConnectorStrategies\ZendFramework2Strategy;
use CrudGenerator\MetaData\Sources\Doctrine2\MetadataDataObjectDoctrine2;
use CrudGenerator\MetaData\Sources\PDO\MetadataDataObjectPDO;

/**
 * @author stephane.demonchaux
 *
 */
class TemplateDatabaseConnector implements StrategyInterface
{
    /**
     * @var CrudGenerator\View\Helpers\TemplateDatabaseConnectorStrategies\StrategyInterface
     */
    private $zendFramework2Strategy = null;
    /**
     * @var CrudGenerator\View\Helpers\TemplateDatabaseConnectorStrategies\StrategyInterface
     */
    private $pdo = null;

    /**
     * @param StrategyInterface $strategy
     */
    public function __construct(ZendFramework2Strategy $zendFramework2Strategy, PDOStrategy $pdo)
    {
        $this->zendFramework2Strategy = $zendFramework2Strategy;
        $this->pdo = $pdo;
    }

    private function findStrategy(DataObject $dataObject)
    {
    	$metadata = $dataObject->getMetadata();

    	if ($metadata instanceof MetadataDataObjectDoctrine2) {
    		$strategy = $this->zendFramework2Strategy;
    	} elseif ($metadata instanceof MetadataDataObjectPDO) {
    		$strategy = $this->pdo;
    	} else {
    		throw new \InvalidArgumentException("Metadata '" . get_class($metadata) . "' not supported");
    	}

    	return $strategy;
    }

    /**
     * @return string
     */
    public function getFullClass(DataObject $dataObject)
    {
		return $this->findStrategy($dataObject)->getFullClass($dataObject);
    }

    /**
     * @return string
     */
    public function getClassName(DataObject $dataObject)
    {
    	return $this->findStrategy($dataObject)->getClassName($dataObject);
    }

    /**
     * @return string
     */
    public function getVariableName(DataObject $dataObject)
    {
    	return $this->findStrategy($dataObject)->getVariableName($dataObject);
    }

    /**
     * @return string
     */
    public function getCreateInstance(DataObject $dataObject)
    {
    	return $this->findStrategy($dataObject)->getCreateInstance($dataObject);
    }

    /**
     * @param DataObject $dataobject
     * @return string
     */
    public function getQueryFindOneBy(DataObject $dataObject)
    {
    	return $this->findStrategy($dataObject)->getQueryFindOneBy($dataObject);
    }

    /**
     * @param DataObject $dataobject
     * @return string
     */
    public function getQueryFindAll(DataObject $dataObject)
    {
    	return $this->findStrategy($dataObject)->getQueryFindAll($dataObject);
    }

    /**
     * @return string
     */
    public function getModifyQuery(DataObject $dataObject)
    {
    	return $this->findStrategy($dataObject)->getModifyQuery($dataObject);
    }

    /**
     * @return string
     */
    public function getPersistQuery(DataObject $dataObject)
    {
        return $this->findStrategy($dataObject)->getPersistQuery($dataObject);
    }

    /**
     * @return string
     */
    public function getPurgeQueryForUnitTest(DataObject $dataObject)
    {
        return $this->findStrategy($dataObject)->getPurgeQueryForUnitTest($dataObject);
    }

    /**
     * @return string
     */
    public function getRemoveQuery(DataObject $dataObject)
    {
        return $this->findStrategy($dataObject)->getRemoveQuery($dataObject);
    }

    /* (non-PHPdoc)
     * @see CrudGenerator\View\Helpers\TemplateDatabaseConnectorStrategies.StrategyInterface::getTypeReturnedByDatabase()
     */
    public function getTypeReturnedByDatabase(DataObject $dataObject)
    {
        return $this->findStrategy($dataObject)->getTypeReturnedByDatabase($dataObject);
    }

    /* (non-PHPdoc)
     * @see CrudGenerator\View\Helpers\TemplateDatabaseConnectorStrategies.StrategyInterface::getConcreteTypeReturnedByDatabase()
     */
    public function getConcreteTypeReturnedByDatabase(DataObject $dataObject)
    {
        return $this->findStrategy($dataObject)->getConcreteTypeReturnedByDatabase($dataObject);
    }
}
