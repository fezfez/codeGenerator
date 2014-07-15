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

use CrudGenerator\MetaData\DataObject\MetaData;

/**
 * Represent relation between Metadata
 *
 * @author Stéphane Demonchaux
 */
class MetaDataRelationColumn
{
    const ONE_TO_MANY  = 3;
    const ONE_TO_ONE   = 4;
    const MANY_TO_MANY = 5;
    const MANY_TO_ONE  = 6;

    /**
     * @var string Full name (ex: Test\My\Metadata)
     */
    private $fullName = null;
    /**
     * @var string Name
     */
    private $fieldName = null;
    /**
     * @var string Relation association type (ex manyToMany, oneToOne etc...)
     */
    private $associationType = null;
    /**
     * @var MetaData
     */
    private $metadata = null;

    /**
     * Set full name
     * @param string $value
     * @return \CrudGenerator\MetaData\DataObject\MetaDataRelationColumn
     */
    public function setFullName($value)
    {
        $this->fullName = $value;
        return $this;
    }
    /**
     * Set name
     * @param string $value
     * @return \CrudGenerator\MetaData\DataObject\MetaDataRelationColumn
     */
    public function setFieldName($value)
    {
        $this->fieldName = $value;
        return $this;
    }
    /**
     * Set association type
     * @param string $value
     * @return \CrudGenerator\MetaData\DataObject\MetaDataRelationColumn
     */
    public function setAssociationType($value)
    {
        $this->associationType = $value;
        return $this;
    }
    /**
     * Set metadata
     * @param MetaData $value
     * @return \CrudGenerator\MetaData\DataObject\MetaDataRelationColumn
     */
    public function setMetadata(MetaData $value)
    {
        $this->metadata = $value;
        return $this;
    }

    /**
     * Get full name
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }
    /**
     * Get name
     * @return string
     */
    public function getName()
    {
        if (!strrchr($this->fullName, '\\')) {
            return $this->fullName;
        } else {
            return str_replace('\\', '', strrchr($this->fullName, '\\'));
        }
    }
    /**
     * Get fields name
     * @return string
     */
    public function getFieldName()
    {
        return $this->fieldName;
    }
    /**
     * Get association type
     * @return string
     */
    public function getAssociationType()
    {
        return $this->associationType;
    }
    /**
     * Get fields name
     * @return MetaData
     */
    public function getMetadata()
    {
        return $this->metadata;
    }
}
