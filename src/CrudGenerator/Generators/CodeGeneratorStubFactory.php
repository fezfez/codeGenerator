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

use CrudGenerator\View\ViewFactory;
use CrudGenerator\Utils\FileManagerStub;
use CrudGenerator\Generators\GeneriqueQuestions;
use CrudGenerator\FileConflict\FileConflictManagerFactory;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\DialogHelper;

/**
 * Create CodeGenerator instance
 * @author Stéphane Demonchaux
 */
class CodeGeneratorStubFactory implements CodeGeneratorFactoryInterface
{
    /**
     * Create CodeGenerator instance
     * @param OutputInterface $output
     * @param DialogHelper $dialog
     * @param string $class
     * @return CrudGenerator\Generators\BaseCodeGenerator
     */
    public function create(OutputInterface $output, DialogHelper $dialog, $class)
    {
        $view               = ViewFactory::getInstance();
        $fileManager        = new FileManagerStub($dialog, $output);
        $generiqueQuestion  = new GeneriqueQuestions($dialog, $output, $fileManager);
        $fileConflitManager = FileConflictManagerFactory::getInstance($output, $dialog);

        return new $class($view, $output, $fileManager, $dialog, $generiqueQuestion, $fileConflitManager);
    }
}
