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

use CrudGenerator\Utils\FileManager;
use CrudGenerator\Utils\PhpStringParser;
use CrudGenerator\Generators\Generator;
use CrudGenerator\Generators\GeneratorParser;

class FileParser implements ParserInterface
{
    /**
     * @var FileManager
     */
    private $fileManager = null;

    /**
     * @param FileManager $fileManager
     */
    public function __construct(FileManager $fileManager)
    {
        $this->fileManager = $fileManager;
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\Generators\Parser\ParserInterface::evaluate()
     */
    public function evaluate(array $process, PhpStringParser $parser, Generator $generator, array $questions)
    {
        $skeletonPath = dirname($generator->getPath()) . '/Skeleton/';

        foreach ($process['filesList'] as $files) {
            foreach ($files as $templateName => $tragetFile) {
                if($templateName === GeneratorParser::ENVIRONNEMENT_CONDITION) {
                    $generator = $this->evaluateEnvironnementCondition($process, $parser, $generator, $skeletonPath);
                } else {
                    $generator->addFile($skeletonPath, $templateName, $parser->parse($tragetFile));
                }
            }
        }

        return $generator;
    }

    /**
     * @param array $environnementNode
     * @param PhpStringParser $parser
     * @param Generator $generator
     * @param string $skeletonPath
     * @return Generator
     */
    private function evaluateEnvironnementCondition(array $environnementNode, PhpStringParser $parser, Generator $generator, $skeletonPath)
    {
        foreach ($environnementNode as $environements) {
            foreach ($environements as $environment => $environmentTemplates) {
                foreach ($environmentTemplates as $environmentTemplateName => $environmentTragetFile) {
                    if ($environment === 'zf2') {
                        try {
                            \CrudGenerator\EnvironnementResolver\ZendFramework2Environnement::getDependence($this->fileManager);
                            $generator->addFile($skeletonPath, $environmentTemplateName, $parser->parse($environmentTragetFile));
                        } catch (\CrudGenerator\EnvironnementResolver\EnvironnementResolverException $e) {
                        }
                    } elseif ($environment === GeneratorParser::CONDITION_ELSE) {
                        $generator->addFile($skeletonPath, $environmentTemplateName, $parser->parse($environmentTragetFile));
                    }
                }
            }
        }

        return $generator;
    }
}
