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

use CrudGenerator\MetaData\Sources\PostgreSQL\SqlManager;

/**
 * Create PostgreSQL Metadata DAO instance
 *
 * @CodeGenerator\Description PostgreSQL
 */
class PostgreSQLMetaDataDAOFactory
{
    /**
     * Create PostgreSQL Metadata DAO instance
     *
     * @param PostgreSQLConfig $config
     * @return \CrudGenerator\MetaData\Sources\PDO\PDOMetaDataDAO
     */
    public static function getInstance(PostgreSQLConfig $config)
    {
        $DSN = 'pgsql:dbname=' . $config->getDatabaseName() . ';host=' . $config->getHost();
        $pdo = new \PDO(
            $DSN,
            $config->getUser(),
            $config->getPassword()
        );
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        return new PostgreSQLMetaDataDAO(
            $pdo,
            $config,
            new SqlManager()
        );
    }

    /**
     * Check if dependence are complete
     * @return boolean
     */
    public static function checkDependencies()
    {
        return true;
    }
}
