<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
     * @param MetadataQuestion                 $metadataQuestion
     * @param GeneratorQuestion                $generatorQuestion
     * @param GeneratorParserInterface         $generatorParser
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
