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
namespace CrudGenerator\MetaData\Driver;

/**
 * @author Stéphane Demonchaux
 */
class DriverConfig implements \JsonSerializable
{
    /**
     * @var array
     */
    private $question = array();
    /**
     * @var array
     */
    private $response = array();
    /**
     * @var string
     */
    private $driver = null;
    /**
     * @var string
     */
    private $uniqueName = null;
    /**
     * @var string
     */
    private $metadataDaoFactory = null;

    /**
     * @param string $uniqueName
     */
    public function __construct($uniqueName)
    {
        $this->uniqueName = $uniqueName;
    }

    /**
     * @param string $value
     * @return \CrudGenerator\MetaData\Driver\DriverConfig
     */
    public function setDriver($value)
    {
        $this->driver = $value;

        return $this;
    }

    /**
     * @param string $value
     * @return \CrudGenerator\MetaData\Driver\DriverConfig
     */
    public function setMetadataDaoFactory($value)
    {
        $this->metadataDaoFactory = $value;

        return $this;
    }

    /**
     * @param string $attribute
     * @param string $response
     * @return \CrudGenerator\MetaData\Driver\DriverConfig
     */
    public function response($attribute, $response)
    {
        $this->response[$attribute] = $response;

        return $this;
    }

    /**
     * @param string $description
     * @param string $attribute
     * @return \CrudGenerator\MetaData\Driver\DriverConfig
     */
    public function addQuestion($description, $attribute)
    {
        $this->question[] = array('desc' => $description, 'attr' => $attribute);
        return $this;
    }

    /**
     * @return array
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * @param string $attribute
     * @return string|null
     */
    public function getResponse($attribute)
    {
        return (isset($this->response[$attribute]) === true) ? $this->response[$attribute] : null;
    }

    /**
     * @return string
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * @return string
     */
    public function getUniqueName()
    {
        return $this->uniqueName;
    }

    /**
     * @return string
     */
    public function getMetadataDaoFactory()
    {
        return $this->metadataDaoFactory;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return array(
            'metadataDaoFactory' => $this->metadataDaoFactory,
            'driver'             => $this->driver,
            'response'           => $this->response,
            'uniqueName'         => $this->uniqueName
        );
    }
}
