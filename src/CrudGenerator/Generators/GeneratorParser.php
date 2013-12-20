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

/**
 * Find all generator allow in project
 *
 * @author Stéphane Demonchaux
 */
class GeneratorParser
{
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
     * Find all generator allow in project
     * @param FileManager $fileManager
     * @param ClassAwake $classAwake
     */
    public function __construct(FileManager $fileManager, Yaml $yaml, PhpStringParser $phpStringParser, ViewFileStategy $viewFile)
    {
        $this->fileManager     = $fileManager;
        $this->yaml            = $yaml;
        $this->phpStringParser = $phpStringParser;
        $this->viewFile        = $viewFile;
    }

    /**
     * @param Generator $generator
     * @return Generator
     */
    public function analyse(Generator $generator)
    {
        $generator = clone $generator;
        $parser    = clone $this->phpStringParser;
        $process   = Yaml::parse(file_get_contents($generator->getName()), true);

        $dto = new $process['dto']();
        $generator->setDTO($dto);


        $parser->addVariable('dto', $dto);//->addVariable('metadataName', 'sample');
        //$parser = new \CrudGenerator\Utils\PhpStringParser(array('metadataName' => 'toto', 'dto' => ));

        // question
        $generator = $this->analyseTemplateVariables($process, $parser, $generator);
        $generator = $this->analyseDirectories($process, $parser, $generator);
        $generator = $this->analyseFileList($process, $parser, $generator);

        return $generator;
    }

    /**
     * @param Generator $generator
     * @param string $fileName
     * @throws \Exception
     * @return string
     */
    public function viewFile(Generator $generator, $fileName)
    {
		if(!in_array($fileName, array_flip($generator->getFiles()))) {
			throw new \Exception('File does not exist');
		}

    	return $this->viewFile->generateFile(
    		$generator->getDTO(),
    		dirname($generator->getName()) . '/Skeleton',
    		$fileName,
    		'',
    		$generator->getTemplateVariables()
		);
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
    private function analyseFileList(array $process, PhpStringParser $parser, Generator $generator)
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
                                        $generator->addFile($environmentTemplateName, $parser->parse($environmentTragetFile));
                                    } catch (\CrudGenerator\EnvironnementResolver\EnvironnementResolverException $e) {
                                    }
                                } elseif($environment === 'else') {
                                    $generator->addFile($environmentTemplateName, $parser->parse($environmentTragetFile));
                                }
                            }
                        }
                    }
                } else {
                    $generator->addFile($templateName, $parser->parse($tragetFile));
                }
            }
        }

        return $generator;
    }
}
