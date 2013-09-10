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

use CrudGenerator\View\ViewHelperInterface;
use CrudGenerator\DataObject;
use CrudGenerator\View\Helpers\TemplateDatabaseConnectorStrategies\StrategyInterface;

/**
 * @author stephane.demonchaux
 *
 */
class TemplateDatabaseConnector implements StrategyInterface
{
    /**
     * @var CrudGenerator\View\Helpers\TemplateDatabaseConnectorStrategies\StrategyInterface
     */
    private $strategy = null;

    /**
     * @param StrategyInterface $strategy
     */
    public function __construct(StrategyInterface $strategy)
    {
        $this->strategy = $strategy;
    }

    /**
     * @return string
     */
    public function getFullClass()
    {
        return $this->strategy->getFullClass();
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->strategy->getClassName();
    }

    /**
     * @return string
     */
    public function getVariableName()
    {
        return $this->strategy->getVariableName();
    }

    /**
     * @return string
     */
    public function getCreateInstance()
    {
        return $this->strategy->getCreateInstance();
    }

    /**
     * @param DataObject $dataobject
     * @return string
     */
    public function getQueryFindOneBy(DataObject $dataObject)
    {
        return $this->strategy->getQueryFindOneBy($dataObject);
    }

    /**
     * @param DataObject $dataobject
     * @return string
     */
    public function getQueryFindAll(DataObject $dataObject)
    {
        return $this->strategy->getQueryFindAll($dataObject);
    }

    /**
     * @return string
     */
    public function getModifyQuery()
    {
        return $this->strategy->getModifyQuery();
    }

    /**
     * @return string
     */
    public function getPersistQuery(DataObject $dataObject)
    {
        return $this->strategy->getPersistQuery($dataObject);
    }

    /**
     * @return string
     */
    public function getPurgeQueryForUnitTest(DataObject $dataObject)
    {
        return $this->strategy->getPurgeQueryForUnitTest($dataObject);
    }

    /**
     * @return string
     */
    public function getRemoveQuery(DataObject $dataObject)
    {
        return $this->strategy->getRemoveQuery($dataObject);
    }

    /* (non-PHPdoc)
     * @see CrudGenerator\View\Helpers\TemplateDatabaseConnectorStrategies.StrategyInterface::getTypeReturnedByDatabase()
     */
    public function getTypeReturnedByDatabase()
    {
        return $this->strategy->getTypeReturnedByDatabase();
    }

    /* (non-PHPdoc)
     * @see CrudGenerator\View\Helpers\TemplateDatabaseConnectorStrategies.StrategyInterface::getConcreteTypeReturnedByDatabase()
     */
    public function getConcreteTypeReturnedByDatabase(DataObject $dataObject)
    {
        return $this->strategy->getConcreteTypeReturnedByDatabase($dataObject);
    }
}
