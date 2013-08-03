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

/**
 * Metadata column representation
 *
 * @author Stéphane Demonchaux
 */
class MetaDataColumnDataObject
{
    /**
     * @var string Column name
     */
    private $name = null;
    /**
     * @var string Column type
     */
    private $type = null;
    /**
     * @var integer Column length
     */
    private $length = null;
    /**
     * @var boolean Column is nullable
     */
    private $nullable = true;

    /**
     * Set Column name
     *
     * @param string $value
     * @return \CrudGenerator\MetaData\DataObject\MetaDataColumnDataObject
     */
    public function setName($value)
    {
        $this->name = $value;
        return $this;
    }
    /**
     * Set Column type
     *
     * @param string $value
     * @return \CrudGenerator\MetaData\DataObject\MetaDataColumnDataObject
     */
    public function setType($value)
    {
        $this->type = $value;
        return $this;
    }
    /**
     * Set Column length
     *
     * @param integer $value
     * @return \CrudGenerator\MetaData\DataObject\MetaDataColumnDataObject
     */
    public function setLength($value)
    {
        $this->length = $value;
        return $this;
    }
    /**
     * Set Column is nullable
     *
     * @param boolean $value
     * @return \CrudGenerator\MetaData\DataObject\MetaDataColumnDataObject
     */
    public function setNullable($value)
    {
        $this->nullable = $value;
        return $this;
    }

    /**
     * Get Column name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * Get Column type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
    /**
     * Get Column length
     *
     * @return integer
     */
    public function getLength()
    {
        return $this->length;
    }
    /**
     * Get Column is nullable
     *
     * @return boolean
     */
    public function getNullable()
    {
        return $this->nullable;
    }
}
