<?php
/**
  * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Generators\Questions\History;

use CrudGenerator\Context\ContextInterface;
use CrudGenerator\Context\PredefinedResponse;
use CrudGenerator\Context\PredefinedResponseCollection;
use CrudGenerator\Context\QuestionWithPredefinedResponse;
use CrudGenerator\History\EmptyHistoryException;
use CrudGenerator\History\HistoryManager;

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
     * @param HistoryManager   $historyManager
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
