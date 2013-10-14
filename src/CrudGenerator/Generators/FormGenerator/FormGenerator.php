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
namespace CrudGenerator\Generators\FormGenerator;

use CrudGenerator\DataObject;
use CrudGenerator\Generators\BaseCodeGenerator;

/**
 * Generate forms
 *
 * @author Stéphane Demonchaux
 */
class FormGenerator extends BaseCodeGenerator
{
    /**
     * @var string Skeleton directory
     */
    protected $skeletonDir    = null;
    /**
     * @var string Generator definition
     */
    protected $definition     = 'Generate forms';
    /**
     * @var string
     */
    protected $dto         = 'CrudGenerator\Generators\FormGenerator\Form';
    /**
     * Generate the files
     * @param DataObject $dataObject
     * @throws \RuntimeException
     */
    public function doGenerate($dataObject)
    {
        $this->skeletonDir = __DIR__ . '/Skeleton';

        $dataObject->setDirectory($this->generiqueQuestion->directoryQuestion($dataObject));
        $dataObject->setNamespace($this->generiqueQuestion->namespaceQuestion());
        $basePath = $dataObject->getModule() . '/' . $dataObject->getDirectory() . '/';
        $formPath = $basePath . 'Form/';

        $this->ifDirDoesNotExistCreate($formPath);

        $this->generateFile(
            $dataObject,
            '/form/FormFactory.phtml',
            $basePath . $dataObject->getEntityName() . 'FormFactory.php'
        );
        $this->generateFile(
            $dataObject,
            '/form/AbstractForm.phtml',
            $formPath . 'Abstract' . $dataObject->getEntityName() . 'Form.php'
        );
        $this->generateFile(
            $dataObject,
            '/form/Form.phtml',
            $formPath . $dataObject->getEntityName() . 'Form.php'
        );
        $this->generateFile(
            $dataObject,
            '/form/FormFilter.phtml',
            $formPath . $dataObject->getEntityName() . 'FormFilter.php'
        );
        $this->generateFile(
            $dataObject,
            '/form/Print.phtml',
            $formPath . 'FormPrint.phtml'
        );

        return $dataObject;
    }
}
