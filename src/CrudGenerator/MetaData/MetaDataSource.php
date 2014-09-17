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
namespace CrudGenerator\MetaData;

use CrudGenerator\MetaData\Sources\MetaDataConfigInterface;

/**
 * Adapter representation
 * @author Stéphane Demonchaux
 */
class MetaDataSource implements \JsonSerializable
{
    /**
     * @var string name of adapater
     */
    private $metaDataDAO = null;
    /**
     * @var string name of adapater
     */
    private $metaDataDAOFactory = null;
    /**
     * @var string true if dependencies of adapater are complete
     */
    private $falseDependencies = null;
    /**
     * @var string adapter definition
     */
    private $definition = null;
    /**
     * @var MetaDataConfigInterface adapter configuration
     */
    private $config = null;

    /**
     * Set name
     * @param string $value
     * @return \CrudGenerator\MetaData\MetaDataSource
     */
    public function setMetaDataDAO($value)
    {
        $this->metaDataDAO = $value;
        return $this;
    }
    /**
     * @param string $value
     * @return \CrudGenerator\MetaData\MetaDataSource
     */
    public function setMetaDataDAOFactory($value)
    {
        $this->metaDataDAOFactory = $value;
        return $this;
    }
    /**
     * Set definition
     * @param string $value
     * @return \CrudGenerator\MetaData\MetaDataSource
     */
    public function setDefinition($value)
    {
        $this->definition = $value;
        return $this;
    }
    /**
     * Set false dependencie
     * @param string $value
     * @return \CrudGenerator\MetaData\MetaDataSource
     */
    public function setFalseDependencie($value)
    {
        $this->falseDependencies = $value;
        return $this;
    }
    /**
     * Set config
     * @param MetaDataConfigInterface $value
     * @return \CrudGenerator\MetaData\MetaDataSource
     */
    public function setConfig(MetaDataConfigInterface $value)
    {
        $this->config = $value;
        return $this;
    }

    /**
     * Get MetadataDAO class as string
     * @return string
     */
    public function getMetadataDao()
    {
        return $this->metaDataDAO;
    }
    /**
     * Get MetadataDAOFactory class as string
     * @return string
     */
    public function getMetadataDaoFactory()
    {
        return $this->metaDataDAOFactory;
    }
    /**
     * Get definition
     * @return string
     */
    public function getDefinition()
    {
        return $this->definition;
    }
    /**
     * Get false dependencies
     * @return string
     */
    public function getFalseDependencies()
    {
        return $this->falseDependencies;
    }
    /**
     * Get config
     * @return MetaDataConfigInterface
     */
    public function getConfig()
    {
        return $this->config;
    }
    /**
     * @return string
     */
    public function getUniqueName()
    {
        if ($this->config === null) {
            return $this->definition;
        } else {
            return $this->config->getUniqueName();
        }
    }

    public function jsonSerialize()
    {
        return array(
            'config'             => $this->config,
            'definition'         => $this->definition,
            'metaDataDAO'        => $this->metaDataDAO,
            'metaDataDAOFactory' => $this->metaDataDAOFactory,
            'falseDependencies'  => $this->falseDependencies,
            'uniqueName'         => $this->getUniqueName()
        );
    }
}
