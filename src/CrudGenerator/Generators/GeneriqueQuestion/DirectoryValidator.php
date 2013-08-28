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

namespace CrudGenerator\Generators\GeneriqueQuestion;

use CrudGenerator\DataObject;
use CrudGenerator\Utils\FileManager;

/**
 * @author Stéphane Demonchaux
 */
class DirectoryValidator
{
    /**
     * @var DataObject $dataObject
     */
    private $dataObject            = null;
    /**
     * @var FileManager FileManager
     */
    private $fileManager        = null;

    /**
     * @param DataObject $dialog
     * @param FileManager $fileManager
     */
    public function __construct(DataObject $dataObject, FileManager $fileManager)
    {
        $this->dataObject  = $dataObject;
        $this->fileManager = $fileManager;
    }

    /**
     * @param string $directory
     * @throws \InvalidArgumentException
     * @return string
     */
    public function __invoke($directory)
    {
        $moduleName = $this->dataObject->getModule();
        if (!$this->fileManager->isDir($moduleName . '/' . $directory)) {
            throw new \InvalidArgumentException(
                sprintf('Directory "%s" does not exist.', $moduleName . $directory)
            );
        }

        return $directory;
    }
}
