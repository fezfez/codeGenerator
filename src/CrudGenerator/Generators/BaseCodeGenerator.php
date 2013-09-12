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
namespace CrudGenerator\Generators;

use CrudGenerator\DataObject;
use CrudGenerator\Generators\GeneriqueQuestions;
use CrudGenerator\Generators\Strategies\StrategyInterface;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\DialogHelper;

/**
 * Base code generator, extends it and implement doGenerate method
 * to make you own Generator
 *
 * @author Stéphane Demonchaux
 */
abstract class BaseCodeGenerator
{
    /**
     * @var StrategyInterface
     */
    private $strategy              = null;
    /**
     * @var OutputInterface Output
     */
    protected $output              = null;
    /**
     * @var DialogHelper Dialog
     */
    protected $dialog              = null;
    /**
     * @var GeneriqueQuestions Generique Question
     */
    protected $generiqueQuestion   = null;

    /**
     * Base code generator
     * @param OutputInterface $output
     * @param DialogHelper $dialog
     * @param GeneriqueQuestions $generiqueQuestion
     * @param StrategyInterface $strategy
     */
    public function __construct(
        OutputInterface $output,
        DialogHelper $dialog,
        GeneriqueQuestions $generiqueQuestion,
        StrategyInterface $strategy
    ) {
        $this->output              = $output;
        $this->dialog              = $dialog;
        $this->generiqueQuestion   = $generiqueQuestion;
        $this->strategy            = $strategy;
    }

    /**
     * Generation concrete method
     * @param DataObject $dataObject
     * @throws \RuntimeException
     */
    abstract protected function doGenerate($dataObject);

    /**
     * Call the concrete generator
     * @param DataObject $dataObject
     * @throws \RuntimeException
     */
    public function generate(DataObject $dataObject)
    {
        $metadata = $dataObject->getMetadata();
        if (empty($metadata)) {
            throw new \RuntimeException('Empty metadata');
        }

        $identifiers = $metadata->getIdentifier();
        if (count($identifiers) === 0) {
            throw new \RuntimeException('The generator does not support entity without primary keys.');
        }

        if (count($identifiers) !== 1) {
            throw new \RuntimeException('The generator does not support entity classes with multiple primary keys.');
        }

        $identifierNames = array();
        foreach ($identifiers as $identifier) {
            $identifierNames[] = $identifier->getName();
        }

        if (!in_array('id', $identifierNames)) {
            throw new \RuntimeException(
                'The generator expects the entity object has a primary key field named "id" with a getId() method.'
            );
        }

        return $this->doGenerate($dataObject);
    }

    /**
     * Get generator definition
     * @return string
     */
    public function getDefinition()
    {
        return $this->definition;
    }
    /**
     * Get dto name
     * @return string
     */
    public function getDTO()
    {
        return '\\' . $this->dto;
    }

    /**
     * Generate file based on template
     * @param DataObject $dataObject
     * @param string $pathTemplate
     * @param string $pathTo
     * @param array $suppDatas
     */
    protected function generateFile(DataObject $dataObject, $pathTemplate, $pathTo, array $suppDatas = array())
    {
        $this->strategy->generateFile($dataObject, $this->skeletonDir, $pathTemplate, $pathTo, $suppDatas);
    }

    /**
     * Create dir if not exist
     * @param string $dir
     */
    protected function ifDirDoesNotExistCreate($dir)
    {
        $this->strategy->ifDirDoesNotExistCreate($dir);
    }

    /**
     * @param string $directory
     */
    protected function createFullPathDirIfNotExist($directory)
    {
        $explodeDirectory = explode('/', $directory);
        $allDir = '';
        foreach ($explodeDirectory as $dir) {
            $allDir .= $dir . '/';
            $this->ifDirDoesNotExistCreate($allDir);
        }
    }

    /**
     * @param string $string
     */
    protected function unCamelCase($string)
    {
        return strtolower(preg_replace('/(?<=\\w)(?=[A-Z])/', "-\$1", $string));
    }

    /**
     * @param DataObject $dataObject
     * @param string $attributeName
     * @param string $question
     * @param string $defaultResponse
     * @return DataObject
     */
    protected function manageOption(DataObject $dataObject, $attributeName, $question, $defaultResponse = null)
    {
        $getter = 'get' . $attributeName;
        $setter = 'set' . $attributeName;

        if (null === $dataObject->$getter()) {
            $dataObject->$setter(
                $this->dialog->ask(
                    $this->output,
                    '<question>' . $question . '</question> ',
                    $defaultResponse
                )
            );
        }

        return $dataObject;
    }
}
