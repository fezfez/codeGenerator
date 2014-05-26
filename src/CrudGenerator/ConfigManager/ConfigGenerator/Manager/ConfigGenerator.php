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
namespace CrudGenerator\ConfigManager\ConfigGenerator\Manager;

/**
 * ConfigGenerator manager instance
 *
 * @author Anthony Rochet
 */
use CrudGenerator\ConfigManager\ConfigGenerator\DataObject\ConfigDataObject;

class ConfigGenerator
{
    /**
     * @var array yamlDatas
     */
    private $yamlDatas  = array();

    /**
     * @param string $yamlDatas
     */
    public function __construct($yamlDatas)
    {
        $this->yamlDatas = $yamlDatas;
    }

    /**
     * @return \CrudGenerator\ConfigManager\ConfigGenerator\DataObject\ConfigDataObject
     */
    public function getConfig()
    {
        return new ConfigDataObject(
            $this->yamlDatas['configs']['baseNamespace'],
            $this->yamlDatas['configs']['pathToModels'],
            $this->yamlDatas['configs']['metadatasBackend'],
            $this->yamlDatas['configs']['pathToMetadatas']
        );
    }
}
