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
namespace CrudGenerator\History;

use CrudGenerator\DataObject;

/**
 * History representation
 *
 * @author Stéphane Demonchaux
 */
class History
{
    /**
     * @var string Column name
     */
    private $name       = null;
    /**
     * @var DataObject DataObject
     */
    private $dataObject = null;

    /**
     * Set Column name
     *
     * @param string $value
     * @return \CrudGenerator\History\History
     */
    public function setName($value)
    {
        $this->name = $value;
        return $this;
    }
    /**
     * Set DataObject
     *
     * @param DataObject $value
     * @return \CrudGenerator\History\History
     */
    public function setDataObject(DataObject $value)
    {
        $this->dataObject = $value;
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
     * Get DataObject
     *
     * @return DataObject
     */
    public function getDataObject()
    {
        return $this->dataObject;
    }
}