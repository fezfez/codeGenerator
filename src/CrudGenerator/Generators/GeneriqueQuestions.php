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

use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Output\OutputInterface;
use CrudGenerator\DataObject;
use CrudGenerator\FileManager;

/**
 * Group of questions frequently asked by Generators
 *
 * @author Stéphane Demonchaux
 */
class GeneriqueQuestions
{
    /**
     * @var DialogHelper Dialog
     */
    private $dialog            = null;
    /**
     * @var OutputInterface Output
     */
    private $output            = null;
    /**
     * @var FileManager FileManager
     */
    private $fileManager        = null;
    /**
     * @var string Response to the question
     */
    private $directoryResponse = null;
    /**
     * @var string Response to the question
     */
    private $namespaceResponse = null;

    /**
     * Group of questions frequently asked by Generators
     * @param DialogHelper $dialog
     * @param OutputInterface $output
     * @param FileManager $fileManager
     */
    public function __construct(DialogHelper $dialog, OutputInterface $output, FileManager $fileManager)
    {
        $this->dialog      = $dialog;
        $this->output      = $output;
        $this->fileManager = $fileManager;
    }

    /**
     * Ask generation directory
     *
     * @param DataObject $dataObject
     * @throws \InvalidArgumentException
     * @return string
     */
    public function directoryQuestion(DataObject $dataObject)
    {
        if (null === $this->directoryResponse) {
            $moduleName = $dataObject->getModule();
            $fileManager = $this->fileManager;

            $this->directoryResponse = $this->dialog->askAndValidate(
                $this->output,
                'Choose a target directory ',
                function ($directory) use ($moduleName, $fileManager) {
                    if (!$fileManager->isDir($moduleName . '/' . $directory)) {
                        throw new \InvalidArgumentException(
                            sprintf('Directory "%s" does not exist.', $moduleName . $directory)
                        );
                    }

                    return $directory;
                },
                false
            );
        }

        return $this->directoryResponse;
    }

    /**
     * Ask the namespace to use in template
     *
     * @return string
     */
    public function namespaceQuestion()
    {
        if (null === $this->namespaceResponse) {
            $this->namespaceResponse   = $this->dialog->ask($this->output, 'Choose a target namespace ');
        }

        return $this->namespaceResponse;
    }
}
