<?php
namespace CrudGenerator\Tests\General\Generators\CrudGenerator;

use Symfony\Component\Yaml\Yaml;

class YamlTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $process = Yaml::parse(file_get_contents('./src/CrudGenerator/Generators/ArchitectGenerator/ArchitectGenerator.yaml'), true);

        $dto = new $process['dto']();

        $parser = new \CrudGenerator\Utils\PhpStringParser(array('metadataName' => 'toto', 'dto' => $dto));

        echo $process['name'] . "\n";
        echo $process['definition'] . "\n";
        echo 'dto : ' . $process['dto'] . "\n";

        /*foreach ($process['questions'] as $question) {
            if (isset($question['type']) && $question['type'] === 'complex') {
                $question['factory']::getInstance();
            } else {
                echo 'Question : ' . $question['text'] . "\n";
                echo "Default response : " . $parser->parse($question['defaultResponse']) . "\n";
                $dtoAttribute = 'set' . ucfirst($question['dtoAttribute']) . "\n";
                echo 'method : ' . $dtoAttribute . "\n";
                //$dto->$dtoAttribute($parser->parse($question['defaultResponse']));
            }
        }*/

        $templateVariable = array();
        foreach ($process['templateVariables'] as $variables) {
            foreach ($variables as $varName => $value) {
                if($varName === 'environnementCondition') {
                    foreach ($value as $environements) {
                        foreach ($environements as $environment => $environmentVariables) {
                            foreach ($environmentVariables as $environmentVariable => $environmentVariablesValue) {
                                if($environment === 'zf2') {
                                    try {
                                        \CrudGenerator\EnvironnementResolver\ZendFramework2Environnement::getDependence(new \CrudGenerator\Utils\FileManager());
	                                  	$variableValue = $parser->parse($environmentVariablesValue);
	                                	echo "ELSE";
	                                	echo "$environmentVariable => $variableValue \n";
	                                	$parser->addVariable($environmentVariable, $variableValue);

	                                	$templateVariable[$environmentVariable] = $variableValue;
                                    } catch (\CrudGenerator\EnvironnementResolver\EnvironnementResolverException $e) {
                                    }
                                } elseif($environment === 'else') {
                                	$variableValue = $parser->parse($environmentVariablesValue);
                                	echo "ELSE";
                                	echo "$environmentVariable => $variableValue \n";
                                	$parser->addVariable($environmentVariable, $variableValue);

                                	$templateVariable[$environmentVariable] = $variableValue;
                                }
                            }
                        }
                    }
                } else {
	                $variableValue = $parser->parse($value);
	                echo "$varName => $variableValue \n";
	                $parser->addVariable($varName, $variableValue);

	                $templateVariable[$varName] = $variableValue;
                }
            }
        }

        foreach ($process['directories'] as $directory) {
            echo 'create directory : "' . $parser->parse($directory) . '"' . "\n";
        }

        echo "FileList\n\n";
        foreach ($process['filesList'] as $files) {
        	foreach ($files as $templateName => $tragetFile) {
        		if($templateName === 'environnementCondition') {
        			foreach ($tragetFile as $environements) {
        				foreach ($environements as $environment => $environmentTemplates) {
        					foreach ($environmentTemplates as $environmentTemplateName => $environmentTragetFile) {
        						if($environment === 'zf2') {
        							try {
        								\CrudGenerator\EnvironnementResolver\ZendFramework2Environnement::getDependence(new \CrudGenerator\Utils\FileManager());
        								$environmentTragetFileValue = $parser->parse($environmentTragetFile);
        								echo "ZF2";
        								echo "$environmentTemplateName => $environmentTragetFileValue \n";
        							} catch (\CrudGenerator\EnvironnementResolver\EnvironnementResolverException $e) {
        							}
        						} elseif($environment === 'else') {
        								$environmentTragetFileValue = $parser->parse($environmentTragetFile);
        								echo "ELSE";
        								echo "$environmentTemplateName => $environmentTragetFileValue \n";
        						}
        					}
        				}
        			}
        		} else {
	        		$tragetFileValue = $parser->parse($tragetFile);
	        		echo "$templateName => $tragetFileValue \n";
        		}
        	}
        }

        var_dump($dto);
        exit;

    }
}
