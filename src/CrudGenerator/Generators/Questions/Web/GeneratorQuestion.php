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
namespace CrudGenerator\Generators\Questions\Web;

use CrudGenerator\Generators\Finder\GeneratorFinder;
use CrudGenerator\Context\ContextInterface;

class GeneratorQuestion
{
    /**
     * @var GeneratorFinder
     */
    private $generatorFinder = null;
    /**
     * @var ContextInterface
     */
    private $context = null;

    /**
     * @param GeneratorFinder $generatorFinder
     * @param ContextInterface $context
     */
    public function __construct(GeneratorFinder $generatorFinder, ContextInterface $context)
    {
        $this->generatorFinder = $generatorFinder;
        $this->context         = $context;
    }

    /**
     * @return string
     */
    public function ask()
    {
        $generatorArray = array();
        foreach ($this->generatorFinder->getAllClasses() as $path => $name) {
        	$generatorArray[] = array('id' => $name, 'label' => $name);
        }

        return $this->retrieve(
        	$this->context->askCollection("Witch generator", 'generator', $generatorArray)
        );
    }

    /**
     * @param string $preSelected
     * @throws \InvalidArgumentException
     * @return unknown
     */
    private function retrieve($preSelected)
    {
    	$generatorArray = array();
    	foreach ($this->generatorFinder->getAllClasses() as $path => $name) {
    		if ($name === $preSelected) {
    			return $name;
    		}
    	}

    	throw new \InvalidArgumentException(
    		sprintf(
    			"Generator %s does not exist",
    			$preSelected
    		)
    	);
    }
}
