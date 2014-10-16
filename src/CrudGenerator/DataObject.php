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

use CrudGenerator\MetaData\DataObject\MetaDataInterface;
use CrudGenerator\Generators\Parser\Lexical\QuestionResponseTypeEnum;
use CrudGenerator\Generators\Parser\Lexical\QuestionTypeEnum;
use CrudGenerator\Storage\StorageString;
use CrudGenerator\Storage\StorageArray;

/**
 * Base representation for template generation
 *
 * @author Stéphane Demonchaux
 */
class DataObject implements \JsonSerializable
{
    /**
     * @var MetaDataInterface Metadata object
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
     * @param MetaDataInterface $value
     * @return \CrudGenerator\DataObject
     */
    public function setMetadata(MetaDataInterface $value)
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
     * @return \CrudGenerator\MetaData\DataObject\MetaDataInterface
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
            throw new EnvironnementNotDefinedException(
                sprintf(
                    'Environnement "%s" not defined, please answer this question before preview',
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
        $firstChar  = substr($method, 0, 3);
        $methodName = substr($method, 3);

        if ($firstChar === 'get') {
            return $this->getter($args, $methodName);
        } elseif ($firstChar === 'set') {
            return $this->setter($args, $methodName);
        } else {
            throw new \Exception("unknown method [$methodName]");
        }
    }

    /**
     * @param array $args
     * @param string $methodName
     * @throws \Exception
     * @return mixed
     */
    private function getter(array $args, $methodName)
    {
        if (false === array_key_exists($methodName, $this->store)) {
            throw new \Exception(sprintf('The storage "%s" does not exist', $methodName));
        } elseif ($this->store[$methodName]->isValidAcces($args) === true) {
            return $this->store[$methodName]->get($args);
        } else {
            throw new \Exception(sprintf('Unkown method "%s"', $methodName));
        }
    }

    /**
     * @param array $args
     * @param string $methodName
     * @throws \Exception
     * @return \CrudGenerator\DataObject
     */
    private function setter(array $args, $methodName)
    {
        $storageCollection = array(new StorageString(), new StorageArray());
        $stored            = false;

        foreach ($storageCollection as $storage) {
            if ($storage->isValidStore($args) === true) {
                if (isset($this->store[$methodName]) === false ||
                    get_class($this->store[$methodName]) !== get_class($storage)) {
                    $this->store[$methodName] = clone $storage;
                }

                $this->store[$methodName]->set($args);
                $stored = true;
            }
        }

        if ($stored === false) {
            throw new \Exception(sprintf("Can't store %s expression in %s", json_encode($args), $methodName));
        }

        return $this;
    }

    /**
     * @param array $question
     * @param boolean $isIterable
     * @return \CrudGenerator\DataObject
     */
    public function register(array $question, $isIterable)
    {
        $this->store[$question['dtoAttribute']] = $isIterable === true ? new StorageArray() : new StorageString();

        return $this;
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
