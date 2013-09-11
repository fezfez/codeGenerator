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
     * @var string
     */
    protected $dto         = 'CrudGenerator\Generators\CrudGenerator\Crud';

    /**
     * Generate the files
     * @param DataObject $dataObject
     * @throws \RuntimeException
     */
    protected function doGenerate($dataObject)
    {
        $this->skeletonDir = __DIR__ . '/Skeleton';

        if (null === $dataObject->isWriteAction()) {
            $dataObject->setWriteAction(
                $this->dialog->askConfirmation(
                    $this->output,
                    '<question>Do you want to generate the "write" actions ?</question> '
                )
            );
        }

        $controllerDir = explode('/', $dataObject->getControllerPath());
        $allDir = '';
        foreach ($controllerDir as $dir) {
            $allDir .= $dir . '/';
            $this->ifDirDoesNotExistCreate($allDir);
        }

        $viewDir = explode('/', $dataObject->getViewPath());
        $allDir = '';
        foreach ($viewDir as $dir) {
            $allDir .= $dir . '/';
            $this->ifDirDoesNotExistCreate($allDir);
        }

        $entityName        = $dataObject->getMetadata()->getName(false);
        $ucFirstEntityName = $dataObject->getMetadata()->getName(true);

        $suppDatas = array(
            'entityName'        => $dataObject->getMetadata()->getName(),
            'ucfirstEntityName' => $ucFirstEntityName,
            'hydratorName'      => $ucFirstEntityName . 'Hydrator',
            'dataObjectName'    => $ucFirstEntityName . 'DataObject',
            'collectionName'    => $ucFirstEntityName . 'Collection',
            'daoFactoryName'    => $ucFirstEntityName . 'DAOFactory',
            'exceptionName'     => 'No' . $ucFirstEntityName . 'Exception',
            'daoFactoryNamespace'            => $dataObject->getNamespace() . '\DAO\\' . $ucFirstEntityName . 'DAOFactory',
            'dtoNamespace'            => $dataObject->getNamespace() . '\DataObject\\' . $ucFirstEntityName . 'DataObject',
            'hydratorNamespace'       => $dataObject->getNamespace() . '\Hydrator\\' . $ucFirstEntityName . 'Hydrator',
            'dtoCollectionNamespace'  => $dataObject->getNamespace() . '\DataObject\\' . $ucFirstEntityName . 'Collection',
            'exceptionNamespace'      => $dataObject->getNamespace() . '\No' . $ucFirstEntityName . 'Exception',
        );

        $filesList = array(
            '/controller.php.phtml'  => $dataObject->getControllerPath() . $dataObject->getEntityName() . 'Controller.php',
            '/views/index.phtml' => $dataObject->getViewPath() . 'index.phtml',
            '/views/show.phtml' => $dataObject->getViewPath() . 'show.phtml',
            '/views/new.phtml' => $dataObject->getViewPath() . 'new.phtml',
            '/views/edit.phtml' =>$dataObject->getViewPath() . 'edit.phtml',
            '/views/edit-js.phtml' => $dataObject->getViewPath() . 'edit-js.phtml'
        );

        foreach ($filesList as $template => $destination) {
            $this->generateFile(
                $dataObject,
                $template,
                $destination,
                $suppDatas
            );
        }


        return $dataObject;
    }
}
