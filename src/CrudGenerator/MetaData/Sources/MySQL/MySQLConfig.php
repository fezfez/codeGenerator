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
namespace CrudGenerator\MetaData\Sources\MySQL;

use CrudGenerator\MetaData\Sources\MetadataConfigDatabase;
use CrudGenerator\MetaData\Sources\MetaDataConfigInterface;
use CrudGenerator\MetaData\Config\ConfigException;

/**
 * MySQL configuration for MySQL Metadata adapter
 *
 * @author Stéphane Demonchaux
 */
class MySQLConfig extends MetadataConfigDatabase implements MetaDataConfigInterface, \JsonSerializable
{
    /**
     * @var string Config definition
     */
    private $definition = 'For use the MySQL adapter you need to define the database and how to get the MySQL instance';
    /**
     * @var string
     */
    private $metaDataDAOFactory = 'CrudGenerator\MetaData\Sources\MySQL\MySQLMetaDataDAOFactory';

    /* (non-PHPdoc)
     * @see \CrudGenerator\MetaData\Sources\MetaDataConfigInterface::getConnection()
     */
    public function getConnection()
    {
        if ($this->configHost === null || $this->configDatabaseName === null) {
            throw new ConfigException('Empty connection');
        }

        $pdo = new \PDO(
            'mysql:host='.$this->configHost . ';dbname=' . $this->configDatabaseName,
            $this->configUser,
            $this->configPassword,
            array(
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            )
        );

        return $pdo;
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
        return 'MySQL ' . $this->configHost . ' ' . $this->configUser;
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
                'metaDataDAOFactory' => $this->metaDataDAOFactory,
                'uniqueName'         => $this->getUniqueName()
            ),
            parent::jsonSerialize()
        );
    }

    /**
     * Get MetaDataDAOFactory
     *
     * @return string
     */
    public function getMetaDataDAOFactory()
    {
        return $this->metaDataDAOFactory;
    }
}
