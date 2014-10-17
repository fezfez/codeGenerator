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
namespace CrudGenerator\Generators\Questions\History;

use CrudGenerator\History\HistoryManager;
use CrudGenerator\Context\ContextInterface;
use CrudGenerator\Context\QuestionWithPredefinedResponse;
use CrudGenerator\Context\PredefinedResponseCollection;
use CrudGenerator\Context\PredefinedResponse;
use CrudGenerator\History\EmptyHistoryException;

class HistoryQuestion
{
    /**
     * @var string
     */
    const QUESTION_KEY = 'history';
    /**
     * @var HistoryManager
     */
    private $historyManager = null;
    /**
     * @var ContextInterface
     */
    private $context = null;

    /**
     * @param HistoryManager $historyManager
     * @param ContextInterface $context
     */
    public function __construct(HistoryManager $historyManager, ContextInterface $context)
    {
        $this->historyManager = $historyManager;
        $this->context        = $context;
    }

    /**
     * @throws EmptyHistoryException
     * @return \CrudGenerator\Generators\GeneratorDataObject
     */
    public function ask()
    {
        $historyCollection = $this->historyManager->findAll();

        if ($historyCollection->count() === 0) {
            throw new EmptyHistoryException('Empty history');
        }

        $responseCollection = new PredefinedResponseCollection();
        foreach ($historyCollection as $history) {
            foreach ($history->getDataObjects() as $dto) {
                $name = $dto->getDto()->getMetadata()->getName();
                $responseCollection->append(new PredefinedResponse($name, $name, $dto));
            }
        }

        $question = new QuestionWithPredefinedResponse(
            "Select history",
            self::QUESTION_KEY,
            $responseCollection
        );
        $question->setShutdownWithoutResponse(true);

        return $this->context->askCollection($question);
    }
}
