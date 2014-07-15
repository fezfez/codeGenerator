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
namespace CrudGenerator\FileConflict;

use CrudGenerator\Utils\FileManager;
use SebastianBergmann\Diff\Differ;
use CrudGenerator\Context\ContextInterface;
use CrudGenerator\Generators\ResponseExpectedException;

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
     * @param FileManager $fileManager
     * @param Differ $diffPHP
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
     * @param string $filePath File location
     * @param string $newResult
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
     * @param string $filePath
     * @param string $results
     * @throws ResponseExpectedException
     */
    public function handle($filePath, $results)
    {
        while (($response = $this->context->askCollection(
                'File "' . $filePath . '" already exist, erase it with the new',
                'conflict' . $filePath,
                array(
                    'postpone',
                    'show diff',
                    'erase',
                    'cancel'
                )
            )) !== null) {

            $response = intval($response);

            if ($response === self::SHOW_DIFF) {
                // write to output the diff
                $this->context->log(
                    '<info>' . $this->diffPHP->diff($results, $this->fileManager->fileGetContent($filePath)) . '</info>'
                );
            } else {
                break;
            }
        }

        if ($response === self::POSTPONE) {
            //Generate the diff file
            $this->fileManager->filePutsContent(
                $filePath . '.diff',
                $this->diffPHP->diff(
                    $results,
                    $this->fileManager->fileGetContent($filePath)
                )
            );
            $this->context->log('--> Generate diff and new file ' . $filePath . '.diff', 'generationLog');
        } elseif ($response === self::ERASE) {
            $this->fileManager->filePutsContent($filePath, $results);
            $this->context->log('--> Replace file ' . $filePath, 'generationLog');
        } elseif (null === $response) {
            throw new ResponseExpectedException(sprintf('Conflict with file "%s" not resolved', $filePath));
        }
    }
}
