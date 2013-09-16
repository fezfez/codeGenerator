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

namespace CrudGenerator\Command;

use CrudGenerator\MetaData\DataObject\MetaDataRelationColumn;

use CrudGenerator\Command\Questions\GeneratorQuestion;
use CrudGenerator\MetaData\DataObject\MetaDataColumn;
use CrudGenerator\MetaData\DataObject\MetaDataRelationCollection;
use CrudGenerator\MetaData\DataObject\MetaDataColumnCollection;
use CrudGenerator\MetaData\Sources\Doctrine2\MetadataDataObjectDoctrine2;
use CrudGenerator\MetaData\Sources\PDO\MetadataDataObjectPDO;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\DialogHelper;

/**
 * generator-sand-box command
 *
 * @author Stéphane Demonchaux
 */
class GeneratorSandBoxCommand extends Command
{
    /**
     * @var GeneratorQuestion
     */
    private $generatorQuestion = null;

    /**
     * @param GeneratorQuestion $generatorQuestion
     */
    public function __construct(GeneratorQuestion $generatorQuestion)
    {
        $this->generatorQuestion = $generatorQuestion;
        parent::__construct('CodeGenerator:generator-sand-box');
    }
    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Console\Command.Command::configure()
     */
    protected function configure()
    {
        parent::configure();

        // @todo -- filter
        $this->setName('CodeGenerator:generator-sand-box')
             ->setDescription('Allow you to test generator with fake metadata');
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Console\Command.Command::execute()
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $generator = $this->generatorQuestion->ask();
        $metadata  = $this->buildFakeMetaData();
        $DTOName   = $generator->getDTO();

        $dataObject = new $DTOName();
        $dataObject->setEntity($metadata->getName())
                   ->setModule('data')
                   ->setMetaData($metadata);

        $generator->generate($dataObject);
    }

    /**
     * @return MetadataDataObjectDoctrine2
     */
    private function buildFakeMetaData()
    {
        $metadata = new MetadataDataObjectPDO(
            new MetaDataColumnCollection(),
            new MetaDataRelationCollection()
        );

        $column = new MetaDataColumn();
        $column->setName('id')
        ->setNullable(true)
        ->setType('integer')
        ->setLength('100')
        ->setPrimaryKey(true);

        $metadata->appendColumn($column);
        $metadata->setName('my_name');

        $column = new MetaDataColumn();
        $column->setName('tetze')
        ->setNullable(true)
        ->setType('integer')
        ->setLength('100');

        $metadata->appendColumn($column);

        $column = new MetaDataColumn();
        $column->setName('myDate')
        ->setNullable(true)
        ->setType('date')
        ->setLength('100');

        $metadata->appendColumn($column);

        $column = new MetaDataColumn();
        $column->setName('my_data')
        ->setNullable(true)
        ->setType('text')
        ->setLength('100');

        $metadata->appendColumn($column);

        $relation = new MetaDataRelationColumn();
        $relation->setAssociationType('ontomany')
                 ->setFieldName('myName')
                 ->setFullName('myFullName');

        $metadata->appendRelation($relation);

        return $metadata;
    }
}
