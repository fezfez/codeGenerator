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
namespace CrudGenerator\MetaData\Config;

use CrudGenerator\MetaData\Config\AbstractConfig;
use CrudGenerator\MetaData\Config\ConfigException;
use CrudGenerator\Utils\FileManager;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Routing\Generator\UrlGenerator;
use ReflectionClass;
use ReflectionProperty;

/**
 * Config a Dataobject for particulary Metadata adpater based on AbstractConfig
 *
 * @author Stéphane Demonchaux
 */
class MetaDataConfigReaderForm
{
    /**
     * @var FileManager File manager
     */
    private $fileManager = null;
    /**
     * @var $formFactory Form factory
     */
    private $formFactory = null;
    /**
     * @var UrlGenerator
     */
    private $urlGenerator = null;

    /**
     * Config a Dataobject for particulary Metadata adpater based on AbstractConfig
     *
     * @param FileManager $fileManager
     * @param FormFactory $formFactory
     * @param UrlGenerator $urlGenerator
     */
    public function __construct(FileManager $fileManager, FormFactory $formFactory, UrlGenerator $urlGenerator)
    {
        $this->fileManager  = $fileManager;
        $this->formFactory  = $formFactory;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * Config a Dataobject for particulary Metadata adpater based on AbstractConfig
     *
     * @param AbstractConfig $adapterConfig
     * @return \CrudGenerator\MetaData\AbstractConfig
     */
    public function config(AbstractConfig $adapterConfig)
    {
        $configPath = 'data/crudGenerator/Config/' . md5(get_class($adapterConfig));

        if (!$this->fileManager->isFile($configPath)) {
			throw new ConfigException();
        }

        $configured = unserialize($this->fileManager->fileGetContent($configPath));
        $this->isValid($configured);

        return $configured;
    }

    /**
     * @param AbstractConfig $adapterConfig
     */
    public function getForm(AbstractConfig $adapterConfig)
    {
    	$adapterConfig = clone $adapterConfig;
    	$reflect = new ReflectionClass($adapterConfig);
    	$props   = $reflect->getProperties(ReflectionProperty::IS_PROTECTED);

    	$form = $this->formFactory->createBuilder('form', null, array(
		    'action' => $this->urlGenerator->generate('metadata-save'),
		    'method' => 'POST',
		));

    	foreach ($props as $prop) {
    		$propName = $prop->getName();
    		if ($propName === 'definition') {
    			continue;
    		}
    		$form->add($propName, 'text');
    	}

    	return $form->add('save', 'submit')->getForm();
    }

    /**
     * Write data into Dataobject
     *
     * @param AbstractConfig $adapterConfig
     * @return AbstractConfig
     */
    public function write(AbstractConfig $adapterConfig, $datas)
    {
    	$adapterConfig = clone $adapterConfig;
    	$reflect = new ReflectionClass($adapterConfig);
    	$props   = $reflect->getProperties(ReflectionProperty::IS_PROTECTED);

    	foreach ($props as $prop) {
    		$propName = $prop->getName();
    		if ($propName === 'definition') {
    			continue;
    		}
			if(isset($datas[$propName])) {
	    		$prop->setAccessible(true);
	    		$prop->setValue($adapterConfig, $datas[$propName]);
			}
    	}

    	return $adapterConfig;
    }

    /**
     * @param AbstractConfig $adapterConfig
     */
    public function isValid(AbstractConfig $adapterConfig)
    {
    	$adapterConfig->test();
    	$configPath = 'data/crudGenerator/Config/' . md5(get_class($adapterConfig));
    	$this->fileManager->filePutsContent($configPath, serialize($adapterConfig));
    	return true;
    }
}
