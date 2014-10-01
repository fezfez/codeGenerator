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
    private $question   = array();
    private $response   = array();
    private $driver     = null;
    private $uniqueName = null;
    private $metadataDaoFactory = null;

    public function __construct($uniqueName)
    {
        $this->uniqueName = $uniqueName;
    }

    public function addQuestion($description, $attribute)
    {
        $this->question[] = array('desc' => $description, 'attr' => $attribute);
    }

    public function getQuestion()
    {
        return $this->question;
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

    public function getResponse($attribute)
    {
        return (isset($this->response[$attribute]) === true) ? $this->response[$attribute] : null;
    }

    public function setDriver($value)
    {
        $this->driver = $value;

        return $this;
    }

    public function getDriver()
    {
        return $this->driver;
    }

    public function getUniqueName()
    {
        return $this->uniqueName;
    }

    public function setMetadataDaoFactory($value)
    {
        $this->metadataDaoFactory = $value;

        return $this;
    }

    public function getMetadataDaoFactory()
    {
        return $this->metadataDaoFactory;
    }

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
