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
namespace CrudGenerator\MetaData;

use CrudGenerator\MetaData\Sources\MetaDataConfigInterface;
use CrudGenerator\MetaData\Sources\MetaDataDAOCache;
use CrudGenerator\Utils\Installer;
use CrudGenerator\MetaData\Driver\DriverConfig;

/**
 * MetaData source factory
 * @author Stéphane Demonchaux
 */
class MetaDataSourceFactory
{
    /**
     * @param string $metadataSourceFactoryName
     * @param DriverConfig $config
     * @param boolean $noCache
     * @return \CrudGenerator\MetaData\Sources\MetaDataDAOCache
     */
    public function create($metadataSourceFactoryName, DriverConfig $config = null, $noCache = false)
    {
        if (null !== $config) {
            $driverFactory  = $config->getDriver();
            $driver         = $driverFactory::getInstance();
            $metadataSource = $metadataSourceFactoryName::getInstance($driver, $config);
        } else {
            $metadataSource = $metadataSourceFactoryName::getInstance();
        }

        return new MetaDataDAOCache(
            $metadataSource,
            Installer::getDirectories(),
            $config,
            $noCache
        );
    }
}
