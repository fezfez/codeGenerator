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
namespace CrudGenerator\Generators;

use Symfony\Component\Yaml\Yaml;
use CrudGenerator\Utils\FileManager;
use CrudGenerator\Utils\PhpStringParser;
use CrudGenerator\Generators\Strategies\ViewFileStategy;
use CrudGenerator\MetaData\DataObject\MetaData;
use CrudGenerator\Generators\GeneratorFinder;

/**
 * Find all generator allow in project
 *
 * @author Stéphane Demonchaux
 */
class GeneratorParser
{
	const COMPLEX_QUESTION = 'complex';
    /**
     * @var Yaml YamlParser
     */
    private $yaml = null;
    /**
     * @var FileManager file manager
     */
    private $fileManager = null;
    /**
     * @var PhpStringParser PhpStringParser
     */
    private $phpStringParser = null;
    /**
     * @var ViewFileStategy
     */
    private $viewFile = null;
    /**
     * @var GeneratorFinder
     */
    private $generatorFinder = null;

    /**
     * Find all generator allow in project
     * @param FileManager $fileManager
     * @param ClassAwake $classAwake
     */
    public function __construct(FileManager $fileManager, Yaml $yaml, PhpStringParser $phpStringParser, ViewFileStategy $viewFile, GeneratorFinder $generatorFinder)
    {
        $this->fileManager     = $fileManager;
        $this->yaml            = $yaml;
        $this->phpStringParser = $phpStringParser;
        $this->viewFile        = $viewFile;
        $this->generatorFinder = $generatorFinder;
    }

    /**
     * @param Generator $generator
     * @param MetaData $metadata
     * @param array $questions
     * @throws \InvalidArgumentException
     * @return Generator
     */
    public function init(Generator $generator, MetaData $metadata, array $questions)
    {
        $generator = clone $generator;
        $parser    = clone $this->phpStringParser;
        $process   = Yaml::parse(file_get_contents($generator->getName()), true);

        $generator = $this->analyseDependencies($process, $parser, $generator, $questions, $metadata);

        $dto = new $process['dto']();
        $dto->setMetadata($metadata);
        $dto = $this->analyseQuestionsReponse($questions, $dto);
        $generator->setDTO($dto);

        $parser->addVariable(lcfirst($process['name']), $generator->getDTO());
        $generator->addTemplateVariable(lcfirst($process['name']), $generator->getDTO());

        $generator = $this->analyseQuestions($process, $parser, $generator, $questions);
        $generator = $this->analyseTemplateVariables($process, $parser, $generator);
        $generator = $this->analyseDirectories($process, $parser, $generator);
        $generator = $this->analyseFileList($process, $parser, $generator, dirname($generator->getName()) . '/Skeleton/');

        return $generator;
    }

    /**
     * @param Generator $generator
     * @param string $fileName
     * @throws \Exception
     * @return string
     */
    public function viewFile(Generator $generator, $fileName, $skeletonPath)
    {
    	$files = $generator->getFiles();
        if(!isset($files[$fileName])) {
            throw new \Exception(sprintf('File "%s" does not exist', $fileName));
        }

        return $this->viewFile->generateFile(
            $generator->getDTO(),
            $skeletonPath,
            $files[$fileName]['name'],
            '',
            $generator->getTemplateVariables()
        );
    }


    /**
     * @param array $process
     * @param PhpStringParser $parser
     * @param Generator $generator
     * @param array $questions
     * @return Generator
     */
    private function analyseDependencies(array $process, PhpStringParser $parser, Generator $generator, array $questions, $metadata)
    {
        if(!isset($process['dependencies'])) {
            return $generator;
        }

        foreach ($process['dependencies'] as $dependencie) {
        	$generatorFile = $this->generatorFinder->findByName($dependencie);
            $dependenciesProcess = Yaml::parse(file_get_contents($generatorFile), true);

            $dto = new $dependenciesProcess['dto']();
            $dto->setMetadata($metadata);
            $dto = $this->analyseQuestionsReponse($questions, $dto);
            $generator->setDTO($dto);
            $parser->addVariable(lcfirst($dependenciesProcess['name']), $generator->getDTO());
            $generator->addTemplateVariable(lcfirst($dependenciesProcess['name']), $generator->getDTO());

            $generator = $this->analyseDependencies($dependenciesProcess, $parser, $generator, $questions, $metadata);
            $generator = $this->analyseQuestions($dependenciesProcess, $parser, $generator, $questions);
            $generator = $this->analyseTemplateVariables($dependenciesProcess, $parser, $generator);
            $generator = $this->analyseDirectories($dependenciesProcess, $parser, $generator);
            $generator = $this->analyseFileList($dependenciesProcess, $parser, $generator, dirname($generatorFile) . '/Skeleton/');
        }

        return $generator;
    }

