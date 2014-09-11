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
namespace CrudGenerator\Generators\Questions\Cli;

use CrudGenerator\Utils\FileManager;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Context\ContextInterface;

class DirectoryQuestion
{
    /**
     * @var integer
     */
    const BACK = 0;
    /**
     * @var integer
     */
    const CURRENT_DIRECTORY = 1;
    /**
     * @var integer
     */
    const CREATE_DIRECTORY = 2;
    /**
     * @var FileManager
     */
    private $fileManager = null;
    /**
     * @var ContextInterface
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
     * Ask in wich directory you want to write
     * @param GeneratorDataObject
     * @return GeneratorDataObject
     */
    public function ask(GeneratorDataObject $generator, $question)
    {
        $directory   = './';
        $choice      = null;
        $directories = array(self::CURRENT_DIRECTORY => 'cant find me !');

        while ($choice != $directories[self::CURRENT_DIRECTORY]) {
            $directories = $this->fileManager->glob(
                $directory . '*',
                GLOB_ONLYDIR|GLOB_MARK
            );

            array_unshift(
                $directories,
                ' -> Back',
                ' -> Chose actual directory',
                ' -> Create directory'
            );

            $choices = array();
            foreach ($directories as $directorie) {
            	$choices[$directorie] = array(
            		'id'    => $directorie,
            		'label' => $directorie
            	);
            }

            $this->context->log($directory);
            $choice = $this->context->askCollection(
                "<question>Choose a target directory</question> \n> ",
                'directory',
                $choices
            );

            if ($choice === $directories[self::CREATE_DIRECTORY]) {
                $directory .= $this->createDirectory($directory);
            } elseif ($choice === $directories[self::BACK]) {
                $directory = substr($directory, 0, -1);
                $directory = str_replace(
                    substr(strrchr($directory, "/"), 1),
                    '',
                    $directory
                );
            } elseif ($choice !== $directories[self::CURRENT_DIRECTORY]) {
                $directory = $choice;
            }
        }

        $attribute = 'set' . $question['dtoAttribute'];

        $generator->getDTO()->$attribute($directory);

        return $generator;
    }

    /**
     * @param string $baseDirectory
     * @throws \Exception
     * @return string
     */
    private function createDirectory($baseDirectory)
    {
        $directory = '';
        while (true) {
            try {
                $directory = $this->context->ask(
                    'Directory name ',
                    'directory_name'
                );

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
}
