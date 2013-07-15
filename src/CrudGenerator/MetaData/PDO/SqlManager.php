<?php
namespace CrudGenerator\MetaData\PDO;

use RuntimeException;

/**
 * Manager SQL in differente database environnement
 *
 * @author Stéphane Demonchaux
 */
class SqlManager
{
    /**
     * @var array Database type supported
     */
    private static $type = array(
        'pgsql',
        'sqlite2'
    );
    /**
     * @var array Sql query to get all tables in database
     */
    private static $allMetadataSql = array(
        'pgsql'   => "select table_name from information_schema.tables where table_schema = 'intranet'",
        'sqlite2' => 'SELECT name as table_name FROM sqlite_master WHERE type = "table"'
    );
    /**
     * @var array Sql query to get all column in particular table
     */
    private static $listFieldsQuery = array(
        'pgsql'   => "SELECT column_name as name, is_nullable, data_type as type, character_maximum_length FROM information_schema.columns WHERE table_name = ?;",
        'sqlite2' => 'PRAGMA table_info(?);'
    );

    /**
     * Get all tables in database
     *
     * @param string $type Database type
     * @throws RuntimeException
     * @return string
     */
    public function getAllMetadata($type)
    {
        if(!in_array($type, self::$type)) {
            throw new RuntimeException('Sql type not allowed ' . $type);
        }

        return self::$allMetadataSql[$type];
    }

    /**
     * Get all column in particular table
     *
     * @param string $type Database type
     * @throws RuntimeException
     * @return string
     */
    public function listFieldsQuery($type)
    {
        if(!in_array($type, self::$type)) {
            throw new RuntimeException('Sql type not allowed ' . $type);
        }

        return self::$listFieldsQuery[$type];
    }
}