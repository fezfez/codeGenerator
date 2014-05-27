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
namespace CrudGenerator\Backbone;

use CrudGenerator\Context\ContextInterface;
use CrudGenerator\Generators\Questions\Cli\HistoryQuestion;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Generators\GeneratorCli;

class GenerateFileBackbone
{
    /**
     * @var GeneratorCli
     */
    private $generator = null;
    /**
     * @var ContextInterface
     */
    private $context = null;

    /**
     * @param GeneratorCli $generator
     * @param ContextInterface $context
     */
    public function __construct(GeneratorCli $generator, ContextInterface $context)
    {
        $this->generator = $generator;
        $this->context   = $context;
    }

    /**
     * @param GeneratorDataObject $generator
     * @param string $fileName
     */
    public function run(GeneratorDataObject $generator)
    {
        $files = array();
        foreach ($generator->getFiles() as $file) {
            $files[] = $file['name'];
        }
        $fileName = $this->context->askCollection("Select a file to generate", "file_to_generate", $files);
        $this->generator->generateFile($generator, $fileName);
    }
}
