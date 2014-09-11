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
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Generators\Generator;
use CrudGenerator\Context\QuestionWithPredefinedResponse;
use CrudGenerator\Context\PredefinedResponseCollection;
use CrudGenerator\Context\PredefinedResponse;

class GenerateFileBackbone
{
    /**
     * @var string
     */
    const QUESTION_KEY = 'file_to_generate';
    /**
     * @var Generator
     */
    private $generator = null;
    /**
     * @var ContextInterface
     */
    private $context = null;

    /**
     * @param Generator $generator
     * @param ContextInterface $context
     */
    public function __construct(Generator $generator, ContextInterface $context)
    {
        $this->generator = $generator;
        $this->context   = $context;
    }

    /**
     * @param GeneratorDataObject $generator
     */
    public function run(GeneratorDataObject $generator)
    {
        $responseCollection = new PredefinedResponseCollection();

        foreach ($generator->getFiles() as $file) {
            $responseCollection->append(
                new PredefinedResponse($file['name'], $file['name'], $file['name'])
            );
        }

        $question = new QuestionWithPredefinedResponse(
            "Select a file to generate",
            self::QUESTION_KEY,
            $responseCollection
        );
        $question->setShutdownWithoutResponse(true);

        $this->generator->generateFile($generator, $this->context->askCollection($question));
    }
}
