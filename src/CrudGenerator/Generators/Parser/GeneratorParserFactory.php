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
namespace CrudGenerator\Generators\Parser;

use Symfony\Component\Yaml\Parser;
use CrudGenerator\Utils\FileManager;
use CrudGenerator\Utils\PhpStringParser;
use CrudGenerator\Generators\Strategies\GeneratorStrategyFactory;
use CrudGenerator\Generators\Finder\GeneratorFinderFactory;
use CrudGenerator\Generators\Parser\ParserCollectionFactory;
use CrudGenerator\Context\ContextInterface;

/**
 * Find all generator allow in project
 *
 * @author Stéphane Demonchaux
 */
class GeneratorParserFactory
{
    /**
     * @param ContextInterface $context
     * @return \CrudGenerator\Generators\GeneratorParser
     */
    public static function getInstance(ContextInterface $context)
    {
        $generatorFinder  = GeneratorFinderFactory::getInstance();
        $parserCollection = ParserCollectionFactory::getInstance($context);

        return new GeneratorParser(
            new FileManager(),
            new Parser(),
            new PhpStringParser(),
            GeneratorStrategyFactory::getInstance($context),
            $generatorFinder,
            $parserCollection
        );
    }
}
