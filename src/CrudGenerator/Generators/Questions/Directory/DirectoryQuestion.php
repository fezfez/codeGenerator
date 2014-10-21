<?php
/**
  * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Generators\Questions\Directory;

use CrudGenerator\Utils\FileManager;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Context\ContextInterface;
use CrudGenerator\Context\QuestionWithPredefinedResponse;
use CrudGenerator\Context\PredefinedResponseCollection;
use CrudGenerator\Context\PredefinedResponse;
use CrudGenerator\Context\CliContext;
use CrudGenerator\Generators\Parser\Lexical\QuestionResponseTypeEnum;
use CrudGenerator\Context\SimpleQuestion;

class DirectoryQuestion
{
    /**
     * @var integer
     */
    const BACK = 2;
    /**
     * @var integer
     */
    const CURRENT_DIRECTORY = 3;
    /**
     * @var integer
     */
    const CREATE_DIRECTORY = 4;

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
        $getter   = 'get' . $question['dtoAttribute'];
        $setter   = 'set' . $question['dtoAttribute'];
        $required = (isset($question['required']) === true) ? $question['required'] : false;

        do {
            $actualDirectory    = $generator->getDto()->$getter();
            $responseCollection = new PredefinedResponseCollection();
            $responseCollection = $this->checkAdditionalChoices($actualDirectory, $responseCollection);
            $responseCollection = $this->buildDirectoryList($actualDirectory, $responseCollection);

            $questionDTO = new QuestionWithPredefinedResponse(
                $question['text'],
                $setter,
                $responseCollection
            );

            $questionDTO->setPreselectedResponse($actualDirectory)
                        ->setRequired($required)
                        ->setHelpMessage(sprintf('Actual directory "%s"', $actualDirectory))
                        ->setType('directory')
                        ->setConsumeResponse(true);

            $response = $this->context->askCollection($questionDTO);
            $response = $this->checkSpecialResponse($response);


            if ($response !== null ) {
                if ($response === self::CURRENT_DIRECTORY) {
                    $generator->getDto()->$setter($actualDirectory);
                    break;
                } else {
                    $generator->getDto()->$setter($response);
                }
            }

        } while($response !== null);

        return $generator;
    }

    /**
     * @param string $response
     * @return string
     */
    private function checkSpecialResponse($response)
    {
        if ($response === self::CREATE_DIRECTORY) {
            $response = $this->createDirectory($actualDirectory);
        } elseif ($response === self::BACK) {
            $response = substr($actualDirectory, 0, -1);
            $response = str_replace(
                substr(strrchr($response, "/"), 1),
                '',
                $response
            );
        }

        return $response;
    }
    /**
     * @param string $baseDirectory
     * @throws \Exception
     * @return string
     */
    private function createDirectory($baseDirectory = null)
    {
        if ($baseDirectory === null) {
            $baseDirectory = '';
        }

        $directory = '';

        while (true) {
            try {
                $directory = $this->context->ask(new SimpleQuestion('Directory name', 'directory_name'));

                if (false === $this->fileManager->ifDirDoesNotExistCreate($baseDirectory . $directory)) {
                    throw new \Exception('Directory already exist');
                } else {
                    break;
                }
            } catch (\Exception $e) {
                $this->context->log('<error>' . $e->getMessage() . '</error>');
            }
        }

        return $directory . '/';
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
            $responseCollection->append(
                new PredefinedResponse(self::BACK, 'Back', self::BACK)
            );
        }

        if ($this->context instanceof CliContext) {
            $responseCollection->append(
                new PredefinedResponse(self::CURRENT_DIRECTORY, 'Chose actual directory', self::CURRENT_DIRECTORY)
            );
            $responseCollection->append(
                new PredefinedResponse(self::CREATE_DIRECTORY, 'Create directory', self::CREATE_DIRECTORY)
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
            GLOB_ONLYDIR | GLOB_MARK
        );

        foreach ($directoriesRaw as $directory) {
            $predefinedResponse = new PredefinedResponse($directory, $directory, $directory);
            $predefinedResponse->setAdditionalData(array('parent' => $actualDirectory));
            $responseCollection->append($predefinedResponse);
        }

        return $responseCollection;
    }
}
