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
namespace CrudGenerator\Generators\Questions\Cli;

use CrudGenerator\History\HistoryManager;
use CrudGenerator\Context\ContextInterface;
use CrudGenerator\History\EmptyHistoryException;
use CrudGenerator\Generators\ResponseExpectedException;

class HistoryQuestion
{
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

        $historyChoices = array();
        foreach ($historyCollection as $history) {
            foreach ($history->getDataObjects() as $dto) {
                $historyChoices[] = array(
                    'id'    => $dto->getDTO()->getMetadata()->getName(),
                    'label' => $dto->getDTO()->getMetadata()->getName()
                );
            }
        }

        return $this->retrieve(
            $this->context->askCollection("Select history", 'history', $historyChoices)
        );
    }

    /**
     * @param string $preSelected
     * @throws ResponseExpectedException
     * @return \CrudGenerator\Generators\GeneratorDataObject
     */
    private function retrieve($preSelected)
    {
        foreach ($this->historyManager->findAll() as $history) {
            /* @var $history \CrudGenerator\History\History */
            foreach ($history->getDataObjects() as $dto) {
                /* @var $dto \CrudGenerator\Generators\GeneratorDataObject */
                if ($dto->getDTO()->getMetadata()->getName() === $preSelected) {
                    return $dto;
                }
            }
        }

        throw new ResponseExpectedException(
            sprintf(
                "History %s does not exist",
                $preSelected
            )
        );
    }
}
