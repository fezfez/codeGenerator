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
namespace CrudGenerator\ConfigManager\ConfigGenerator\DataObject;

/**
 * Configuration for autogeneration
 *
 * @author Anthony Rochet
 */
class ConfigDataObject
{
    /**
     * @var string BaseNamespace
     */
    private $baseNamespace    = null;
    /**
     * @var string PathToModels
     */
    private $pathToModels     = null;
    /**
     * @var string MetadatasBackend
     */
    private $metadatasBackend = null;
    /**
     * @var string PathToMetadatas
     */
    private $pathToMetadatas  = null;

    /**
     *
     * @param string $baseNamespace
     * @param string $pathToModels
     * @param string $metadatasBackend
     * @param string $pathToMetadatas
     */
    public function __construct(
        $baseNamespace = null,
        $pathToModels = null,
        $metadatasBackend = null,
        $pathToMetadatas = null
    ) {
        $this->baseNamespace       = $baseNamespace;
        $this->pathToModels        = $pathToModels;
        $this->metadatasBackend    = $metadatasBackend;
        $this->pathToMetadatas     = $pathToMetadatas;
    }

    /**
     * Set baseNamespace
     * @param string $value
     */
    public function setBaseNamespace($value)
    {
        $this->baseNamespace = $value;
        return $this;
    }

    /**
     * Set pathToModels
     * @param string $value
     */
    public function setPathToModels($value)
    {
        $this->pathToModels = $value;
        return $this;
    }


    /**
     * Set metadatasBackend
     * @param string $value
     */
    public function setMetadatasBackend($value)
    {
        $this->metadatasBackend = $value;
        return $this;
    }


    /**
     * Set pathToMetadatas
     * @param string $value
     */
    public function setPathToMetadatas($value)
    {
        $this->pathToMetadatas = $value;
        return $this;
    }

    /**
     * Get baseNamespace
     * @return string
     */
    public function getBaseNamespace()
    {
        return $this->baseNamespace;
    }

    /**
     * Get pathToModels
     * @return string
     */
    public function getPathToModels()
    {
        return $this->pathToModels;
    }

    /**
     * Get metadatasBackend
     * @return string
     */
    public function getMetadatasBackend()
    {
        return $this->metadatasBackend;
    }

    /**
     * Get pathToMetadatas
     * @return string
     */
    public function getPathToMetadatas()
    {
        return $this->pathToMetadatas;
    }
}
