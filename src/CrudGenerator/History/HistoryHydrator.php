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

use CrudGenerator\DataObject;
use CrudGenerator\History\HistoryCollection;

use Symfony\Component\Yaml\Dumper;
use Symfony\Component\Yaml\Parser;

use CrudGenerator\Command\Questions\MetaDataSourcesQuestion;
use CrudGenerator\Command\Questions\MetaDataQuestion;

/**
 * HistoryManager instance
 *
 * @author Stéphane Demonchaux
 */
class HistoryHydrator
{
    /**
     * @var Dumper
     */
    private $yamlDumper = null;
    /**
     * @var Parser
     */
    private $yamlParser = null;
    /**
     * @var MetaDataSourcesQuestion
     */
    private $metaDataSourceQuestion = null;
    /**
     * @var MetaDataQuestion
     */
    private $metaDataQuestion = null;

    /**
     * @param Dump $yampDump
     * @param Parser $yampParser
     * @param MetaDataSourcesQuestion $metaDataSourceQuestion
     * @param MetaDataQuestion $metaDataQuestion
     */
    public function __construct(
        Dumper $yamlDump,
        Parser $yamlParser,
        MetaDataSourcesQuestion $metaDataSourceQuestion,
        MetaDataQuestion $metaDataQuestion
    ) {
        $this->yamlDumper = $yamlDump;
        $this->yamlParser = $yamlParser;
        $this->metaDataSourceQuestion = $metaDataSourceQuestion;
        $this->metaDataQuestion = $metaDataQuestion;
    }

    /**
     * @param DataObject $dataObject
     */
    public function dtoToYaml(DataObject $dataObject)
    {
        $dumpArray = array(
            'module'         => $dataObject->getModule(),
            'metaDataSource' => $dataObject->getAdapter(),
            'metaData'       => $dataObject->getMetadata()->getOriginalName(),
            'Generators'     => $this->dumpToArray($dataObject)
        );

        return $this->yamlDumper->dump($dumpArray, 50);
    }

    /**
     * @param string $content
     */
    public function yamlToDto($content)
    {
        $arrayRepresentation = $this->yamlParser->parse($content);
        $historyCollection   = new HistoryCollection();

        foreach ($arrayRepresentation['Generators'] as $dtoName => $generatorInformation) {

            $metaDataSource = $this->metaDataSourceQuestion->ask($arrayRepresentation['metaDataSource']);
            $metaData       = $this->metaDataQuestion->ask($metadataSource, $arrayRepresentation['metaData']);

            $dto = new $dtoName();
            $dto->setModule($arrayRepresentation['module'])
                ->setMetadata($metaData)
                ->setEntity($metaData->getName());

            $dto = $this->writeAbstractOptions($dtoName, $dto);

            $historyCollection->append($dto);
        }

        return $historyCollection;
    }

    /**
     * @param string $generatorName
     * @param DataObject $dataObject
     * @return DataObject
     */
    private function writeAbstractOptions($generatorName, DataObject $dataObject)
    {
        $dataObject = clone $dataObject;

        if (isset($packageDatas['Generators'][$generatorName]['options'])) {
            foreach ($packageDatas['Generators'][$generatorName]['options'] as $optionName => $optionValue) {
                $methodName = 'set' . ucfirst($optionName);
                if (method_exists($dataObject, $methodName)) {
                    $dataObject->$methodName($optionValue);
                }
            }
        }

        return $dataObject;
    }

    /**
     * @param DataObject $dataObject
     * @param array $array
     * @return array
     */
    private function dumpToArray($dataObject, array $array = array())
    {
        $class = new \ReflectionClass($dataObject);
        $methods = $class->getMethods();

        foreach ($methods as $method) {
            if ($method->getDeclaringClass()->getName() === get_class($dataObject)) {
                $methodName = $method->getName();
                if (substr($methodName, 0, 3) === 'get') {
                    $result = $dataObject->$methodName();
                    $array[get_class($dataObject)]['options'][substr($methodName, 3)] = $result;
                }
            }
        }

        return $array;
    }
}