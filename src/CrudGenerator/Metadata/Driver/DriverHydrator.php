<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
*/
namespace CrudGenerator\Metadata\Driver;

class DriverHydrator
{
    /**
     * @param  array        $config
     * @return DriverConfig
     */
    public function arrayToDto(array $config)
    {
        $driverConfig = new DriverConfig($config[DriverConfig::UNIQUE_NAME]);
        $driverConfig->setMetadataDaoFactory($config[DriverConfig::SOURCE_FACTORY]);

        foreach ($config[DriverConfig::RESPONSE] as $responseKey => $response) {
            $driverConfig->response($responseKey, $response);
        }

        return $driverConfig->setDriver($config[DriverConfig::FACTORY]);
    }
}
