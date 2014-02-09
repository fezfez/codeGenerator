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

use CrudGenerator\History\HistoryManager;
use CrudGenerator\Generators\Questions\Cli\MetaDataSourcesQuestion;
use CrudGenerator\Generators\Questions\Cli\MetaDataQuestion;
use CrudGenerator\Generators\Questions\Cli\GeneratorQuestion;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Generators\GeneratorCli;
use CrudGenerator\Generators\Parser\GeneratorParser;
use CrudGenerator\DataObject;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use CrudGenerator\Context\CliContext;

/**
 * Generator command
 *
 * @author Stéphane Demonchaux
 */
class CreateCommand extends Command
{
    /**
     * @var GeneratorParser
     */
    private $parser = null;
    /**
     * @var GeneratorCli
     */
    private $generator = null;
    /**
     * @var HistoryManager
     */
    private $historyManager = null;
    /**
     * @var MetaDataSourcesQuestion
     */
    private $metaDataSourcesQuestion = null;
    /**
     * @var MetaDataQuestion
     */
    private $metaDataQuestion = null;
    /**
     * @var GeneratorQuestion
     */
    private $generatorQuestion = null;
    /**
     * @var CliContext
     */
    private $cliContext = null;

    /**
     * @param GeneratorParser $parser
     * @param GeneratorCli $generator
     * @param HistoryManager $historyManager
     * @param MetaDataSourcesQuestion $metaDataSourcesQuestion
     * @param MetaDataQuestion $metaDataQuestion
     * @param GeneratorQuestion $generatorQuestion
     * @param CliContext $cliContext
     */
    public function __construct(
        GeneratorParser $parser,
        GeneratorCli $generator,
        HistoryManager $historyManager,
        MetaDataSourcesQuestion $metaDataSourcesQuestion,
        MetaDataQuestion $metaDataQuestion,
        GeneratorQuestion $generatorQuestion,
        CliContext $cliContext
    ) {
        parent::__construct('create');
        $this->parser                  = $parser;
        $this->generator               = $generator;
        $this->historyManager          = $historyManager;
        $this->metaDataSourcesQuestion = $metaDataSourcesQuestion;
        $this->metaDataQuestion        = $metaDataQuestion;
        $this->generatorQuestion       = $generatorQuestion;
        $this->cliContext              = $cliContext;
    }
    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Console\Command.Command::configure()
     */
    protected function configure()
    {
        $this->setName('CodeGenerator:create')
             ->setDescription('Generate code based on metadata');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->create();
    }

    /**
     * @throws \RuntimeException
     * @return \CrudGenerator\DataObject
     */
    public function create()
    {
        $adapter    = $this->metaDataSourcesQuestion->ask();
        $metadata   = $this->metaDataQuestion->ask($adapter);
        $generator  = $this->generatorQuestion->ask();

        $generatorDTO = new GeneratorDataObject();
        $generatorDTO->setName($generator);

        $generatorDTO = $this->parser->init($generatorDTO, $metadata);

        $generatorDTO->getDTO()
                     ->setAdapter($adapter->getName());

        $doI = $this->cliContext->getDialogHelper()->askConfirmation(
            $this->cliContext->getOutput(),
            "\n<question>Do you confirm generation ?</question> "
        );

        if ($doI === true) {
            $this->generator->generate($generatorDTO);
            $this->historyManager->create($generatorDTO->getDTO());

            return $generatorDTO;
        } else {
            throw new \RuntimeException('Command aborted');
        }
    }
}
