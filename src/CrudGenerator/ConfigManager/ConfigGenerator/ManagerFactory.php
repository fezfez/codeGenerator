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
namespace CrudGenerator\ConfigManager\ConfigGenerator;

use CrudGenerator\ConfigManager\ConfigGenerator\Manager\ConfigGenerator;
use CrudGenerator\Utils\FileManager;
use Symfony\Component\Yaml\Yaml;
use CrudGenerator\ConfigManager\ConfigGenerator\InvalidConfigPathException;

/**
 * Create a ConfigGenerator manager instance
 *
 * @author Anthony Rochet
 */
class ManagerFactory
{
    /**
     * Create a ConfigGenerator manager instance
     * @param string $pathToConf
     * @throws InvalidConfigPathException
     * @return \CrudGenerator\ConfigManager\ConfigGenerator\Manager\ConfigGenerator
     */
    public static function getInstance($pathToConf)
    {
        try {
            $fileManager = new FileManager();
            $configContent = $fileManager->fileGetContent($pathToConf . 'ConfigGenerator.yml');
            $yamlDatas = Yaml::parse($configContent, true);
        } catch (\RuntimeException $exception) {
            throw new InvalidConfigPathException(
                'The given path is not valid, application cannot open the config file from : ' .
                $pathToConf .
                'ConfigGenerator.yaml'
            );
        }

        return new ConfigGenerator($yamlDatas);
    }

    /**
     * @param string $pathToConf
     * @return \CrudGenerator\ConfigManager\ConfigGenerator\Manager\ConfigGenerator
     */
    public function create($pathToConf)
    {
        return self::getInstance($pathToConf);
    }
}
