<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\FileConflict;

use CrudGenerator\Utils\FileManager;
use SebastianBergmann\Diff\Differ;
use CrudGenerator\Context\ContextInterface;
use CrudGenerator\Context\QuestionWithPredefinedResponse;
use CrudGenerator\Context\PredefinedResponseCollection;
use CrudGenerator\Context\PredefinedResponse;

class FileConflictManager
{
    /**
     * @var integer Post pone choise
     */
    const POSTPONE = 0;
    /**
     * @var integer Show diff choise
     */
    const SHOW_DIFF = 1;
    /**
     * @var integer Erase choise
     */
    const ERASE = 2;
    /**
     * @var integer Cancel choise
     */
    const CANCEL = 3;

    /**
     * @var ContextInterface Output
     */
    private $context = null;
    /**
     * @var FileManager File Manager
     */
    private $fileManager = null;
    /**
     * @var Differ Diff php
     */
    private $diffPHP = null;

    /**
     * @param ContextInterface $context
     * @param FileManager      $fileManager
     * @param Differ           $diffPHP
     */
    public function __construct(
        ContextInterface $context,
        FileManager $fileManager,
        Differ $diffPHP
    ) {
        $this->context     = $context;
        $this->fileManager = $fileManager;
        $this->diffPHP     = $diffPHP;
    }

    /**
     * Test if there is a file conflict
     * @param  string  $filePath  File location
     * @param  string  $newResult
     * @return boolean
     */
    public function test($filePath, $newResult)
    {
        return ($this->fileManager->isFile($filePath)
                        && $this->fileManager->fileGetContent($filePath) !== $newResult);
    }

    /**
     * Handle the file conflict
     *
     * @param  string                    $filePath
     * @param  string                    $results
     * @throws ResponseExpectedException
     */
    public function handle($filePath, $results)
    {
        $responseCollection = new PredefinedResponseCollection();
        $responseCollection->append(
            new PredefinedResponse('postpone', 'postpone', self::POSTPONE)
        );
        $responseCollection->append(
            new PredefinedResponse('show diff', 'show diff', self::SHOW_DIFF)
        );
        $responseCollection->append(
            new PredefinedResponse('erase', 'erase', self::ERASE)
        );
        $responseCollection->append(
            new PredefinedResponse('cancel', 'cancel', self::CANCEL)
        );

        $question = new QuestionWithPredefinedResponse(
            sprintf('File "%s" already exist, erase it with the new', $filePath),
            'conflict'.$filePath,
            $responseCollection
        );

        $question->setShutdownWithoutResponse(true, sprintf('Conflict with file "%s" not resolved', $filePath));

        while (($response = $this->context->askCollection($question)) !== null) {
            $response = intval($response);

            if ($response === self::SHOW_DIFF) {
                // write to output the diff
                $this->context->log(
                    '<info>'.$this->diffPHP->diff($results, $this->fileManager->fileGetContent($filePath)).'</info>'
                );
            } else {
                break;
            }
        }

        if ($response === self::POSTPONE) {
            //Generate the diff file
            $this->fileManager->filePutsContent(
                $filePath.'.diff',
                $this->diffPHP->diff(
                    $results,
                    $this->fileManager->fileGetContent($filePath)
                )
            );
            $this->context->log('--> Generate diff and new file '.$filePath.'.diff', 'generationLog');
        } elseif ($response === self::ERASE) {
            $this->fileManager->filePutsContent($filePath, $results);
            $this->context->log('--> Replace file '.$filePath, 'generationLog');
        }
    }
}
