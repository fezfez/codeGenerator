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
namespace CrudGenerator\GeneratorsEmbed\ArchitectGenerator;

use CrudGenerator\GeneratorsEmbed\ArchitectGenerator\Architect;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Output\OutputInterface;

class MetadataToArray
{
    /**
     * @var DialogHelper
     */
    private $dialog = null;
    /**
     * @var OutputInterface
     */
    private $output = null;

    /**
     * @param DialogHelper $dialog
     * @param OutputInterface $output
     */
    public function __construct(DialogHelper $dialog, OutputInterface $output)
    {
        $this->dialog = $dialog;
        $this->output = $output;
    }

    /**
     * @param Architect $DTO
     * @return Architect
     */
    public function ask(Architect $DTO)
    {
        foreach ($DTO->getMetadata()->getColumnCollection() as $column) {
            if (null === $DTO->getAttributeName($column->getName())) {
                $DTO->setAttributeName(
                    $column->getName(),
                    $this->dialog->ask(
                        $this->output,
                        '<question>Attribute name for "' . $column->getName() . '"</question> ',
                        $column->getName()
                    )
                );
            }
        }

        foreach ($DTO->getMetadata()->getRelationCollection() as $relation) {
            $addRelation = $this->dialog->askConfirmation(
                $this->output,
                '<question>Do you want to add relation "' . $relation->getName() . '" in DataObject</question> ',
                $relation->getName()
            );

            if ($addRelation === true) {
                $arrayRelation = array();
                $name = $this->dialog->ask(
                    $this->output,
                    '<question>Attribute name for "' . $relation->getName() . '"</question> ',
                    $relation->getName()
                );
                $arrayRelation[$name] = array();
                foreach ($relation->getMetadata()->getColumnCollection() as $column) {
                    $arrayRelation[$name]['columns'][$column->getName()] = $this->dialog->ask(
                        $this->output,
                        '<question>Attribute name for "' . $column->getName() . '"</question> ',
                        $column->getName()
                    );
                }
                $arrayRelation[$name]['persistanceStrategy'] = $this->dialog->select(
                	$this->output,
                	'Persistance strategy, retrive or persist ?',
                	array('retrive', 'persist')
                );

                $DTO->setAttributeName(
                    $relation->getName(),
                    $arrayRelation
                );
            }
        }

        return $DTO;
    }
}