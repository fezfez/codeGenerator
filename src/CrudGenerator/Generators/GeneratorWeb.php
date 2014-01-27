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

use CrudGenerator\Generators\Strategies\StrategyInterface;
use CrudGenerator\FileConflict\FileConflictManagerWeb;
use CrudGenerator\Utils\FileManager;

/**
 * @author Stéphane Demonchaux
 */
class GeneratorWeb
{
    /**
     * @var StrategyInterface
     */
    private $strategy = null;
    /**
     * @var FileConflictManager
     */
    private $fileConflict = null;
    /**
     * @var FileManager
     */
    private $fileManager = null;

    /**
     * @param StrategyInterface $strategy
     */
    public function __construct(StrategyInterface $strategy, FileConflictManagerWeb $fileConflict,  FileManager $fileManager)
    {
        $this->strategy = $strategy;
        $this->fileConflict = $fileConflict;
        $this->fileManager = $fileManager;
    }

    /**
     * @param Generator $generator
     * @param string $fileName
     * @throws \Exception
     * @return string
     */
    public function generate(GeneratorDataObject $generator, $fileName = null)
    {
    	$files = $generator->getFiles();

    	if (null !== $fileName) {
    		if (!isset($files[$fileName])) {
    			throw new \InvalidArgumentException('File does not exist');
    		}
    		return $this->strategy->generateFile(
    			$generator->getTemplateVariables(),
    			$files[$fileName]['skeletonPath'],
    			$files[$fileName]['name'],
    			$files[$fileName]['fileName']
    		);
    	} else {
	        $generationResult = array();
	        $isConflictInGeneration = false;
	        $isConfliInFile = false;
	        foreach ($files as $fileName => $fileInfos) {
	            if (isset($files[$fileName]['result'])) {
	                $generationResult[$fileName] = $files[$fileName]['result'];
	            } else {
	                $generationResult[$fileName] = $this->strategy->generateFile(
	                    $generator->getTemplateVariables(),
	                    $files[$fileName]['skeletonPath'],
	                    $files[$fileName]['name'],
	                    $files[$fileName]['fileName']
	                );

	                $isConfliInFile = $this->fileConflict->test($files[$fileName]['fileName'], $generationResult[$fileName]);
	                if (true === $isConfliInFile) {
	                    throw new GeneratorWebConflictException('Conflict in "' . $files[$fileName]['fileName'] . '" file');
	                }
	            }
	        }

	        $log = '';
	        foreach ($generationResult as $fileName => $result) {
	            $log .= $this->fileManager->filePutsContent($fileName, $result);
	        }

	        return $log;
    	}
    }

    /**
     * @param GeneratorDataObject $generator
     * @param array $responseToHandle
     * @return array
     */
    public function handleConflict(GeneratorDataObject $generator, array $responseToHandle)
    {
        $files = $generator->getFiles();

        $generationResult = array();
        $isConfliInFile = false;
        foreach ($files as $fileName => $fileInfos) {
            $generationResult[$fileName]['result'] = $this->strategy->generateFile(
                $generator->getTemplateVariables(),
                $files[$fileName]['skeletonPath'],
                $files[$fileName]['name'],
                $files[$fileName]['fileName']
            );

            $isConfliInFile = $this->fileConflict->test($files[$fileName]['fileName'], $generationResult[$fileName]['result']);

            if (isset($responseToHandle['handle_' . str_replace('.', '_', $fileName)])) {
                if ($responseToHandle['handle_' . str_replace('.', '_', $fileName)] === 'postpone') {

                    $generator->addFile(
                        $files[$fileName]['skeletonPath'],
                        $files[$fileName]['name'],
                        $files[$fileName]['fileName'] . '.diff',
                        $this->fileConflict->handle($files[$fileName]['fileName'], $generationResult[$fileName]['result'])
                    )
                    ->addFile(
                        $files[$fileName]['skeletonPath'],
                        $files[$fileName]['name'],
                        $files[$fileName]['fileName'] . '.diff',
                        $generationResult[$fileName]['result']
                    )
                    ->deleteFile($fileName);
                } elseif ($responseToHandle['handle_' . str_replace('.', '_', $fileName)] === 'erase') {
                    $file = $files[$fileName];
                    $generator->deleteFile($fileName)
                              ->addFile(
                                      $file['skeletonPath'],
                                      $file['name'],
                                      $file['value'],
                                      $generationResult[$fileName]['result']
                              );
                    $this->fileManager->filePutsContent($filePath, $results);
                }
            }
        }

        return $generator;
    }

    /**
     * @param GeneratorDataObject $generator
     * @return array
     */
    public function checkConflict(GeneratorDataObject $generator)
    {
        $files = $generator->getFiles();

        $generationResult = array();
        $isConfliInFile = false;
        foreach ($files as $fileName => $fileInfos) {
            $generationResult[$fileName]['result'] = $this->strategy->generateFile(
                $generator->getTemplateVariables(),
                $files[$fileName]['skeletonPath'],
                $files[$fileName]['name'],
                $files[$fileName]['fileName']
            );

            $isConfliInFile = $this->fileConflict->test($files[$fileName]['fileName'], $generationResult[$fileName]['result']);
            $generationResult[$fileName]['isConflict'] = $isConfliInFile;

            if (true === $isConfliInFile) {
                $generationResult[$fileName]['diff'] = $this->fileConflict->handle($files[$fileName]['fileName'], $generationResult[$fileName]['result']);
            }
        }

        return $generationResult;
    }
}