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

use CrudGenerator\Generators\Questions\Web\MetaDataSourcesConfiguredQuestion;
use CrudGenerator\Generators\Questions\Web\MetaDataQuestion;
use CrudGenerator\Generators\Questions\Web\GeneratorQuestion;
use CrudGenerator\Generators\Parser\GeneratorParser;
use CrudGenerator\Generators\GeneratorDataObject;

class PreapreForGenerationBackbone
{
    /**
     * @var MetaDataSourcesConfiguredQuestion
     */
    private $metaDataSourcesConfiguredQuestion = null;
    /**
     * @var MetaDataQuestion
     */
    private $metaDataQuestion = null;
    /**
     * @var GeneratorQuestion
     */
    private $generatorQuestion = null;
    /**
     * @var GeneratorParser
     */
    private $generatorParser = null;

    /**
     * @param MetaDataSourcesConfiguredQuestion $metaDataSourcesConfiguredQuestion
     * @param MetaDataQuestion $metaDataQuestion
     * @param GeneratorQuestion $generatorQuestion
     * @param GeneratorParser $generatorParser
     */
    public function __construct(
        MetaDataSourcesConfiguredQuestion $metaDataSourcesConfiguredQuestion,
        MetaDataQuestion $metaDataQuestion,
        GeneratorQuestion $generatorQuestion,
        GeneratorParser $generatorParser
    ) {
        $this->metaDataQuestion = $metaDataQuestion;
        $this->generatorParser = $generatorParser;
        $this->generatorQuestion = $generatorQuestion;
        $this->metaDataSourcesConfiguredQuestion = $metaDataSourcesConfiguredQuestion;
    }

    /**
     * @throws \InvalidArgumentException
     * @return CrudGenerator\Generators\GeneratorDataObject
     */
    public function run()
    {
        $metadataSource = $this->metaDataSourcesConfiguredQuestion->ask();
        $metadata       = $this->metaDataQuestion->ask($metadataSource);
        $generatorPath  = $this->generatorQuestion->ask();

        $generator = new GeneratorDataObject();
        $generator->setName($generatorPath);

        return $this->generatorParser->init($generator, $metadata);
    }
}
