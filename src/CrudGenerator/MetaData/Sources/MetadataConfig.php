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
namespace CrudGenerator\MetaData\Sources;

/**
 * @author Stéphane Demonchaux
 */
abstract class MetadataConfig implements \JsonSerializable
{
    /**
     * Config description
     *
     * @var string
     */
    protected $definition;

    /**
     * The metaDataDAOFactory full qualified class
     *
     * @var string
     */
    protected $metaDataDAOFactory;

    /**
     * The connector full qualified class
     *
     * @var string
     */
    protected $connector;

    /**
     * The connector full qualified class
     *
     * @var string
     */
    protected $connectorConfig;

    final public function __construct()
    {
        $interface = 'JsonSerializable';

        if (in_array($interface, class_implements($this)) === false) {
            throw new \LogicException(get_class($this) . ' must implements ' . $interface);
        }

        if (class_exists($this->metaDataDAOFactory, true) === false) {
            throw new \LogicException(
                sprintf('The metadataDAOFactory class %s does not exist', $this->metaDataDAOFactory)
            );
        }
    }

    /**
     * Get unique configuration name
     * @return string
     */
    abstract public function getUniqueName();

    public function getConnectors()
    {
        return $this->connectors;
    }

    /**
     * @return string
     */
    public function getDefinition()
    {
        return $this->definition;
    }

    /**
     * @return string
     */
    public function getMetadataDaoFactory()
    {
        return $this->metaDataDAOFactory;
    }

    /**
     * @return string
     */
    public function getConnectorConfig()
    {
        return $this->connectorConfig;
    }

    /**
     * @param string $value
     * @return \CrudGenerator\MetaData\Sources\MetadataConfig
     */
    public function setConnector($value)
    {
        $this->connector = $value;

        return $this;
    }

    /**
     * @param string $value
     * @return \CrudGenerator\MetaData\Sources\MetadataConfig
     */
    public function setConnectorConfig($value)
    {
        $this->connectorConfig = $value;

        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return array(
            'uniqueName'         => $this->getUniqueName(),
            'metadataDaoFactory' => $this->getMetadataDaoFactory(),
            'connector'          => $this->connector
        );
    }
}
