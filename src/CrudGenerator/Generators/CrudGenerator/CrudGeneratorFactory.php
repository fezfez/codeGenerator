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
namespace CrudGenerator\Generators\CrudGenerator;

use CrudGenerator\Generators\ArchitectGenerator\MetadataToArray;
use CrudGenerator\Generators\ArchitectGenerator\ArchitectGenerator;
use CrudGenerator\Generators\GeneriqueQuestions;
use CrudGenerator\Generators\Strategies\StrategyInterface;
use CrudGenerator\Generators\GeneratorDependencies;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\DialogHelper;

/**
 * Generate CRUD based on ArchitectGenerator utilisation
 *
 * @author Stéphane Demonchaux
 */
class CrudGeneratorFactory
{
    /**
     * Base code generator
     * @param OutputInterface $output
     * @param DialogHelper $dialog
     * @param GeneriqueQuestions $generiqueQuestion
     * @param StrategyInterface $strategy
     */
    public static function getInstance(
        OutputInterface $output,
        DialogHelper $dialog,
        GeneriqueQuestions $generiqueQuestion,
        StrategyInterface $strategy,
        GeneratorDependencies $generatorDepencies
    ) {
        return new CrudGenerator(
            $output,
            $dialog,
            $generiqueQuestion,
            $strategy,
            $generatorDepencies
        );
    }
}
