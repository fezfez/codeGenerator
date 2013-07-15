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
namespace CrudGenerator\MetaData\PDO;

use PDO;
use CrudGenerator\MetaData\PDO\PDOConfig;
use CrudGenerator\MetaData\PDO\SqlManager;
use CrudGenerator\MetaData\MetaDataDAOInterface;
use CrudGenerator\MetaData\PDO\MetadataDataObjectPDO;
use CrudGenerator\MetaData\DataObject\MetaDataDataObjectCollection;
use CrudGenerator\MetaData\DataObject\MetaDataColumnDataObjectCollection;
use CrudGenerator\MetaData\DataObject\MetaDataColumnDataObject;
use CrudGenerator\MetaData\DataObject\MetaDataRelationDataObjectCollection;

/**
 * PDO adapter
 */
class PDOMetaDataDAO implements MetaDataDAOInterface
{
    /**
     * @var PDO Pdo stmt
     */
    private $pdo       = null;
    /**
     * @var PDOConfig Pdo configuration
     */
    private $pdoConfig = null;
    /**
     * @var SqlManager Sql manager
     */
    private $sqlManager = null;

    /**
     * PDO adapter
     * @param PDO $pdo
     * @param PDOConfig $pdoConfig
     * @param SqlManager $sqlManager
     */
    public function __construct(PDO $pdo, PDOConfig $pdoConfig, SqlManager $sqlManager)
    {
        $this->pdo        = $pdo;
        $this->pdoConfig  = $pdoConfig;
        $this->sqlManager = $sqlManager;
    }

    /**
     * Get all metadata from PDO
     *
     * @return \CrudGenerator\MetaData\MetaDataDataObjectCollection
     */
    public function getAllMetadata()
    {
        $sth = $this->pdo->prepare(
            $this->sqlManager->getAllMetadata(
                $this->pdoConfig->getType()
            )
        );
        $sth->execute();

        $allTables = $sth->fetchAll();

        return $this->pdoMetadataToGeneratorMetadata($allTables);
    }

    /**
     * Convert PDOmapping to CrudGenerator mapping
     * @param array $metadataCollection
     * @return \CrudGenerator\MetaData\DataObject\MetaDataDataObjectCollection
     */
    private function pdoMetadataToGeneratorMetadata(array $metadataCollection)
    {
        $metaDataCollection = new MetaDataDataObjectCollection();
        $dataObject = new MetadataDataObjectPDO(
            new MetaDataColumnDataObjectCollection(),
            new MetaDataRelationDataObjectCollection()
        );

        foreach ($metadataCollection as $metadata) {
            $realDataObject = clone $dataObject;
            $realDataObject->setName($metadata['table_name']);
            $metaDataCollection->append($realDataObject);
        }

        return $metaDataCollection;
    }

    /**
     * Convert PDOmapping to CrudGenerator mapping
     * @param string $tableName
     * @return MetadataDataObjectPDO
     */
    private function hydrateDataObject($tableName)
    {
        $dataObject = new MetadataDataObjectPDO(
            new MetaDataColumnDataObjectCollection(),
            new MetaDataRelationDataObjectCollection()
        );
        $dataObject->setName($tableName);
        $columnDataObject = new MetaDataColumnDataObject();

        $statement = $this->pdo->prepare($this->sqlManager->listFieldsQuery($this->pdoConfig->getType()));
        $statement->execute(array($tableName));
        $allFields = $statement->fetchAll(PDO::FETCH_ASSOC);

        foreach ($allFields as $metadata) {
            $column = clone $columnDataObject;
            $column->setName($metadata['name'])
                   ->setType($metadata['type'])
                   ->setLength(
                       isset($metadata['character_maximum_length']) ?
                       $metadata['character_maximum_length'] :
                       null
                   );
            $dataObject->appendColumn($column);
        }

        $databaseType = $this->pdoConfig->getType();
        if ($databaseType === 'pgsql') {
            $contraintQuery =  'SELECT contype as "type", conkey as "columnNumber"
                FROM pg_class r, pg_constraint c
                WHERE r.oid = c.conrelid
                AND relname = ?;';
            $statement = $this->pdo->prepare($contraintQuery);
            $statement->execute(array($tableName));

            $constraintList = $statement->fetchAll(PDO::FETCH_ASSOC);

            foreach ($constraintList as $number => $containt) {
                if ($containt['type'] === 'p') {
                    $columnNumbers = explode(
                        ',',
                        str_replace(array('{', '}'), array('', ''), $containt['columnNumber'])
                    );
                    foreach ($columnNumbers as $number) {
                        $dataObject->addIdentifier($allFields[($number - 1)]['name']);
                    }
                }
            }
        }

        return $dataObject;
    }

    /**
     * Get particularie metadata from PDO
     *
     * @param string $tableName
     * @return \CrudGenerator\MetaData\PDO\MetadataDataObjectPDO
     */
    public function getMetadataFor($tableName)
    {
        return $this->hydrateDataObject($tableName);
    }
}
