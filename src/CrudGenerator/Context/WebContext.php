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

namespace CrudGenerator\Context;

use Silex\Application;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Generators\Questions\Web\GeneratorQuestion;
use CrudGenerator\Generators\Questions\Web\MetaDataQuestion;
use CrudGenerator\Generators\Questions\Web\MetaDataSourcesConfiguredQuestion;

class WebContext implements ContextInterface, \JsonSerializable
{
    /**
     * @var array
     */
    private $question = array();
    /**
     * @var array
     */
    private $preResponse = null;
    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    private $request = null;

    /**
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        $this->request = $application->offsetGet('request')->request;
    }

    /**
     * @param string $key
     * @return string
     */
    private function getResponse($key)
    {
        if (isset($this->preResponse[$key]) === true) {
            return $this->preResponse[$key];
        } else {
            return $this->request->get($key);
        }
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\Context\ContextInterface::ask()
    */
    public function ask($text, $key, $defaultResponse = null, $required = false, $helpMessage = null)
    {
        if (false === isset($this->question['question'])) {
            $this->question['question'] = array();
        }
        $this->question['question'][] = array(
            'text'            => $text,
            'dtoAttribute'    => $key,
            'defaultResponse' => $defaultResponse,
            'required'        => $required,
            'type'            => 'text'
        );

        return $this->getResponse($key);
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\Context\ContextInterface::ask()
    */
    public function askCollection($text, $uniqueKey, array $collection, $defaultResponse = null, $required = false, $helpMessage = null)
    {
        $response = $this->getResponse($uniqueKey);

        $this->question['question'][] = array(
            'text'            => $text,
            'dtoAttribute'    => $uniqueKey,
            'defaultResponse' => ($response !== null) ? $response : $defaultResponse,
            'required'        => $required,
            'values'          => $collection,
            'type'            => 'select'
        );

        return $response;
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\Context\ContextInterface::confirm()
     */
    public function confirm($text, $uniqueKey)
    {
        $response = $this->getResponse($uniqueKey);

        if ($response !== null && is_numeric($response) === false) {
        	throw new \Exception(sprintf('%s', $response));
        } else {
        	return (boolean) $response;
        }
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\Context\ContextInterface::log()
     */
    public function log($text, $name = null)
    {
        if (isset($this->question[$name]) === true && is_array($this->question[$name]) === false) {
            $this->question[$name] = array($this->question[$name]);
        }
        if (isset($this->question[$name]) === true) {
            $this->question[$name][] = $text;
        } else {
            $this->question[$name] = $text;
        }
    }

    /* (non-PHPdoc)
     * @see JsonSerializable::jsonSerialize()
     */
    public function jsonSerialize()
    {
        return $this->question;
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\Context\ContextInterface::publishGenerator()
     */
    public function publishGenerator(GeneratorDataObject $generator)
    {
        $this->preResponse[MetaDataSourcesConfiguredQuestion::QUESTION_KEY] = $generator->getMetadataSource()->getUniqueName();
        $this->preResponse[MetaDataQuestion::QUESTION_KEY]                  = $generator->getDTO()->getMetadata()->getOriginalName();
        $this->preResponse[GeneratorQuestion::QUESTION_KEY]                 = $generator->getName();
    }
}
