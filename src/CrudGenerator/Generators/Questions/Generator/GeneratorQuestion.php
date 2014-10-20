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
namespace CrudGenerator\Generators\Questions\Generator;

use CrudGenerator\Context\ContextInterface;
use CrudGenerator\Context\QuestionWithPredefinedResponse;
use CrudGenerator\Context\PredefinedResponseCollection;
use CrudGenerator\Context\PredefinedResponse;
use CrudGenerator\Generators\Finder\GeneratorFinderInterface;
use CrudGenerator\MetaData\DataObject\MetaDataInterface;

class GeneratorQuestion
{
    const QUESTION_KEY = 'generator';
    /**
     * @var GeneratorFinderInterface
     */
    private $generatorFinder = null;
    /**
     * @var ContextInterface
     */
    private $context = null;

    /**
     * @param GeneratorFinderInterface $generatorFinder
     * @param ContextInterface $context
     */
    public function __construct(GeneratorFinderInterface $generatorFinder, ContextInterface $context)
    {
        $this->generatorFinder = $generatorFinder;
        $this->context         = $context;
    }

    /**
     * @return string
     */
    public function ask(MetaDataInterface $metadata)
    {
        $responseCollection = new PredefinedResponseCollection();
        $generators         = $this->generatorFinder->getAllClasses($metadata);

        foreach ($generators as $name) {
            $responseCollection->append(new PredefinedResponse($name, $name, $name));
        }

        $question = new QuestionWithPredefinedResponse(
            "Select generator",
            self::QUESTION_KEY,
            $responseCollection
        );
        $question->setShutdownWithoutResponse(true);

        return $this->context->askCollection($question);
    }
}