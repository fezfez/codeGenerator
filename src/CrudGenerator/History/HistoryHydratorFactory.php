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
namespace CrudGenerator\History;

use Symfony\Component\Yaml\Dumper;
use Symfony\Component\Yaml\Parser;
use CrudGenerator\Context\ContextInterface;
use CrudGenerator\Context\CliContext;
use CrudGenerator\Generators\Questions\MetaDataSourcesQuestionFactory;
use CrudGenerator\Generators\Questions\MetaDataQuestionFactory;
use CrudGenerator\Context\WebContext;

/**
 * HistoryManager instance
 *
 * @author Stéphane Demonchaux
 */
class HistoryHydratorFactory
{
    /**
     * @param ContextInterface $context
     * @return \CrudGenerator\History\HistoryHydrator
     */
    public static function getInstance(ContextInterface $context)
    {
        if ($context instanceof CliContext || $context instanceof WebContext) {
            $metaDataSourceQuestion = MetaDataSourcesQuestionFactory::getInstance($context);
            $metaDataQuestion       = MetaDataQuestionFactory::getInstance($context);

            $yampDump   = new Dumper();
            $yampParser = new Parser();

            return new HistoryHydrator($yampDump, $yampParser, $metaDataSourceQuestion, $metaDataQuestion);
        } else {
            throw new \InvalidArgumentException('Context web not supported');
        }
    }
}