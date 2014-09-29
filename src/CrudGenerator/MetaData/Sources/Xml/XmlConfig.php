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
namespace CrudGenerator\MetaData\Sources\Xml;

use CrudGenerator\MetaData\Sources\MetaDataConfigInterface;
use CrudGenerator\MetaData\Config\ConfigException;

/**
 * Json configuration for Json Metadata adapter
 *
 * @author Stéphane Demonchaux
 */
class XmlConfig implements MetaDataConfigInterface, \JsonSerializable
{
    /**
     * @var string Config definition
     */
    private $definition = 'Xml adapter';
    /**
     * @var string
     */
    private $metaDataDAOFactory = 'CrudGenerator\MetaData\Sources\Xml\XmlMetaDataDAOFactory';
    /**
     * Json Url
     *
     * @var string
     */
    private $configUrl = null;

    /**
     * @param string $value
     * @return \CrudGenerator\MetaData\Sources\Json\JsonConfig
     */
    public function setConfigUrl($value)
    {
        $this->configUrl = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getConfigUrl()
    {
        return $this->configUrl;
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\MetaData\Sources\MetaDataConfigInterface::getConnection()
     */
    public function getConnection()
    {
        if ($this->configUrl === null) {
            throw new ConfigException('Empty connection');
        }

        $content = file_get_contents($this->configUrl);

        return $content;
    }
    /* (non-PHPdoc)
     * @see \CrudGenerator\MetaData\Sources\MetaDataConfigInterface::test()
     */
    public function test()
    {
        try {
            $this->getConnection();
        } catch (\Exception $e) {
            throw new ConfigException('Connection failed with "' . $e->getMessage() . '"');
        }
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\MetaData\Sources\MetaDataConfigInterface::getUniqueName()
     */
    public function getUniqueName()
    {
        return 'Xml ' . $this->configUrl;
    }
    /* (non-PHPdoc)
     * @see \CrudGenerator\MetaData\Sources\MetaDataConfigInterface::getDefinition()
    */
    public function getDefinition()
    {
        return $this->definition;
    }
    /* (non-PHPdoc)
     * @see \CrudGenerator\MetaData\Sources\MetaDataConfigInterface::jsonSerialize()
    */
    public function jsonSerialize()
    {
        return array_merge(
            array(
                'metadataDaoFactory' => $this->metaDataDAOFactory,
                'uniqueName'         => $this->getUniqueName(),
                'configUrl'          => $this->configUrl
            )
        );
    }

    /**
     * Get MetaDataDAOFactory
     *
     * @return string
     */
    public function getMetadataDaoFactory()
    {
        return $this->metaDataDAOFactory;
    }
}
