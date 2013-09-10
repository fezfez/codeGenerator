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

use CrudGenerator\MetaData\DataObject\MetaDataColumn;
use CrudGenerator\MetaData\DataObject\MetaDataColumnCollection;
use CrudGenerator\MetaData\DataObject\MetaDataRelationColumn;
use CrudGenerator\MetaData\DataObject\MetaDataRelationCollection;

/**
 * Base representation metadata for template generation
 *
 * @author Stéphane Demonchaux
 */
abstract class MetaData
{
    /**
     * @var MetaDataColumnCollection Column collection
     */
    private $columnCollection = null;
    /**
     * @var MetaDataRelationCollection Relation collection
     */
    private $relationCollection = null;
    /**
     * @var string Name
     */
    private $name = null;

    /**
     * Base representation metadata for template generation
     * @param MetaDataColumnCollection $columnCollection
     * @param MetaDataRelationCollection $relationCollection
     */
    public function __construct(
        MetaDataColumnCollection $columnCollection,
        MetaDataRelationCollection $relationCollection
    ) {
        $this->columnCollection   = $columnCollection;
        $this->relationCollection = $relationCollection;
    }
    /**
     * Append column dataobject
     * @param MetaDataColumn $value
     */
    public function appendColumn(MetaDataColumn $value)
    {
        $this->columnCollection->append($value);
    }
    /**
     * Append relation dataobject
     * @param MetaDataRelationColumn $value
     */
    public function appendRelation(MetaDataRelationColumn $value)
    {
        $this->relationCollection->append($value);
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
     * @return MetaDataColumnCollection
     */
    public function getColumnCollection($withoutIdentifier = false)
    {
        if ($withoutIdentifier === true) {
            $tmpColumnCollection = array();

            foreach ($this->columnCollection as $column) {
                $isPk = $column->isPrimaryKey();
                if ($isPk === false) {
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
     * @return MetaDataRelationColumn
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
        $tmpColumnCollection = array();

        foreach ($this->columnCollection as $column) {
            $isPk = $column->isPrimaryKey();
            if ($isPk === true) {
                $tmpColumnCollection[] = $column;
            }
        }

        return $tmpColumnCollection;
    }
    /**
     * Get name
     * @return string
     */
    public function getName($ucfirst = false)
    {
        $name = $this->camelCase($this->name);
        if (true === $ucfirst) {
            return ucfirst($name);
        } else {
            return $name;
        }
    }

    /**
     * @param string $value
     */
    private function camelCase($value)
    {
        return preg_replace_callback(
            '/_(\w)/',
            function (array $matches) {
                return ucfirst($matches[1]);
            },
            $value
        );
    }

    public function getOriginalName()
    {
        return $this->name;
    }
}
