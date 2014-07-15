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
     * @var string Database Name
     */
    private $databaseName = null;
    /**
     * @var string Host
     */
    private $host = null;
    /**
     * @var string User
     */
    private $user = null;
    /**
     * @var string Password
     */
    private $password = null;
    /**
     * @var string Port
     */
    private $port = null;
    /**
     * @var string
     */
    private $metaDataDAOFactory = 'CrudGenerator\MetaData\Sources\MySQL\MySQLMetaDataDAOFactory';

    /**
     * Set database name
     * @param string $value
     * @return MySQLConfig
     */
    public function setDatabaseName($value)
    {
        $this->databaseName = $value;
        return $this;
    }
    /**
     * Set host
     * @param string $value
     * @return MySQLConfig
     */
    public function setHost($value)
    {
        $this->host = $value;
        return $this;
    }
    /**
     * Set user
     * @param string $value
     * @return MySQLConfig
     */
    public function setUser($value)
    {
        $this->user = $value;
        return $this;
    }
    /**
     * Set password
     * @param string $value
     * @return MySQLConfig
     */
    public function setPassword($value)
    {
        $this->password = $value;
        return $this;
    }
    /**
     * Set port
     * @param string $value
     * @return MySQLConfig
     */
    public function setPort($value)
    {
        $this->port = $value;
        return $this;
    }

    /**
     * Get database name
     * @return string
     */
    public function getDatabaseName()
    {
        return $this->databaseName;
    }
    /**
     * Get host
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }
    /**
     * Get user
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }
    /**
     * Get password
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }
    /**
     * Get port
     * @return string
     */
    public function getPort()
    {
        return $this->port;
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\MetaData\Sources\MetaDataConfig::getConnection()
     */
    public function getConnection()
    {
        if ($this->host === null || $this->databaseName === null) {
            throw new ConfigException('Empty connection');
        }
        $pdo = new \PDO('mysql:host='.$this->host . ';dbname='.$this->databaseName, $this->user, $this->password, array(
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
        return 'MySQL ' . $this->host . ' ' . $this->user;
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
            'databaseName'       => $this->databaseName,
            'host'               => $this->host,
            'user'               => $this->user,
            'password'           => $this->password,
            'port'               => $this->port,
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
