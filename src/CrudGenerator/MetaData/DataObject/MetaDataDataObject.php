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
namespace CrudGenerator\MetaData\DataObject;

use CrudGenerator\MetaData\DataObject\MetaDataColumnDataObject;
use CrudGenerator\MetaData\DataObject\MetaDataColumnDataObjectCollection;
use CrudGenerator\MetaData\DataObject\MetaDataRelationColumnDataObject;
use CrudGenerator\MetaData\DataObject\MetaDataRelationDataObjectCollection;

/**
 * Base representation metadata for template generation
 *
 * @author Stéphane Demonchaux
 */
abstract class MetaDataDataObject
{
    /**
     * @var MetaDataColumnDataObjectCollection Column collection
     */
    private $columnCollection = null;
    /**
     * @var MetaDataRelationDataObjectCollection Relation collection
     */
    private $relationCollection = null;
    /**
     * @var array Identifiers
     */
    private $identifier = array();
    /**
     * @var string Name
     */
    private $name = null;

    /**
     * Base representation metadata for template generation
     * @param MetaDataColumnDataObjectCollection $columnCollection
     * @param MetaDataRelationDataObjectCollection $relationCollection
     */
    public function __construct(
        MetaDataColumnDataObjectCollection $columnCollection,
        MetaDataRelationDataObjectCollection $relationCollection
    ) {
        $this->columnCollection   = $columnCollection;
        $this->relationCollection = $relationCollection;
    }
    /**
     * Append column dataobject
     * @param MetaDataColumnDataObject $value
     */
    public function appendColumn(MetaDataColumnDataObject $value)
    {
        $this->columnCollection->append($value);
    }
    /**
     * Append relation dataobject
     * @param MetaDataRelationColumnDataObject $value
     */
    public function appendRelation(MetaDataRelationColumnDataObject $value)
    {
        $this->relationCollection->append($value);
    }
    /**
     * Add identifier
     * @param MetaDataRelationColumnDataObject $value
     */
    public function addIdentifier($value)
    {
        $this->identifier[] = $value;
        return $this;
    }
    /**
     * Set name
     * @param string $value
     */
    public function setName($value)
    {
        $this->name = $value;
        return $this;
    }

    /**
     * Get column collection
     * @return MetaDataColumnDataObjectCollection
     */
    public function getColumnCollection($withoutIdentifier = false)
    {
        if ($withoutIdentifier === true) {
            $tmpColumnCollection = array();

            foreach ($this->columnCollection as $column) {
                $name = $column->getName();
                if (!in_array($name, $this->identifier)) {
                    $tmpColumnCollection[] = $column;
                }
            }

            return $tmpColumnCollection;
        } else {
            return $this->columnCollection;
        }
    }
    /**
     * Get relation collection
     * @return MetaDataRelationColumnDataObject
     */
    public function getRelationCollection()
    {
        return $this->relationCollection;
    }
    /**
     * Get identifier
     * @return array
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }
    /**
     * Get name
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function getCamelCaseName($ucfirst = true)
    {
        $name = $this->name;
        $result = preg_replace_callback(
            '/_(\w)/',
            function (array $matches) {
               return ucfirst($matches[1]);
            },
            $name
        );

        if(true === $ucfirst) {
            return ucfirst($result);
        } else {
            return $result;
        }
    }
}
