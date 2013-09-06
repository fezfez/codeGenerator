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
        return '$query = $this->' . $this->getVariableName() . '->prepare("SELECT * FROM ' . $dataObject->getEntity() .'");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_COLUMN, 0);';
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
        return '$this->' . $this->getVariableName() . '->persist($entity);
        $this->' . $this->getVariableName() . '->flush();';
    }

    /**
     * @return string
     */
    public function getPersistQuery(DataObject $dataObject)
    {
        $result = '$query = $this->' . $this->getVariableName() . '->prepare("INSERT INTO ' . $dataObject->getEntity();

        $columnName = array();
        foreach ($dataObject->getMetadata()->getColumnCollection(true) as $column) {
            $columnName[] = $column->getName();
        }

        $result .= '(' . implode(', ', $columnName) . ') VALUES ';
        $result .= '(' . implode(', ', explode(' ', str_repeat("? ", count($columnName)))) . ')';
        $result .= '");' . "\n";

        $result .= '        $query->execute(array(' . "\n";

        $columnInArray = array();
        foreach($dataObject->getMetadata()->getColumnCollection(true) as $metadata) {
            $columnInArray[] = '            $result->get' . $column->getName(true) . '()';
        }

        $result .= implode(', ' . "\n", $columnInArray) . "\n";
        $result .= "        ));";

        return $result;
    }

    /**
     * @return string
     */
    public function getRemoveQuery()
    {
        return '$this->' . $this->getVariableName() . '->remove($entity);
        $this->' . $this->getVariableName() . '->flush();';
    }
}
