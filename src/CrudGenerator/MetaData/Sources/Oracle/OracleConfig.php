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
namespace CrudGenerator\MetaData\Sources\Oracle;

use CrudGenerator\MetaData\Sources\MetadataConfigDatabase;
use CrudGenerator\MetaData\Sources\MetaDataConfig;
use CrudGenerator\MetaData\Config\ConfigException;

/**
 * Oracle configuration for Oracle Metadata adapter
 *
 * @author Stéphane Demonchaux
 */
class OracleConfig extends MetadataConfigDatabase implements MetaDataConfig, \JsonSerializable
{
    /**
     * @var string Config definition
     */
    private $definition = 'For use the Oracle adapter you need to define the database and how to get the PDO instance';
    /**
     * @var string
     */
    private $metaDataDAOFactory = 'CrudGenerator\MetaData\Sources\Oracle\OracleMetaDataDAOFactory';

    /* (non-PHPdoc)
     * @see \CrudGenerator\MetaData\Sources\MetaDataConfig::getConnection()
    */
    public function getConnection()
    {
        $pdo = new \PDO('//' . $this->configHost . '/' . $this->configDatabaseName, $this->configUser, $this->configPassword);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        return $pdo;
    }
    /* (non-PHPdoc)
     * @see \CrudGenerator\MetaData\Sources\MetaDataConfig::test()
    */
    public function test()
    {
        try {
            $this->getConnection();
        } catch (\PDOException $e) {
            throw new ConfigException('Connection failed with "' . $e->getMessage() . '"');
        }
    }
    /* (non-PHPdoc)
     * @see \CrudGenerator\MetaData\Sources\MetaDataConfig::getUniqueName()
    */
    public function getUniqueName()
    {
        return 'Oracle ' . $this->configHost . ' ' . $this->configUser;
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
            'configPassword'     => $this->configPort,
            'metaDataDAOFactory' => $this->metaDataDAOFactory,
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
