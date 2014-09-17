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
namespace CrudGenerator\Generators\Search;

use CrudGenerator\Context\ContextInterface;
use CrudGenerator\Context\QuestionWithPredefinedResponse;
use CrudGenerator\Context\PredefinedResponseCollection;
use CrudGenerator\Context\PredefinedResponse;
use Packagist\Api\Client;

/**
 * Find all generator allow in project
 *
 * @author Stéphane Demonchaux
 */
class GeneratorSearch
{
    /**
     * @var string
     */
    const QUESTION_KEY = 'search_generatorcollection';
    /**
     * @var Client
     */
    private $packagistApiClient = null;
    /**
     * @var ContextInterface
     */
    private $context = null;

    public function __construct(Client $packagistApiClient, ContextInterface $context)
    {
        $this->packagistApiClient = $packagistApiClient;
        $this->context            = $context;
    }

    /**
     * @return \Packagist\Api\Result\Result
     */
    public function ask()
    {
        $name = $this->context->ask('Search generator', 'generator_name');
        $list = $this->packagistApiClient->search($name, array('type' => 'fezfez-code-generator'));

        $responseCollection = new PredefinedResponseCollection();

        foreach ($list as $package) {
            /* @var \Packagist\Api\Result\Result $package */
            $predefinedResponse = new PredefinedResponse(
                $package->getName(),
                $package->getDescription(),
                $package
            );
            $predefinedResponse->setAdditionalData(array('repository' => $package->getRepository()));
            $responseCollection->append($predefinedResponse);
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
