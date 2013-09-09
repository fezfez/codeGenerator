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
namespace CrudGenerator\Command\Questions;

use CrudGenerator\EnvironnementResolver\ZendFramework2Environnement;
use CrudGenerator\EnvironnementResolver\EnvironnementResolverException;
use CrudGenerator\Utils\FileManager;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\DialogHelper;

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
     * @var OutputInterface
     */
    private $output = null;
    /**
     * @var DialogHelper
     */
    private $dialog = null;

    /**
     * @param FileManager $fileManager
     * @param OutputInterface $output
     * @param DialogHelper $dialog
     */
    public function __construct(FileManager $fileManager, OutputInterface $output, DialogHelper $dialog)
    {
        $this->fileManager = $fileManager;
        $this->output = $output;
        $this->dialog = $dialog;
    }

    /**
     * Ask in wich directory you want to write
     * @return string
     */
    public function ask($withFile = false)
    {
        try {
            ZendFramework2Environnement::getDependence($this->fileManager);
            $directory = './module/';
        } catch (EnvironnementResolverException $e) {
            $directory = './';
        }

        $choice = null;
        while ($choice != self::CURRENT_DIRECTORY) {
            $directories = $this->fileManager->glob(
                $directory . '*',
                ($withFile === false) ? GLOB_ONLYDIR|GLOB_MARK : GLOB_MARK
            );

            array_unshift(
                $directories,
                ' -> Back',
                ' -> Chose actual directory',
                ' -> Create directory'
            );

            $this->output->writeLn($directory);
            $choice = $this->dialog->select(
                $this->output,
                "<question>Choose a target directory</question> \n> ",
                $directories
            );

            if ($choice == self::CREATE_DIRECTORY) {
                $directory .= $this->createDirectory($directory);
            } elseif ($choice == self::BACK) {
                $directory = substr($directory, 0, -1);
                $directory = str_replace(
                    substr(strrchr($directory, "/"), 1),
                    '',
                    $directory
                );
            } elseif ($this->fileManager->isFile($directories[$choice])) {
                $directory = $directories[$choice];
                break;
            } elseif ($choice != self::CURRENT_DIRECTORY) {
                $directory = $directories[$choice];
            }
        }

        return $directory;
    }

    private function createDirectory($baseDirectory)
    {
        while (true) {
            try {
                $directory = $this->dialog->ask(
                    $this->output,
                    'Directory name '
                );

                if (false === $this->fileManager->ifDirDoesNotExistCreate($directory)) {
                    throw new \Exception('Directory already exist');
                } else {
                    break;
                }
            } catch (\Exception $e) {
                $this->output->writeLn('<error>' . $e->getMessage() . '</error>');
            }
        }

        return $directory . '/';
    }
}
