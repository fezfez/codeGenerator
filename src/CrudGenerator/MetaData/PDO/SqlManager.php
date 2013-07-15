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