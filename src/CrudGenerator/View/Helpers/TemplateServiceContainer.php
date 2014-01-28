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

use CrudGenerator\View\Helpers\TemplateServiceContainerStrategies\StrategyInterface;
use CrudGenerator\View\Helpers\TemplateServiceContainerStrategies\PDOStrategy;
use CrudGenerator\View\Helpers\TemplateServiceContainerStrategies\ZendFramework2Strategy;
use CrudGenerator\MetaData\Sources\Doctrine2\MetadataDataObjectDoctrine2;
use CrudGenerator\MetaData\Sources\PDO\MetadataDataObjectPDO;
use CrudGenerator\DataObject;

/**
 * @author stephane.demonchaux
 *
 */
class TemplateServiceContainer implements StrategyInterface
{
    /**
     * @var CrudGenerator\View\Helpers\TemplateServiceContainerStrategies\ZendFramework2Strategy
     */
    private $zendFramework2Strategy = null;
    /**
     * @var CrudGenerator\View\Helpers\TemplateServiceContainerStrategies\PDOStrategy
     */
    private $pdo = null;

    /**
     * @param ZendFramework2Strategy $zendFramework2Strategy
     * @param PDOStrategy $pdo
     */
    public function __construct(ZendFramework2Strategy $zendFramework2Strategy, PDOStrategy $pdo)
    {
        $this->zendFramework2Strategy = $zendFramework2Strategy;
        $this->pdo = $pdo;
    }

    /**
     * @param DataObject $dataObject
     * @return Ambiguous
     */
    private function findStrategy(DataObject $dataObject)
    {
    	$metadata = $dataObject->getMetadata();

    	if ($metadata instanceof MetadataDataObjectDoctrine2) {
    		$strategy = $this->zendFramework2Strategy;
    	} elseif ($metadata instanceof MetadataDataObjectPDO) {
    		$strategy = $this->pdo;
    	} else {
    		throw new \InvalidArgumentException('Metadata "' . get_class($metadata) . '" not supported');
    	}

    	return $strategy;
    }

    /**
     * @return string
     */
    public function getFullClassForUnitTest(DataObject $dataObject)
    {
    	return $this->findStrategy($dataObject)->getFullClassForUnitTest($dataObject);
    }

    /**
     * @return string
     */
    public function getCreateInstanceForUnitTest(DataObject $dataObject)
    {
    	return $this->findStrategy($dataObject)->getCreateInstanceForUnitTest($dataObject);
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
    public function getInjectionInDependencie(DataObject $dataObject)
    {
    	return $this->findStrategy($dataObject)->getInjectionInDependencie($dataObject);
    }
}
