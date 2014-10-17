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

use CrudGenerator\Generators\Questions\MetadataSourceConfigured\MetadataSourceConfiguredQuestion;
use CrudGenerator\Generators\Questions\Metadata\MetadataQuestion;
use CrudGenerator\Generators\Questions\Generator\GeneratorQuestion;
use CrudGenerator\Generators\Parser\GeneratorParserInterface;
use CrudGenerator\Generators\GeneratorDataObject;

class PreapreForGenerationBackbone
{
    /**
     * @var MetadataSourceConfiguredQuestion
     */
    private $metadataSourceConfiguredQuestion = null;
    /**
     * @var MetadataQuestion
     */
    private $metadataQuestion = null;
    /**
     * @var GeneratorQuestion
     */
    private $generatorQuestion = null;
    /**
     * @var GeneratorParserInterface
     */
    private $generatorParser = null;

    /**
     * @param MetadataSourceConfiguredQuestion $metadataSourceConfiguredQuestion
     * @param MetadataQuestion $metadataQuestion
     * @param GeneratorQuestion $generatorQuestion
     * @param GeneratorParserInterface $generatorParser
     */
    public function __construct(
        MetadataSourceConfiguredQuestion $metadataSourceConfiguredQuestion,
        MetadataQuestion $metadataQuestion,
        GeneratorQuestion $generatorQuestion,
        GeneratorParserInterface $generatorParser
    ) {
        $this->metadataQuestion                 = $metadataQuestion;
        $this->generatorParser                  = $generatorParser;
        $this->generatorQuestion                = $generatorQuestion;
        $this->metadataSourceConfiguredQuestion = $metadataSourceConfiguredQuestion;
    }

    /**
     * @throws \InvalidArgumentException
     * @return GeneratorDataObject
     */
    public function run()
    {
        $metadataSource = $this->metadataSourceConfiguredQuestion->ask();
        $metadata       = $this->metadataQuestion->ask($metadataSource);
        $generatorName  = $this->generatorQuestion->ask($metadata);

        $generator = new GeneratorDataObject();
        $generator->setName($generatorName)
                  ->setMetadataSource($metadataSource);

        return $this->generatorParser->init($generator, $metadata);
    }
}
