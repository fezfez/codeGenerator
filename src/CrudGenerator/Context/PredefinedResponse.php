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

/**
 * Represntation of a predefined response
 *
 */
class PredefinedResponse
{
    /**
     * @var string
     */
    private $id = null;
    /**
     * @var string
     */
    private $labelle = null;
    /**
     * @var mixed
     */
    private $response = null;
    /**
     * @var array
     */
    private $additionalData = array();

    /**
     * @param string $id
     * @param string $label
     * @param unknown $response
     */
    public function __construct($id, $label, $response)
    {
        $this->id       = $id;
        $this->label    = $label;
        $this->response = $response;
    }
    /**
     * The response Identification
     *
     * @param string $value
     * @return \CrudGenerator\Context\PredefinedResponse
     */
    public function setId($value)
    {
        $this->id = $value;
        return $this;
    }

    /**
     * The response text
     *
     * @param string $value
     * @return \CrudGenerator\Context\PredefinedResponse
     */
    public function setLabel($value)
    {
        $this->label = $value;
        return $this;
    }
    /**
     * If the user choice this response, this value woulmd be returned
     *
     * @param mixed $value
     * @return \CrudGenerator\Context\PredefinedResponse
     */
    public function setResponse($value)
    {
    	$this->response = $value;
    	return $this;
    }
    /**
     * Additional data will be added only in WebContext
     *
     * @param array $value
     * @return \CrudGenerator\Context\PredefinedResponse
     */
    public function setAdditionalData(array $value)
    {
    	$this->additionalData = $value;
    	return $this;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
    	return $this->label;
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return array
     */
    public function getAdditionalData()
    {
    	return $this->additionalData;
    }
}
