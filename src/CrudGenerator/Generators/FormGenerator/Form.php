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

class Form extends DataObject
{
    protected $generator      = 'CrudGenerator\Generators\FormGenerator\FormGenerator';

    /**
     * @var string Target directory
     */
    private $directory       = null;
    /**
     * @var string Target namespace
     */
    private $namespace       = null;

    /**
     * Set Directory
     * @param string $value
     * @return \CrudGenerator\DataObject
     */
    public function setDirectory($value)
    {
        $this->directory = $value;
        return $this;
    }
    /**
     * Get directory
     *
     * @return string
     */
    public function getDirectory()
    {
        return $this->directory;
    }
    /**
     * Set Namespace
     * @param string $value
     * @return \CrudGenerator\DataObject
     */
    public function setNamespace($value)
    {
        $this->namespace = $value;
        return $this;
    }
    /**
     * Set Namespace
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }
    /**
     * Get namespace path
     * @return string
     */
    public function getNamespacePath()
    {
        return str_replace('\\', '/', $this->namespace);
    }
}
