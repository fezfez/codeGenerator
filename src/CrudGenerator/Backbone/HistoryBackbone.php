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

use CrudGenerator\Generators\Questions\Cli\HistoryQuestion;
use CrudGenerator\History\EmptyHistoryException;
use CrudGenerator\Context\ContextInterface;

class HistoryBackbone
{
    /**
     * @var HistoryQuestion
     */
    private $historyQuestion = null;
    /**
     * @var ContextInterface
     */
    private $context = null;

    /**
     * @param HistoryQuestion $historyQuestion
     */
    public function __construct(HistoryQuestion $historyQuestion, ContextInterface $context)
    {
        $this->historyQuestion = $historyQuestion;
        $this->context         = $context;
    }

    public function run()
    {
        try {
            $this->historyQuestion->ask();
        } catch (EmptyHistoryException $e) {
            $this->context->log("Generation history empty", "history_empty");
        }
    }
}
