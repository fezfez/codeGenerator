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
namespace CrudGenerator\Utils;

use RuntimeException;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Stub for file manager
 *
 * @author Stéphane Demonchaux
 */
class FileManagerStub extends FileManager
{
    /**
     * @var DialogHelper
     */
    private $dialog = null;
    /**
     * @var OutputInterface
     */
    private $output = null;

    /**
     * @param DialogHelper $dialog
     * @param OutputInterface $output
     */
    public function __construct(DialogHelper $dialog, OutputInterface $output)
    {
        $this->dialog = $dialog;
        $this->output = $output;
    }
    /**
     * Create a directory
     * @param string $pathname Dir to create
     * @throws RuntimeException
     */
    public function mkdir($pathname)
    {
        $this->output->writeln("<info>[LOG] mkdir $pathname</info>");
    }

    /**
     * Puts content into file
     * @param string $path File path
     * @param string $content File Content
     */
    public function filePutsContent($path, $content)
    {
        $this->output->writeln("<info>[LOG] filePutsContent $path</info>");
        $this->output->writeln($content);

        $doI = $this->dialog->askConfirmation(
            $this->output,
            "\n<question>Regenerate ?</question> "
        );
        if ($doI === true) {
            throw new \Exception('Re-try');
        }
    }

    /**
     * Get content from file
     * @param string $path File path
     */
    public function fileGetContent($path)
    {
        $this->output->writeln("<info>[LOG] fileGetContent $path</info>");
    }

    /**
     * Test if is file
     * @param string $path File path
     */
    public function isFile($path)
    {
        $this->output->writeln("<info>[LOG] isFile $path</info>");
        return true;
    }

    /**
     * Test if is directory
     * @param string $path Directory path
     */
    public function isDir($path)
    {
        $this->output->writeln("<info>[LOG] isDir $path</info>");
        return true;
    }

    /**
     * Test if file exist
     * @param string $file File path
     */
    public function fileExists($file)
    {
        $this->output->writeln("<info>[LOG] fileExists $file</info>");
    }

    /**
     * Include file
     * @param string $file File path
     */
    public function includeFile($file)
    {
        $this->output->writeln("<info>[LOG] includeFile $file</info>");
    }

    /**
     * Delete file
     * @param string $file File path
     */
    public function unlink($file)
    {
        $this->output->writeln("<info>[LOG] unlink $file</info>");
    }

    /**
     * Glob dir
     * @param string $dir Dir path
     */
    public function glob($dir)
    {
        $this->output->writeln("<info>[LOG] glob $dir</info>");
    }
}
