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
namespace CrudGenerator\Command\Questions;

use CrudGenerator\MetaData\MetaDataSourceFinder;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\DialogHelper;

class MetaDataSourcesQuestion
{
    /**
     * @var MetaDataSourceFinder
     */
    private $metadataSourceFinder = null;
    /**
     * @var OutputInterface
     */
    private $output = null;
    /**
     * @var DialogHelper
     */
    private $dialog = null;
    /**
     * @param MetaDataSourceFinder $metadataSourceFinder
     * @param OutputInterface $output
     * @param DialogHelper $dialog
     */
    public function __construct(
        MetaDataSourceFinder $metadataSourceFinder,
        OutputInterface $output,
        DialogHelper $dialog
    ) {
        $this->metadataSourceFinder = $metadataSourceFinder;
        $this->output = $output;
        $this->dialog = $dialog;
    }

    /**
     * Ask wich MetaData Source you want to use
     * @return MetaDataSource
     */
    public function ask()
    {
        $adaptersCollection = $this->metadataSourceFinder->getAllAdapters();
        $adaptersChoices    = array();

        foreach ($adaptersCollection as $adapter) {
            $falseDependencies = $adapter->getFalseDependencies();

            if (!empty($falseDependencies)) {
                $this->output->writeln(
                    '<error>Dependencies not complet for use adapter "' . $adapter->getName() . '" caused by</error>'
                );
                $this->output->writeln('<error> * ' . $falseDependencies . '</error>');
            } else {
                $adaptersChoices[$adapter->getDefinition()] = $adapter;
            }
        }

        $adaptersKeysChoices = array_keys($adaptersChoices);
        $choice = $this->dialog->select(
            $this->output,
            "<question>Choose an adapter</question> \n> ",
            $adaptersKeysChoices
        );

        return $adaptersChoices[$adaptersKeysChoices[$choice]];
    }
}
