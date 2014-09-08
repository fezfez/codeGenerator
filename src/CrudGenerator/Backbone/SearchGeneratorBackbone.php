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
namespace CrudGenerator\Backbone;

use CrudGenerator\Context\ContextInterface;
use CrudGenerator\Generators\Search\GeneratorSearch;
use CrudGenerator\Generators\Installer\GeneratorInstallerInterface;
use CrudGenerator\Generators\Detail\GeneratorDetail;

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
            $this->generatorInstaller->install($package['name']);
        }
    }
}
