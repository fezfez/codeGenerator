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
namespace CrudGenerator\MetaData\Sources\PostgreSQL;

use PDO;
use CrudGenerator\MetaData\Sources\PostgreSQL\PostgreSQLConfig;
use CrudGenerator\MetaData\Sources\PostgreSQL\SqlManager;
use CrudGenerator\MetaData\Sources\MetaDataDAOInterface;
use CrudGenerator\MetaData\Sources\PostgreSQL\MetadataDataObjectPostgreSQL;
use CrudGenerator\MetaData\DataObject\MetaDataCollection;
use CrudGenerator\MetaData\DataObject\MetaDataColumnCollection;
use CrudGenerator\MetaData\DataObject\MetaDataColumn;
use CrudGenerator\MetaData\DataObject\MetaDataRelationCollection;

/**
 * PostgreSQL adapter
 */
class PostgreSQLMetaDataDAO implements MetaDataDAOInterface
{
    private $typeConversion = array(
        'character varying' => 'text'
    );
    /**
     * @var PDO Pdo stmt
     */
    private $pdo       = null;
    /**
     * @var PostgreSQLConfig Pdo configuration
     */
    private $pdoConfig = null;
    /**
     * @var SqlManager Sql manager
     */
    private $sqlManager = null;

    /**
     * PostgreSQL adapter
     * @param PDO $pdo
     * @param PostgreSQLConfig $pdoConfig
     * @param SqlManager $sqlManager
     */
    public function __construct(PDO $pdo, PostgreSQLConfig $pdoConfig, SqlManager $sqlManager)
    {
        $this->pdo        = $pdo;
        $this->pdoConfig  = $pdoConfig;
        $this->sqlManager = $sqlManager;
    }

    /**
     * Get all metadata from PostgreSQL
     *
     * @return \CrudGenerator\MetaData\MetaDataCollection
     */
    public function getAllMetadata()
    {
        $sth = $this->pdo->prepare(
            $this->sqlManager->getAllMetadata(
                $this->pdoConfig->getType()
            )
        );

        $sth->execute();

        return $this->pdoMetadataToGeneratorMetadata(
            $sth->fetchAll(
                PDO::FETCH_COLUMN,
                0
            )
        );
    }

    /**
     * Convert PostgreSQL mapping to CrudGenerator mapping
     * @param array $metadataCollection
     * @return \CrudGenerator\MetaData\DataObject\MetaDataCollection
     */
    private function pdoMetadataToGeneratorMetadata(array $metadataCollection)
    {
        $metaDataCollection = new MetaDataCollection();

        foreach ($metadataCollection as $tableName) {
            $metaDataCollection->append(
                $this->hydrateDataObject($tableName)
            );
        }

        return $metaDataCollection;
    }

    /**
     * Convert PostgreSQL mapping to CrudGenerator mapping
     * @param string $tableName
     * @return MetadataDataObjectPostgreSQL
     */
    private function hydrateDataObject($tableName)
    {
        $dataObject = new MetadataDataObjectPostgreSQL(
            new MetaDataColumnCollection(),
            new MetaDataRelationCollection()
        );
        $dataObject->setName($tableName);
        $columnDataObject = new MetaDataColumn();

        $statement = $this->pdo->prepare(
            $this->sqlManager->listFieldsQuery(
                $this->pdoConfig->getType()
            )
        );
        $statement->execute(array($tableName));
        $allFields = $statement->fetchAll(PDO::FETCH_ASSOC);
        $identifiers = $this->getIdentifiers($tableName);

        foreach ($allFields as $metadata) {
            $column = clone $columnDataObject;

            $type = $metadata['type'];

            if (isset($this->typeConversion[$type])) {
                $type = $this->typeConversion[$type];
            }

            $column->setName($metadata['name'])
                   ->setType($type)
                   ->setLength(
                       isset($metadata['character_maximum_length']) ?
                       $metadata['character_maximum_length'] :
                       null
                   );
            if (in_array($metadata['name'], $identifiers)) {
                $column->setPrimaryKey(true);
            }

            $dataObject->appendColumn($column);
        }

        return $dataObject;
    }

    private function getIdentifiers($tableName)
    {
        $identifiers = array();

        $statement = $this->pdo->prepare(
            $this->sqlManager->getAllPrimaryKeys(
                $this->pdoConfig->getType()
            )
        );
        $statement->execute(array($tableName));

        $constraintList = $statement->fetchAll(PDO::FETCH_ASSOC);
        foreach ($constraintList as $containt) {
            $identifiers[] = $containt['column_name'];
        }

        return $identifiers;
    }

    /**
     * Get particularie metadata from PDO
     *
     * @param string $tableName
     * @return \CrudGenerator\MetaData\Sources\PostgreSQL\MetadataDataObjectPostgreSQL
     */
    public function getMetadataFor($tableName, array $parentName = array())
    {
        return $this->hydrateDataObject($tableName);
    }
}
