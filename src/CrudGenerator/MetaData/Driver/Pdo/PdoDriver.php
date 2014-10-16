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
namespace CrudGenerator\MetaData\Driver\Pdo;

use CrudGenerator\MetaData\Config\ConfigException;
use CrudGenerator\MetaData\Driver\DriverConfig;
use CrudGenerator\MetaData\Driver\DriverInterface;

/**
 * Json configuration for Json Metadata adapter
 *
 * @author Stéphane Demonchaux
 */
class PdoDriver implements DriverInterface
{
    /**
     * @var string
     */
    const MYSQL = 'MySql';
    /**
     * @var string
     */
    const POSTGRESQL = 'PostgreSQL';
    /**
     * @var string
     */
    const ORACLE = 'Oracle';

    /**
     * @param DriverConfig $config
     * @throws ConfigException
     * @throws \Exception
     * @return \PDO
     */
    public function getConnection(DriverConfig $config)
    {
        if ($config->getResponse('configHost') === null || $config->getResponse('configDatabaseName') === null) {
            throw new ConfigException('Empty connection');
        }

        try {
            if ($config->getResponse('dsn') === self::MYSQL) {
                $dsn  = 'mysql:host=' . $config->getResponse('configHost') . ';dbname=';
            } elseif ($config->getResponse('dsn') ===  self::POSTGRESQL) {
                $dsn = 'pgsql:host=' . $config->getResponse('configHost') . ';dbname=';
            } elseif ($config->getResponse('dsn') === self::ORACLE) {
                $dsn = '//' . $config->getResponse('configHost') . '/';
            } else {
                throw new \Exception('Dsn not found');
            }

            $dsn .= $config->getResponse('configDatabaseName');

            return new \PDO(
                $dsn,
                $config->getResponse('configUser'),
                $config->getResponse('configPassword'),
                array(
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                )
            );
        } catch (\RuntimeException $e) {
            throw new ConfigException($e->getMessage());
        }
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\MetaData\Driver\DriverInterface::isValid()
     */
    public function isValid(DriverConfig $driverConfig)
    {
        $this->getConnection($config);
        return true;
    }
}
