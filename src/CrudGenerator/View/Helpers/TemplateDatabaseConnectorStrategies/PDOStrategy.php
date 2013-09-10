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

        $query = $this->' . $this->getVariableName() . '->prepare("SELECT * FROM ' . $dataObject->getEntity() .' id = " . $' . $dataObject->getMetadata()->getName() . '->getId());
        $query->execute();
        $result = $query->fetch();';
    }

    /**
     * @param DataObject $dataobject
     * @return string
     */
    public function getQueryFindAll(DataObject $dataObject)
    {
        return '$query = $this->' . $this->getVariableName() . '->prepare("SELECT * FROM ' . $dataObject->getEntity() .'");
        $query->execute();
        $results = $query->fetchAll();';
    }

    /**
     * @return string
     */
    public function getModifyQuery()
    {
        return '
                $query = $this->pdo->prepare("UPDATE suivi_news set dt_creat = ?, nom_log = ?, ver_log = ?, titre_new = ?, url = ?, nivutil = ? WHERE id = ?");
        $query->execute(array(
            $result->getDt_creat(),
            $result->getNom_log(),
            $result->getVer_log(),
            $result->getTitre_new(),
            $result->getUrl(),
            $result->getNivutil(),
            $result->getId()
        ));

                $query = $this->pdo->prepare("SELECT * FROM suivi_news WHERE id = " . $result->getId());
        $query->execute();
        $result = $query->fetch();
        ';
    }

    /**
     * @return string
     */
    public function getPersistQuery(DataObject $dataObject)
    {
        $result = '$query = $this->' . $this->getVariableName() . '->prepare("INSERT INTO ' . $dataObject->getEntity();

        $columnName = array();
        $columnCollection = $dataObject->getMetadata()->getColumnCollection(true);
        foreach ($columnCollection as $column) {
            $columnName[] = $column->getName();
        }

        $result .= '(' . implode(', ', $columnName) . ') VALUES ';
        $result .= '(' . implode(', ', explode(' ', str_repeat("? ", count($columnName)))) . ')';
        $result .= '");' . "\n";

        $result .= '        $query->execute(array(' . "\n";

        $columnInArray = array();
        $columnCollection = $dataObject->getMetadata()->getColumnCollection(true);
        foreach($columnCollection as $metadata) {
            $columnInArray[] = '            $result->get' . $column->getName(true) . '()';
        }

        $result .= implode(', ' . "\n", $columnInArray) . "\n";
        $result .= "        ));";

        $result .= '$query = $this->' . $this->getVariableName() . '->prepare("SELECT * FROM ' . $dataObject->getEntity() . ' WHERE id = " . $this->pdo->lastInsertId())' . "\n";
        $result .= '$query->execute();' . "\n";
        $result .= '$result = $query->fetch();' . "\n";

        return $result;
    }

    /**
     * @return string
     */
    public function getRemoveQuery(DataObject $dataObject)
    {
        return '$this->' . $this->getVariableName() . '->exec("DELETE FROM ' . $dataObject->getEntity() . ' WHERE id = " . $' . $dataObject->getMetadata()->getName(true) . '->getId());';
    }

    /**
     * @return string
     */
    public function getPurgeQueryForUnitTest(DataObject $dataObject)
    {
        return '$this->getDatabaseConnection()->exec("DELETE FROM ' . $dataObject->getEntity() . '");';
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
