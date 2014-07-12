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
namespace CrudGenerator;

use CrudGenerator\MetaData\DataObject\MetaData;

/**
 * Base representation for template generation
 *
 * @author Stéphane Demonchaux
 */
abstract class DataObject implements \JsonSerializable
{
    /**
     * @var MetaData Metadata object
     */
    private $metadata        = null;
    /**
     * @var array
     */
    private $environnement   = array();

    /**
     * Set MetaData
     * @param MetaData $value
     * @return \CrudGenerator\DataObject
     */
    public function setMetadata(MetaData $value)
    {
        $this->metadata = $value;
        return $this;
    }
    /**
     * @param string $environnement
     * @param string $value
     * @return \CrudGenerator\DataObject
     */
    public function addEnvironnementValue($environnement, $value)
    {
        $this->environnement[$environnement] = $value;
        return $this;
    }

    /**
     * Get MetaData
     *
     * @return \CrudGenerator\MetaData\DataObject\MetaData
     */
    public function getMetadata()
    {
        return $this->metadata;
    }
    /**
     * Get environnement
     *
     * @param string $environnement
     * @throws \InvalidArgumentException
     * @return string
     */
    public function getEnvironnement($environnement)
    {
        if (!isset($this->environnement[$environnement])) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Environnement "%s" not defined',
                    $environnement
                )
            );
        } else {
            return $this->environnement[$environnement];
        }
    }

    public function jsonSerialize()
    {
        return array(
        	'metadata' => $this->metadata
        );
    }
}
