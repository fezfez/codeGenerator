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
namespace CrudGenerator\View\Helpers\TemplateDatabaseConnectorStrategies;

use CrudGenerator\DataObject;

/**
 * @author stephane.demonchaux
 *
 */
class PDOStrategy implements StrategyInterface
{
    /**
     * @return string
     */
    public function getFullClass()
    {
        return 'PDO';
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return 'PDO';
    }

    /**
     * @return string
     */
    public function getVariableName()
    {
        return 'pdo';
    }

    /**
     * @return string
     */
    public function getCreateInstance()
    {
        return 'new PDO();';
    }

    /**
     * @param DataObject $dataobject
     * @return string
     */
    public function getQueryFindOneBy(DataObject $dataObject)
    {
        return '
        if(!$' . $dataObject->getMetadata()->getName() . '->getId()) {
            throw new No' . $dataObject->getMetadata()->getName(true) . 'Exception(\'' . $dataObject->getMetadata()->getName() .' with not found\');
        }

        $query = $this->' . $this->getVariableName() . '->prepare("SELECT * FROM ' . $dataObject->getMetaData()->getOriginalName() .' id = " . $' . $dataObject->getMetadata()->getName() . '->getId());
        $query->execute();
        $result = $query->fetch();';
    }

    /**
     * @param DataObject $dataobject
     * @return string
     */
    public function getQueryFindAll(DataObject $dataObject)
    {
        return '$query = $this->' . $this->getVariableName() . '->prepare("SELECT * FROM ' . $dataObject->getMetaData()->getOriginalName() .'");
        $query->execute();
        $results = $query->fetchAll();';
    }

    /**
     * @return string
     */
    public function getModifyQuery(DataObject $dataObject)
    {
        return '
                $query = $this->pdo->prepare("UPDATE ' . $dataObject->getMetaData()->getOriginalName() . ' SET ';

        $columnInArray = array();
        $columnCollection = $dataObject->getMetadata()->getColumnCollection(true);
        foreach($columnCollection as $metadata) {
            $columnInArray[] = '' . $metadata->getName(true) . ' = ?';
        }

        $result .= implode(', ', $columnInArray) . ' WHERE id = ?");\n';
        $result .= $this->getExecuteParamsWithSelectOne($dataObject, false);

        return $result;
    }

    private function getExecuteParamsWithSelectOne(DataObject $dataObject, $withoutId = true)
    {
        $result = '        $query->execute(array(' . "\n";

        $columnInArray = array();
        $columnCollection = $dataObject->getMetadata()->getColumnCollection($withoutId);
        foreach($columnCollection as $metadata) {
            $columnInArray[] = '            $result->get' . $metadata->getName(true) . '()';
        }

        $result .= implode(', ' . "\n", $columnInArray) . "\n";
        $result .= "        ));\n\n";

        $result .= '        $query = $this->' . $this->getVariableName() . '->prepare("SELECT * FROM ' . $dataObject->getMetaData()->getOriginalName() . ' WHERE id = " . $this->pdo->lastInsertId());' . "\n";
        $result .= '        $query->execute();' . "\n";
        $result .= '        $result = $query->fetch();' . "\n";

        return $result;
    }

    /**
     * @return string
     */
    public function getPersistQuery(DataObject $dataObject)
    {
        $result = '$query = $this->' . $this->getVariableName() . '->prepare("INSERT INTO ' . $dataObject->getMetaData()->getOriginalName();

        $columnName = array();
        $columnCollection = $dataObject->getMetadata()->getColumnCollection(true);
        foreach ($columnCollection as $column) {
            $columnName[] = $column->getName();
        }

        $result .= '(' . implode(', ', $columnName) . ') VALUES ';
        $result .= '(' . implode(', ', explode('?', str_repeat("?", count($columnCollection)))) . ')';
        $result .= '");' . "\n\n";

        $result .= $this->getExecuteParamsWithSelectOne($dataObject);

        return $result;
    }

    /**
     * @return string
     */
    public function getRemoveQuery(DataObject $dataObject)
    {
        return '$this->' . $this->getVariableName() . '->exec("DELETE FROM ' . $dataObject->getMetaData()->getOriginalName() . ' WHERE id = " . $' . $dataObject->getMetadata()->getName(true) . '->getId());';
    }

    /**
     * @return string
     */
    public function getPurgeQueryForUnitTest(DataObject $dataObject)
    {
        return '$this->getDatabaseConnection()->exec("DELETE FROM ' . $dataObject->getMetaData()->getOriginalName() . '");';
    }

    /* (non-PHPdoc)
     * @see CrudGenerator\View\Helpers\TemplateDatabaseConnectorStrategies.StrategyInterface::getTypeReturnedByDatabase()
     */
    public function getTypeReturnedByDatabase()
    {
        return 'array';
    }

    public function getConcreteTypeReturnedByDatabase(DataObject $dataObject)
    {
        return 'array';
    }
}
