<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Backbone;

use CrudGenerator\Context\ContextInterface;
use CrudGenerator\Generators\Detail\GeneratorDetail;
use CrudGenerator\Generators\Installer\GeneratorInstallerInterface;
use CrudGenerator\Generators\Search\GeneratorSearch;

class SearchGeneratorBackbone
{
    /**
     * @var GeneratorSearch
     */
    private $generatorSearch = null;
    /**
     * @var GeneratorInstallerInterface
     */
    private $generatorInstaller = null;
    /**
     * @var GeneratorDetail
     */
    private $generatorDetail = null;
    /**
     * @var ContextInterface
     */
    private $context = null;

    public function __construct(
        GeneratorSearch $generatorSearch,
        GeneratorInstallerInterface $generatorInstaller,
        GeneratorDetail $generatorDetail,
        ContextInterface $context
    ) {
        $this->generatorSearch    = $generatorSearch;
        $this->generatorInstaller = $generatorInstaller;
        $this->generatorDetail    = $generatorDetail;
        $this->context            = $context;
    }

    public function run()
    {
        $package = $this->generatorSearch->ask();

        if ($this->context->confirm('Detail', 'package_detail') === true) {
            $this->context->log(
                $this->generatorDetail->find($package),
                'package_details'
            );
        }

        if ($this->context->confirm('Install', 'install_new_package') === true) {
            $this->generatorInstaller->install($package->getName());
        }
    }
}
