<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Metadata\Sources\PostgreSQL;

/**
 * Manager SQL in differente database environnement
 *
 * @author Stéphane Demonchaux
 */
class SqlManager
{
    /**
     * Get all tables in database
     *
     * @return string
     */
    public function getAllMetadata()
    {
        return "SELECT table_name
                FROM information_schema.tables
                WHERE table_schema = 'public'";
    }

    /**
     * Get all column in particular table
     *
     * @return string
     */
    public function listFieldsQuery()
    {
        return "SELECT column_name as name, is_nullable, data_type as type, character_maximum_length
                      FROM information_schema.columns
                      WHERE table_name = ?;";
    }

    /**
     * Get all primary keys in particular table
     *
     * @return string
     */
    public function getAllPrimaryKeys()
    {
        return "SELECT c.column_name
                FROM information_schema.table_constraints tc
                    JOIN information_schema.constraint_column_usage AS ccu USING (constraint_schema, constraint_name)
                    JOIN information_schema.columns AS c ON c.table_schema = tc.constraint_schema
                        AND tc.table_name = c.table_name AND ccu.column_name = c.column_name
                WHERE constraint_type = 'PRIMARY KEY' and tc.table_name = ?";
    }
}
