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
use CrudGenerator\Utils\FileManager;
use CrudGenerator\Utils\FileManagerStub;
use CrudGenerator\View\View;
use CrudGenerator\Generators\GeneriqueQuestions;
use CrudGenerator\FileConflict\FileConflictManager;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Base code generator, extends it and implement doGenerate method
 * to make you own Generator
 *
 * @author Stéphane Demonchaux
 */
abstract class BaseCodeGenerator
{
    /**
     * @var View View manager
     */
    protected $view                = null;
    /**
     * @var OutputInterface Output
     */
    protected $clientResponse      = null;
    /**
     * @var FileManager File Manager
     */
    protected $fileManager         = null;
    /**
     * @var DialogHelper Dialog
     */
    protected $dialog              = null;
    /**
     * @var GeneriqueQuestions Generique Question
     */
    protected $generiqueQuestion   = null;
    /**
     * @var FileConflictManager File conflict manager
     */
    protected $fileConflictManager = null;

    /**
     * Base code generator
     * @param View $view
     * @param OutputInterface $output
     * @param FileManager $fileManager
     * @param DialogHelper $dialog
     * @param GeneriqueQuestions $generiqueQuestion
     * @param FileConflictManager $fileConflictManager
     */
    public function __construct(
        View $view,
        OutputInterface $output,
        FileManager $fileManager,
        DialogHelper $dialog,
        GeneriqueQuestions $generiqueQuestion,
        FileConflictManager $fileConflictManager
    ) {
        $this->view                = $view;
        $this->output              = $output;
        $this->fileManager         = $fileManager;
        $this->dialog              = $dialog;
        $this->generiqueQuestion   = $generiqueQuestion;
        $this->fileConflictManager = $fileConflictManager;
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

        $identifier = $metadata->getIdentifier();
        if (count($identifier) === 0) {
            throw new \RuntimeException('The generator does not support entity classes with multiple primary keys.');
        }

        if (!in_array('id', $identifier)) {
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
        $datas = array(
            'dir'        => $this->skeletonDir,
            'dataObject' => $dataObject,
        );

        $results = $this->view->render(
            $this->skeletonDir,
            $pathTemplate,
            array_merge($datas, $suppDatas)
        );

        if ($this->fileManager instanceof FileManagerStub) {
            $continue = true;
            while ($continue) {
                try {
                    $this->fileManager->filePutsContent($pathTo, $results);
                    $continue = false;
                } catch (\Exception $e) {
                    $results = $this->view->render(
                        $this->skeletonDir,
                        $pathTemplate,
                        array_merge($datas, $suppDatas)
                    );
                }
            }
        } elseif (true === $this->fileConflictManager->test($pathTo, $results)) {
            $this->fileConflictManager->handle($pathTo, $results);
        } else {
            $this->fileManager->filePutsContent($pathTo, $results);
            $this->output->writeln('--> Create ' . $pathTo);
        }
    }

    /**
     * Create dir if not exist
     * @param string $dir
     */
    protected function ifDirDoesNotExistCreate($dir)
    {
        if (true === $this->fileManager->ifDirDoesNotExistCreate($dir)) {
            $this->output->writeln('--> Create dir ' . $dir);
        }
    }
}