    /**
     * @param array $questions
     * @param unknown $dto
     * @return Ambiguous
     */
    private function analyseQuestionsReponse(array $questions, $dto)
    {
        foreach ($questions as $questionName => $questionReponse) {
        	if (is_array($questionReponse)) {
        		foreach ($questionReponse as $firstArgument => $secondArgument) {
	        		if (method_exists($dto, $questionName)) {
	        			$dto->$questionName($firstArgument, $secondArgument);
	        		}
        		}
        	} else {
	            if (method_exists($dto, $questionName)) {
	                $dto->$questionName($questionReponse);
	            }
        	}
        }

        return $dto;
    }

    /**
     * @param array $process
     * @param PhpStringParser $parser
     * @param Generator $generator
     * @return Generator
     */
    private function analyseQuestions(array $process, PhpStringParser $parser, Generator $generator, array $questions)
    {
        foreach ($process['questions'] as $question) {
            if (isset($question['type']) && $question['type'] === self::COMPLEX_QUESTION) {
                $complex = $question['factory']::getInstance();
                $generator = $complex->ask($generator);
            } else {
                $generator->addQuestion(
                    array(
                        'dtoAttribute'    => 'set' . ucfirst($question['dtoAttribute']),
                        'text'            => $question['text'],
                    	'value'           => (isset($questions['set' . ucfirst($question['dtoAttribute'])])) ? $questions['set' . ucfirst($question['dtoAttribute'])] : '',
                        'defaultResponse' => (isset($question['defaultResponse']) && $parser->issetVariable($question['defaultResponse'])) ? $parser->parse($question['defaultResponse']) : ''
                    )
                );
            }
        }

        return $generator;
    }
    /**
     * @param array $process
     * @param PhpStringParser $parser
     * @param Generator $generator
     * @return Generator
     */
    private function analyseTemplateVariables(array $process, PhpStringParser $parser, Generator $generator)
    {
        $templateVariable = array();

        foreach ($process['templateVariables'] as $variables) {
            foreach ($variables as $varName => $value) {
                //var_dump($varName);
                if($varName === 'environnementCondition') {
                    foreach ($value as $environements) {
                        foreach ($environements as $environment => $environmentVariables) {
                            foreach ($environmentVariables as $environmentVariable => $environmentVariablesValue) {
                                if($environment === 'zf2') {
                                    try {
                                        \CrudGenerator\EnvironnementResolver\ZendFramework2Environnement::getDependence($this->fileManager);
                                        $variableValue = $parser->parse($environmentVariablesValue);
                                        $parser->addVariable($environmentVariable, $variableValue);
                                        $generator->addTemplateVariable($environmentVariable, $variableValue);
                                    } catch (\CrudGenerator\EnvironnementResolver\EnvironnementResolverException $e) {
                                    }
                                } elseif($environment === 'else') {
                                    $variableValue = $parser->parse($environmentVariablesValue);
                                    $parser->addVariable($environmentVariable, $variableValue);
                                    $generator->addTemplateVariable($environmentVariable, $variableValue);
                                }
                            }
                        }
                    }
                } else {
                    $variableValue = $parser->parse($value);
                    $parser->addVariable($varName, $variableValue);
                    $generator->addTemplateVariable($varName, $variableValue);
                }
            }
        }

        return $generator;
    }

    /**
     * @param array $process
     * @param PhpStringParser $parser
     * @param Generator $generator
     * @return Generator
     */
    private function analyseDirectories(array $process, PhpStringParser $parser, Generator $generator)
    {
        foreach ($process['directories'] as $directory) {
            $generator->addDirectories($directory, $parser->parse($directory));
        }

        return $generator;
    }

    /**
     * @param array $process
     * @param PhpStringParser $parser
     * @param Generator $generator
     * @return Generator
     */
    private function analyseFileList(array $process, PhpStringParser $parser, Generator $generator, $skeletonPath)
    {
        foreach ($process['filesList'] as $files) {
            foreach ($files as $templateName => $tragetFile) {
                if($templateName === 'environnementCondition') {
                    foreach ($tragetFile as $environements) {
                        foreach ($environements as $environment => $environmentTemplates) {
                            foreach ($environmentTemplates as $environmentTemplateName => $environmentTragetFile) {
                                if($environment === 'zf2') {
                                    try {
                                        \CrudGenerator\EnvironnementResolver\ZendFramework2Environnement::getDependence(new \CrudGenerator\Utils\FileManager());
                                        $generator->addFile($skeletonPath, $environmentTemplateName, $parser->parse($environmentTragetFile));
                                    } catch (\CrudGenerator\EnvironnementResolver\EnvironnementResolverException $e) {
                                    }
                                } elseif($environment === 'else') {
                                    $generator->addFile($skeletonPath, $environmentTemplateName, $parser->parse($environmentTragetFile));
                                }
                            }
                        }
                    }
                } else {
                    $generator->addFile($skeletonPath, $templateName, $parser->parse($tragetFile));
                }
            }
        }

        return $generator;
    }
}
