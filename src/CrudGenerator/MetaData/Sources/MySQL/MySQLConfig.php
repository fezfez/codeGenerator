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

use CrudGenerator\MetaData\Sources\MetaDataConfig;
use CrudGenerator\MetaData\Config\ConfigException;

/**
 * MySQL configuration for MySQL Metadata adapter
 *
 * @author Stéphane Demonchaux
 */
class MySQLConfig implements MetaDataConfig, \JsonSerializable
{
    /**
     * @var string Config definition
     */
    private $definition = 'For use the MySQL adapter you need to define the database and how to get the MySQL instance';
    /**
     * Database Name
     *
     * @var string
     */
    private $configDatabaseName = null;
    /**
     * Host
     *
     * @var string
     */
    private $configHost = null;
    /**
     * User
     *
     * @var string
     */
    private $configUser = null;
    /**
     * Password
     *
     * @var string
     */
    private $configPassword = null;
    /**
     * Port
     *
     * @var string
     */
    private $configPort = null;
    /**
     * @var string
     */
    private $metaDataDAOFactory = 'CrudGenerator\MetaData\Sources\MySQL\MySQLMetaDataDAOFactory';

    /**
     * Set database name
     * @param string $value
     * @return MySQLConfig
     */
    public function setConfigDatabaseName($value)
    {
        $this->configDatabaseName = $value;
        return $this;
    }
    /**
     * Set host
     * @param string $value
     * @return MySQLConfig
     */
    public function setConfigHost($value)
    {
        $this->configHost = $value;
        return $this;
    }
    /**
     * Set user
     * @param string $value
     * @return MySQLConfig
     */
    public function setConfigUser($value)
    {
        $this->configUser = $value;
        return $this;
    }
    /**
     * Set password
     * @param string $value
     * @return MySQLConfig
     */
    public function setConfigPassword($value)
    {
        $this->configPassword = $value;
        return $this;
    }
    /**
     * Set port
     * @param string $value
     * @return MySQLConfig
     */
    public function setConfigPort($value)
    {
        $this->configPort = $value;
        return $this;
    }

    /**
     * Get database name
     * @return string
     */
    public function getConfigDatabaseName()
    {
        return $this->configDatabaseName;
    }
    /**
     * Get host
     * @return string
     */
    public function getConfigHost()
    {
        return $this->configHost;
    }
    /**
     * Get user
     * @return string
     */
    public function getConfigUser()
    {
        return $this->configUser;
    }
    /**
     * Get password
     * @return string
     */
    public function getConfigPassword()
    {
        return $this->configPassword;
    }
    /**
     * Get port
     * @return string
     */
    public function getConfigPort()
    {
        return $this->configPort;
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\MetaData\Sources\MetaDataConfig::getConnection()
     */
    public function getConnection()
    {
        if ($this->configHost === null || $this->configDatabaseName === null) {
            throw new ConfigException('Empty connection');
        }
        $pdo = new \PDO('mysql:host='.$this->configHost . ';dbname='.$this->configDatabaseName, $this->configUser, $this->configPassword, array(
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        ));

        return $pdo;
    }
    /* (non-PHPdoc)
     * @see \CrudGenerator\MetaData\Sources\MetaDataConfig::test()
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
     * @see \CrudGenerator\MetaData\Sources\MetaDataConfig::getUniqueName()
     */
    public function getUniqueName()
    {
        return 'MySQL ' . $this->configHost . ' ' . $this->configUser;
    }
    /* (non-PHPdoc)
     * @see \CrudGenerator\MetaData\Sources\MetaDataConfig::getDefinition()
    */
    public function getDefinition()
    {
        return $this->definition;
    }
    /* (non-PHPdoc)
     * @see \CrudGenerator\MetaData\Sources\MetaDataConfig::jsonSerialize()
    */
    public function jsonSerialize()
    {
        return array(
            'configDatabaseName' => $this->configDatabaseName,
            'configHost'         => $this->configHost,
            'configUser'         => $this->configUser,
            'configPassword'     => $this->configPassword,
            'configPort'         => $this->configPort,
            'metaDataDAOFactory' => $this->metaDataDAOFactory,
            'uniqueName'         => $this->getUniqueName()
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
    /**
     * Set MetaDataDAOFactory
     *
     * @return MetaDataConfig
     */
    public function setMetaDataDAOFactory($value)
    {

    }
}
