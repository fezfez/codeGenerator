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
namespace CrudGenerator\Command;

use CrudGenerator\Backbone\BackboneInterface;
/**
 * Base representation for template generation
 *
 * @author Stéphane Demonchaux
 */
class CommandDefinition
{
    /**
     * @var string
     */
    private $action = null;
    /**
     * @var string
     */
    private $namespace = null;
    /**
     * @var string
     */
    private $definition = null;
    /**
     * @var callable
     */
    private $runner = null;


    /**
     * @param string $value
     * @return \CrudGenerator\Command\CommandDefinition
     */
    public function setAction($value)
    {
        $this->action = $value;
        return $this;
    }

    /**
     * @param string $value
     * @return \CrudGenerator\Command\CommandDefinition
     */
    public function setNamespace($value)
    {
        $this->namespace = $value;
        return $this;
    }

    /**
     * @param string $value
     * @return \CrudGenerator\Command\CommandDefinition
     */
    public function setDefinition($value)
    {
        $this->definition = $value;
        return $this;
    }

    /**
     * @param callable $value
     * @return \CrudGenerator\Command\CommandDefinition
     */
    public function setRunner(callable $value)
    {
        $this->runner = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }
    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }
    /**
     * @return string
     */
    public function getDefinition()
    {
        return $this->definition;
    }
    /**
     * @return callable
     */
    public function getRunner()
    {
        return $this->runner;
    }
}
