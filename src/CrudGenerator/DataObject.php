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
class DataObject implements \JsonSerializable
{
    /**
     * @var MetaData Metadata object
     */
    private $metadata = null;
    /**
     * @var array
     */
    private $environnement = array();
    /**
     * @var array
     */
    private $store = array();

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
        if (false === isset($this->environnement[$environnement])) {
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

    /**
     * @param string $method
     * @param array $args
     * @throws \Exception
     * @return mixed
     */
    public function __call($method, $args)
    {
        $firstChar = substr($method, 0, 3);
        $methodName = substr($method, 3);
        if ($firstChar === 'get') {
            if (false === isset($this->store[$methodName])) {
                return null;
            }
            // Try acces a simple storage
            elseif (count($args) === 0 && count($this->store[$methodName]) === 1) {
                return $this->store[$methodName];
            }
            // try to acces a storage with (key, val) without parameters
            elseif (count($args) === 0 && is_array($this->store[$methodName]) === true) {
                return $this->store[$methodName];
            }
            // try to acces a storage with (key, val) with key parameters
            elseif (count($args) === 1 && $args[0] !== null && is_array($this->store[$methodName]) === true) {
                if (isset($this->store[$methodName][$args[0]]) === true) {
                    return $this->store[$methodName][$args[0]];
                } else {
                    return null;
                }
            }
            // unkown
            else {
                throw new \Exception(
                    sprintf("method %s unkown params %s with store %s", $method, print_r($args, true), print_r($this->store[$methodName], true))
                );
            }
        } elseif ($firstChar === 'set') {
            if (count($args) === 1) {
                $this->store[$methodName] = $args[0];
            } elseif (count($args) === 2) {
                $this->store[$methodName][$args[0]] = $args[1];
            } else {
                throw new \Exception("cannot store more than 2 parameters");
            }

            return $this;
        } else {
            throw new \Exception("unknown method [$methodName]");
        }
    }

    public function getStore()
    {
        return $this->store;
    }

    public function jsonSerialize()
    {
        return array(
            'metadata' => $this->metadata,
            'store'    => $this->store
        );
    }
}
