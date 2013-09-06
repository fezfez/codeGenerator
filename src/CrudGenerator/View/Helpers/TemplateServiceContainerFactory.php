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
namespace CrudGenerator\View\Helpers;

use CrudGenerator\View\ViewHelperFactoryInterface;
use CrudGenerator\DataObject;
use CrudGenerator\View\Helpers\TemplateServiceContainerStrategies\PDOStrategy;
use CrudGenerator\View\Helpers\TemplateServiceContainerStrategies\ZendFramework2Strategy;
use CrudGenerator\MetaData\Sources\Doctrine2\MetadataDataObjectDoctrine2;
use CrudGenerator\MetaData\Sources\PDO\MetadataDataObjectPDO;

/**
 * @author stephane.demonchaux
 *
 */
class TemplateServiceContainerFactory implements ViewHelperFactoryInterface
{
    /**
     * @param DataObject $dataObject
     * @return \CrudGenerator\View\Helpers\TemplateServiceContainer
     */
    public static function getInstance(DataObject $dataObject)
    {
        $metadata = $dataObject->getMetadata();

        if ($metadata instanceof MetadataDataObjectDoctrine2) {
            $strategy = new ZendFramework2Strategy();
        } elseif ($metadata instanceof MetadataDataObjectPDO) {
            $strategy = new PDOStrategy();
        }

        return new TemplateServiceContainer($strategy);
    }
}
