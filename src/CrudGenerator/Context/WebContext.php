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

use Symfony\Component\HttpFoundation\Request;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Generators\ResponseExpectedException;
use CrudGenerator\Generators\Questions\Web\GeneratorQuestion;
use CrudGenerator\Generators\Questions\Web\MetaDataQuestion;
use CrudGenerator\Generators\Questions\Web\MetaDataSourcesConfiguredQuestion;
use Igorw\EventSource\Stream;

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
     * @var Request
     */
    private $request = null;
    /**
     * @var Stream
     */
    private $stream = null;

    /**
     * @param Request $request
     * @param Stream $stream
     */
    public function __construct(Request $request, Stream $stream = null)
    {
        $this->request = $request;
        $this->stream  = $stream;
    }

    /**
     * @param string  $key
     * @param boolean $consume If consume is true the response is remove after the retrieve
     *
     * @return string
     */
    private function getResponse($key, $consume = false)
    {
        if (isset($this->preResponse[$key]) === true) {
            $response = $this->preResponse[$key];

            if ($consume === true) {
                unset($this->preResponse[$key]);
            }

            return $response;
        } elseif (($response = $this->request->get($key)) !== null) {
            $method = ($this->request->request->has($key) === true) ? 'request' : 'query';

            if ($consume === true) {
                $query = $this->request->$method;

                $query->remove($key);

                $this->request->$method = $query;
            }

            return $response;
        } else {
            return null;
        }
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\Context\ContextInterface::ask()
    */
    public function ask($text, $key, $defaultResponse = null, $required = false, $helpMessage = null, $type = null)
    {
        if (false === isset($this->question['question'])) {
            $this->question['question'] = array();
        }
        if ($type === null) {
            $type = 'text';
        }

        $this->question['question'][] = array(
            'text'            => $text,
            'dtoAttribute'    => $key,
            'defaultResponse' => $defaultResponse,
            'required'        => $required,
            'type'            => $type
        );

        $response = $this->getResponse($key);

        return ($response !== null) ? $response : $defaultResponse;
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\Context\ContextInterface::ask()
    */
    public function askCollection(QuestionWithPredefinedResponse $questionWithPredefinedReponse)
    {
        $response = $this->getResponse(
            $questionWithPredefinedReponse->getUniqueKey(),
            $questionWithPredefinedReponse->isConsumeResponse(
        ));

        $collection = array();
        foreach ($questionWithPredefinedReponse->getPredefinedResponseCollection() as $predifinedResponse) {
            /* @var $predifinedResponse PredefinedResponse */
            $collection[] = array_merge(
                array(
                    'id' => $predifinedResponse->getId(),
                    'label' => $predifinedResponse->getLabel()
                ),
                $predifinedResponse->getAdditionalData()
            );
        }

        $this->question['question'][] = array(
            'text'            => $questionWithPredefinedReponse->getText(),
            'dtoAttribute'    => $questionWithPredefinedReponse->getUniqueKey(),
            'defaultResponse' => ($response !== null)
                                     ? $response : $questionWithPredefinedReponse->getDefaultResponse(),
            'required'        => $questionWithPredefinedReponse->isRequired(),
            'values'          => $collection,
            'type'            => ($questionWithPredefinedReponse->getType() === null)
                                     ? 'select' : $questionWithPredefinedReponse->getType()
        );

        if ($response === null) {
            if ($questionWithPredefinedReponse->isShutdownWithoutResponse() === true) {
                throw new ResponseExpectedException('Response expected');
            } else {
                return null;
            }
        } else {
            try {
                return $questionWithPredefinedReponse->getPredefinedResponseCollection()
                                                     ->offsetGetById($response)
                                                     ->getResponse();
            } catch (\Exception $e) {
                throw new ResponseExpectedException(
                    sprintf(
                        'Response "%s" does not exist',
                        $response
                    )
                );
            }
        }
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\Context\ContextInterface::confirm()
     */
    public function confirm($text, $uniqueKey)
    {
        return (bool) $this->getResponse($uniqueKey);
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\Context\ContextInterface::menu()
     */
    public function menu($text, $uniqueKey, callable $runner)
    {
        if ((bool) $this->getResponse($uniqueKey) === true) {
            $runner();
        }
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\Context\ContextInterface::log()
     */
    public function log($text, $name = null)
    {
        if ($this->stream !== null) {
            $this->stream
            ->event()
            ->setData($text)
            ->end()
            ->flush();
        }
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
        $this->preResponse[MetaDataSourcesConfiguredQuestion::QUESTION_KEY] = $generator->getMetadataSource()
                                                                                        ->getUniqueName();
        $this->preResponse[MetaDataQuestion::QUESTION_KEY]                  = $generator->getDto()
                                                                                        ->getMetadata()
                                                                                        ->getOriginalName();
        $this->preResponse[GeneratorQuestion::QUESTION_KEY]                 = $generator->getName();
    }
}
