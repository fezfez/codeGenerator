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

class DirectoryQuestion
{
    /**
     * @var FileManager $fileManager
     */
    private $fileManager = null;

    /**
     * @param FileManager $fileManager
     */
    public function __construct(FileManager $fileManager)
    {
        $this->fileManager = $fileManager;
    }

    /**
     * @param GeneratorDataObject $DTO
     * @return GeneratorDataObject
     */
    public function ask(GeneratorDataObject $generator, array $question)
    {
    	$attribute = 'get' . $question['dtoAttribute'];
        $module = $generator->getDTO()->$attribute();
        $directoriesRaw = $this->fileManager->glob(
            $module . '*',
            GLOB_ONLYDIR|GLOB_MARK
        );

        $directories = array();
        if ('' !== $module && null !== $module) {
            $back = str_replace(array(getcwd() . '/', getcwd()), array('', ''), realpath($module . '../'));
            $directories[] = array('label' => 'Back', 'id' => ($back !== '') ? $back . '/' : '');
        }

        foreach ($directoriesRaw as $directory) {
            $directories[] = array('label' => $directory, 'id' => $directory);
        }

        $generator->addQuestion(
            array(
                'dtoAttribute'    => 'set' . $question['dtoAttribute'],
                'text'            => $question['text'],
                'placeholder'     => 'Actual directory "' . $module . '"',
                'value'           => $module,
                'type'            => 'select',
                'values'          => $directories
            )
        );

        return $generator;
    }
}