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
namespace CrudGenerator\Utils;

use RuntimeException;
use Composer\Script\Event;

/**
 * Installer
 *
 * @author Stéphane Demonchaux
 */
class Installer
{
    /**
     * @return array
     */
    public static function getDirectories()
    {
        $app = array();

        require __DIR__.'/../../../silex/resources/config/prod.php';

        $directoriestoCreate = array(
            'Cache'   => $app['cache.path'],
            'History' => \CrudGenerator\History\HistoryManager::HISTORY_PATH,
            'Config'  => \CrudGenerator\MetaData\Config\MetaDataConfigDAO::PATH
        );

        return $directoriestoCreate;
    }

    /**
     * @param PackageEvent $event
     */
    public static function postPackageInstall(Event $event)
    {
        $directoriestoCreate = self::getDirectories();

        foreach ($directoriestoCreate as $directoryName => $directoryPath) {
            if (is_dir($directoryPath) !== true) {
                mkdir($directoryPath, 0777, true);
            }
        }
    }

    /**
     * @throws RuntimeException
     */
    public static function checkInstall()
    {
        $directoriestoCreate = self::getDirectories();

        foreach ($directoriestoCreate as $directoryName => $directoryPath) {
            if (is_writable($directoryPath) !== true) {
                throw new RuntimeException(
                    sprintf(
                        '%s directory "%s" is not writable',
                        $directoryName,
                        $directoryPath
                    )
                );
            }
        }
    }
}
