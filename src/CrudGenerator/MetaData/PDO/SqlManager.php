<?php
namespace CrudGenerator\MetaData\PDO;

use RuntimeException;

class SqlManager
{
    private static $type = array(
        'pgsql',
        'sqlite2'
    );

    private static $allMetadataSql = array(
        'pgsql'   => "select table_name from information_schema.tables where table_schema = 'intranet'",
        'sqlite2' => 'SELECT name as table_name FROM sqlite_master WHERE type = "table"'
    );

    private static $listFieldsQuery = array(
        'pgsql'   => "SELECT column_name as name, is_nullable, data_type as type, character_maximum_length FROM information_schema.columns WHERE table_name = ?;",
        'sqlite2' => 'PRAGMA table_info(?);'
    );

    /**
     * @param string $type
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
     * @param string $type
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