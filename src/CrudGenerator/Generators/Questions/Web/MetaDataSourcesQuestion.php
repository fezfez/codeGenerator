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

use CrudGenerator\MetaData\MetaDataSourceFinder;
use CrudGenerator\Context\ContextInterface;
use CrudGenerator\Context\QuestionWithPredefinedResponse;
use CrudGenerator\Context\PredefinedResponseCollection;
use CrudGenerator\Context\PredefinedResponse;

class MetaDataSourcesQuestion
{
	const QUESTION_KEY = 'metadatasource';
    /**
     * @var MetaDataSourceFinder
     */
    private $metadataSourceFinder = null;
    /**
     * @var ContextInterface
     */
    private $context = null;

    /**
     * @param MetaDataSourceFinder $metadataSourceFinder
     * @param ContextInterface $context
     */
    public function __construct(MetaDataSourceFinder $metadataSourceFinder, ContextInterface $context)
    {
        $this->metadataSourceFinder = $metadataSourceFinder;
        $this->context              = $context;
    }

    /**
     * Ask witch MetaData Source you want to use
     *
     * @param string $choice
     * @return \CrudGenerator\MetaData\MetaDataSource
     */
    public function ask($choice = null)
    {
        $responseCollection = new PredefinedResponseCollection();

        foreach ($this->metadataSourceFinder->getAllAdapters() as $backend) {
            /* @var $backend \CrudGenerator\MetaData\MetaDataSource */
            if(null === $backend->getFalseDependencies()) {
                $responseCollection->append(
                	new PredefinedResponse($backend->getMetaDataDAOFactory(), $backend->getDefinition(), $backend)
                );
            }
        }

        $question = new QuestionWithPredefinedResponse(
            "Select source",
            self::QUESTION_KEY,
            $responseCollection
        );
        $question->setPreselectedResponse($choice);
        $question->setShutdownWithoutResponse(true);

        return $this->context->askCollection($question);
    }
}
