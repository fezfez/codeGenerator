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
namespace CrudGenerator\Generators\Questions\Web;

use CrudGenerator\Utils\FileManager;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Context\ContextInterface;
use CrudGenerator\Context\QuestionWithPredefinedResponse;
use CrudGenerator\Context\PredefinedResponseCollection;
use CrudGenerator\Context\PredefinedResponse;

class DirectoryQuestion
{
    /**
     * @var FileManager $fileManager
     */
    private $fileManager = null;
    /**
     * @var ContextInterface $context
     */
    private $context = null;

    /**
     * @param FileManager $fileManager
     * @param ContextInterface $context
     */
    public function __construct(FileManager $fileManager, ContextInterface $context)
    {
        $this->fileManager = $fileManager;
        $this->context     = $context;
    }

    /**
     * @param GeneratorDataObject $generator
     * @param array $question
     * @return GeneratorDataObject
     */
    public function ask(GeneratorDataObject $generator, array $question)
    {
        $attribute       = 'get' . $question['dtoAttribute'];
        $actualDirectory = $generator->getDTO()->$attribute();

        $responseCollection = new PredefinedResponseCollection();
        $responseCollection = $this->checkAdditionalChoices($actualDirectory, $responseCollection);
        $responseCollection = $this->buildDirectoryList($actualDirectory, $responseCollection);

        $questionDTO = new QuestionWithPredefinedResponse(
            $question['text'],
            'set' . $question['dtoAttribute'],
            $responseCollection
        );
        $questionDTO->setPreselectedResponse($actualDirectory)
                    ->setRequired((isset($question['required']) === true) ? $question['required'] : false)
                    ->setHelpMessage(sprintf('Actual directory "%s"', $actualDirectory))
                    ->setType('directory');

        $response = $this->context->askCollection($questionDTO);

        if ($response !== null) {
            $setter = 'set' . $question['dtoAttribute'];
            $generator->getDTO()->$setter($response);
        }

        return $generator;
    }

    /**
     * @param string|null $actualDirectory
     * @param PredefinedResponseCollection $responseCollection
     * @return PredefinedResponseCollection
     */
    private function checkAdditionalChoices($actualDirectory, PredefinedResponseCollection $responseCollection)
    {
        // if we aren't in base directory, add back button
        if ('' !== $actualDirectory && null !== $actualDirectory) {
            $back = str_replace(
                array(getcwd() . DIRECTORY_SEPARATOR , getcwd()),
                array('', ''),
                realpath($actualDirectory . '..' . DIRECTORY_SEPARATOR)
            );
            $id = ($back !== '') ? $back . DIRECTORY_SEPARATOR : '';

            $responseCollection->append(
                new PredefinedResponse($id, 'Back', $id)
            );
        }

        return $responseCollection;
    }

    /**
     * @param string|null $actualDirectory
     * @param PredefinedResponseCollection $responseCollection
     * @return PredefinedResponseCollection
     */
    private function buildDirectoryList($actualDirectory, PredefinedResponseCollection $responseCollection)
    {
        $directoriesRaw = $this->fileManager->glob(
            $actualDirectory . '*',
            GLOB_ONLYDIR|GLOB_MARK
        );

        foreach ($directoriesRaw as $directory) {
            $predefinedResponse = new PredefinedResponse($directory, $directory, $directory);
            $predefinedResponse->setAdditionalData(array('parent' => $actualDirectory));
            $responseCollection->append($predefinedResponse);
        }

        return $responseCollection;
    }
}
