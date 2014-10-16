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
namespace CrudGenerator\MetaData\Driver\File\Web;

use CrudGenerator\Utils\FileManager;
use CrudGenerator\MetaData\Driver\DriverConfig;
use CrudGenerator\MetaData\Driver\Driver;
use CrudGenerator\MetaData\Driver\DriverFactoryInterface;

class WebDriverFactory implements DriverFactoryInterface
{
    /**
     * @return \CrudGenerator\MetaData\Driver\File\Web\WebDriver
     */
    public static function getInstance()
    {
        return new WebDriver(new FileManager());
    }

    /**
     * @return \CrudGenerator\MetaData\Driver\Driver
     */
    public static function getDescription()
    {
        $config = new DriverConfig('Web');
        $config->addQuestion('Url', 'configUrl');
        $config->setDriver(__CLASS__);

        $dataObject = new Driver();
        $dataObject->setConfig($config)
                   ->setDefinition('Web connector')
                   ->setUniqueName('Web');

        return $dataObject;
    }
}
