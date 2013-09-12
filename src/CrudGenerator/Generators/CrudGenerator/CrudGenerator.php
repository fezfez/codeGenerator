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
        $entityName        = $dataObject->getMetadata()->getName(false);
        $ucFirstEntityName = $dataObject->getMetadata()->getName(true);

        $dataObject = $this->manageOption($dataObject, 'DisplayName', 'Display name (in view, title etc..) : ', $entityName);
        $dataObject = $this->manageOption($dataObject, 'DisplayNames', 'Display name au plurielle (in view, title etc..) : ', $entityName . 's');
        $dataObject = $this->manageOption($dataObject, 'ControllerName', 'Controller name (ucFirst and without "Controller"): ', $ucFirstEntityName);
        $dataObject = $this->manageOption($dataObject, 'PrefixRouteName', 'Prefix route name (lower case): ', $this->unCamelCase($entityName));
        $dataObject = $this->manageOption($dataObject, 'ModelNamespace', 'Model namespace : ');

        foreach ($dataObject->getMetadata()->getColumnCollection() as $column) {
            if (null === $dataObject->getAttributeName($column->getName())) {
                $dataObject->setAttributeName(
                    $column->getName(),
                    $this->dialog->ask(
                        $this->output,
                        '<question>Display name for "' . $column->getName() . '" attribute</question> ',
                        $column->getName()
                    )
                );
            }
        }

        $this->createFullPathDirIfNotExist($dataObject->findControllerPath());
        $this->createFullPathDirIfNotExist($dataObject->findViewPath());

        $homeRoute   = $dataObject->getPrefixRouteName();
        $newRoute    = $homeRoute . '-new';
        $showRoute   = $homeRoute . '-show';
        $editRoute   = $homeRoute . '-edit';
        $deleteRoute = $homeRoute . '-delete';

        $suppDatas = array(
            'homeRoute'              => $homeRoute,
            'newRoute'               => $newRoute,
            'showRoute'              => $showRoute,
            'editRoute'              => $editRoute,
            'deleteRoute'            => $deleteRoute,
            'entityName'             => $entityName,
            'ucfirstEntityName'      => $ucFirstEntityName,
            'hydratorName'           => $ucFirstEntityName . 'Hydrator',
            'dataObjectName'         => $ucFirstEntityName . 'DataObject',
            'collectionName'         => $ucFirstEntityName . 'Collection',
            'daoFactoryName'         => $ucFirstEntityName . 'DAOFactory',
            'exceptionName'          => 'No' . $ucFirstEntityName . 'Exception',
            'daoFactoryNamespace'    => $dataObject->getModelNamespace() . '\\' . $ucFirstEntityName . 'DAOFactory',
            'dtoNamespace'           => $dataObject->getModelNamespace() . '\DataObject\\' . $ucFirstEntityName . 'DataObject',
            'hydratorNamespace'      => $dataObject->getModelNamespace() . '\Hydrator\\' . $ucFirstEntityName . 'Hydrator',
            'dtoCollectionNamespace' => $dataObject->getModelNamespace() . '\DataObject\\' . $ucFirstEntityName . 'Collection',
            'exceptionNamespace'     => $dataObject->getModelNamespace() . '\No' . $ucFirstEntityName . 'Exception',
        );

        $filesList = array(
            '/controller.php.phtml' => $dataObject->findControllerPath() . $dataObject->getControllerName() . 'Controller.php',
            '/views/index.phtml'    => $dataObject->findViewPath() . 'index.phtml',
            '/views/show.phtml'     => $dataObject->findViewPath() . 'show.phtml',
            '/views/new.phtml'      => $dataObject->findViewPath() . 'new.phtml',
            '/views/edit.phtml'     => $dataObject->findViewPath() . 'edit.phtml',
            '/views/edit-js.phtml'  => $dataObject->findViewPath() . 'edit-js.phtml'
        );

        foreach ($filesList as $template => $destination) {
            $this->generateFile(
                $dataObject,
                $template,
                $destination,
                $suppDatas
            );
        }

        $this->output->writeln("Route to add to module.config.php
'" . $homeRoute . "' => array(
    'type' => 'Zend\Mvc\Router\Http\Literal',
    'options' => array(
        'route'    => '/" . $homeRoute . "',
        'defaults' => array(
            'controller' => 'Application\Controller\\" . $dataObject->getControllerName() ."',
            'action'     => 'index',
        ),
    ),
),
'" . $newRoute . "' => array(
    'type' => 'Zend\Mvc\Router\Http\Literal',
    'options' => array(
        'route'    => '/" . $newRoute . "',
        'defaults' => array(
            'controller' => 'Application\Controller\\" . $dataObject->getControllerName() ."',
            'action'     => 'new',
        ),
    ),
),
'" . $showRoute . "' => array(
    'type' => 'Zend\Mvc\Router\Http\Segment',
    'options' => array(
        'route'    => '/" . $showRoute . "/[:id]',
        'defaults' => array(
            'controller' => 'Application\Controller\\" . $dataObject->getControllerName() ."',
            'action'     => 'show',
        ),
    ),
),
'" . $editRoute . "' => array(
    'type' => 'Zend\Mvc\Router\Http\Segment',
    'options' => array(
        'route'    => '/" . $editRoute . "/[:id]',
        'defaults' => array(
            'controller' => 'Application\Controller\\" . $dataObject->getControllerName() ."',
            'action'     => 'edit',
        ),
    ),
),
'" . $deleteRoute . "' => array(
    'type' => 'Zend\Mvc\Router\Http\Segment',
    'options' => array(
        'route'    => '/" . $deleteRoute . "/[:id]',
        'defaults' => array(
            'controller' => 'Application\Controller\\" . $dataObject->getControllerName() ."',
            'action'     => 'delete',
        ),
    ),
),
And add controller as invokable

'controllers' => array(
    'invokables' => array(
        'Application\Controller\\" . $dataObject->getControllerName . "' => 'Application\Controller\\" . $dataObject->getControllerName() ."Controller'
    )
)
");


        return $dataObject;
    }
}
