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

use CrudGenerator\History\HistoryManager;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\DialogHelper;

class HistoryQuestion
{
    /**
     * @var HistoryManager
     */
    private $historyManager = null;
    /**
     * @var OutputInterface
     */
    private $output = null;
    /**
     * @var DialogHelper
     */
    private $dialog = null;
    /**
     * @param HistoryManager $historyManager
     * @param OutputInterface $output
     * @param DialogHelper $dialog
     */
    public function __construct(HistoryManager $historyManager, OutputInterface $output, DialogHelper $dialog)
    {
        $this->historyManager = $historyManager;
        $this->output         = $output;
        $this->dialog         = $dialog;
    }

    /**
     * @return \CrudGenerator\History\History
     */
    public function ask()
    {
        $historyCollection = $this->historyManager->findAll();

        if ($historyCollection->count() === 0) {
            throw new \RuntimeException('Empty history');
        }

        $historyChoices = array();
        foreach ($historyCollection as $history) {
            foreach ($history->getDataObjects() as $dto) {
                $historyChoices[$dto->getEntity() . ' ' . $dto->getGenerator()] = $dto;
            }
        }

        $historyKeysChoices = array_keys($historyChoices);
        $choice = $this->dialog->select(
            $this->output,
            "<question>History to regenerate</question> \n> ",
            $historyKeysChoices
        );

        return $historyChoices[$historyKeysChoices[$choice]];
    }
}
