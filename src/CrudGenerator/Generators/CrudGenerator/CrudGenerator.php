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
namespace CrudGenerator\Generators\CrudGenerator;

use CrudGenerator\DataObject;
use CrudGenerator\Generators\BaseCodeGenerator;

/**
 * Generate CRUD based on ArchitectGenerator utilisation
 *
 * @author Stéphane Demonchaux
 */
class CrudGenerator extends BaseCodeGenerator
{
    /**
     * @var string Skeleton directory
     */
    protected $skeletonDir    = null;
    /**
     * @var string Generator definition
     */
    protected $definition     = 'Generate CRUD based on ArchitectGenerator utilisation';

    /**
     * Generate the files
     * @param DataObject $dataObject
     * @throws \RuntimeException
     */
    protected function doGenerate(DataObject $dataObject)
    {
        $this->skeletonDir = __DIR__ . '/Skeleton';

        $this->dialog->askConfirmation(
            $this->output,
            '<question>Do you want to generate the "write" actions ?</question> '
        );

        $this->generateFile(
            $dataObject,
            '/crud/controller.php.phtml',
            $dataObject->getControllerPath() . $dataObject->getClassName() . 'Controller.php'
        );
        $this->generateFile($dataObject, '/crud/views/index.phtml.phtml', $dataObject->getViewPath() . 'index.phtml');

        if (in_array('show', $dataObject->getActions())) {
            $this->generateFile($dataObject, '/crud/views/show.phtml.phtml', $dataObject->getViewPath() . 'show.phtml');
        }

        if (in_array('new', $dataObject->getActions())) {
            $this->generateFile($dataObject, '/crud/views/new.phtml.phtml', $dataObject->getViewPath() . 'new.phtml');
        }

        if (in_array('edit', $dataObject->getActions())) {
            $this->generateFile($dataObject, '/crud/views/edit.phtml.phtml', $dataObject->getViewPath() . 'edit.phtml');
            $this->generateFile(
                $dataObject,
                '/crud/views/edit-js.phtml.phtml',
                $dataObject->getViewPath() . 'edit-js.phtml'
            );
        }
    }
}
