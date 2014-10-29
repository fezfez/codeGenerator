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

use CrudGenerator\Context\ContextInterface;
use CrudGenerator\Generators\Parser\GeneratorParserFactory;

class MainBackbone
{
    /**
     * @var HistoryBackbone
     */
    private $historyBackbone = null;
    /**
     * @var SearchGeneratorBackbone
     */
    private $searchGeneratorBackbone = null;
    /**
     * @var PreapreForGenerationBackbone
     */
    private $preapreForGenerationBackbone = null;
    /**
     * @var GenerateFileBackbone
     */
    private $generateFileBackbone = null;
    /**
     * @var GenerateBackbone
     */
    private $generateBackbone = null;
    /**
     * @var CreateSourceBackbone
     */
    private $createSourceBackbone = null;
    /**
     * @var ContextInterface
     */
    private $context = null;

    /**
     * @param HistoryBackbone $historyBackbone
     * @param SearchGeneratorBackbone $searchGeneratorBackbone
     * @param PreapreForGenerationBackbone $preapreForGenerationBackbone
     * @param GenerateFileBackbone $generateFileBackbone
     * @param GenerateBackbone $generateBackbone
     * @param CreateSourceBackbone $createSourceBackbone
     * @param ContextInterface $context
     */
    public function __construct(
        HistoryBackbone $historyBackbone,
        SearchGeneratorBackbone $searchGeneratorBackbone,
        PreapreForGenerationBackbone $preapreForGenerationBackbone,
        GenerateFileBackbone $generateFileBackbone,
        GenerateBackbone $generateBackbone,
        CreateSourceBackbone $createSourceBackbone,
        ContextInterface $context
    ) {
        $this->historyBackbone              = $historyBackbone;
        $this->searchGeneratorBackbone      = $searchGeneratorBackbone;
        $this->preapreForGenerationBackbone = $preapreForGenerationBackbone;
        $this->generateFileBackbone         = $generateFileBackbone;
        $this->generateBackbone             = $generateBackbone;
        $this->createSourceBackbone         = $createSourceBackbone;
        $this->context                      = $context;
    }

    public function run()
    {
        $generate = function($generator) {
            if (true === $this->context->confirm('Preview a file ?', 'view_file')) {
                $this->generateFileBackbone->run($generator);
            }
            if (true === $this->context->confirm('Generate file(s) ?', 'generate_files')) {
                $this->generateBackbone->run($generator);
            }
        };

        $this->context->menu(
            'Create new metadataSource',
            'create_metadatasource',
            function() {
                $this->createSourceBackbone->run();
            }
        );

        $this->context->menu(
            'Wake history',
            'select_history',
            function() use ($generate) {
                $generator = $this->historyBackbone->run();
                $parser = GeneratorParserFactory::getInstance($this->context);
                $generator = $parser->init($generator, $generator->getDto()->getMetadata());
                $generate($generator);
            }
        );

        $this->context->menu(
            'Search generator',
            'search_generator',
            function() {
                $this->searchGeneratorBackbone->run();
            }
        );

        $this->context->menu(
            'Generate',
            'generate',
            function() use ($generate) {
                $generator = $this->preapreForGenerationBackbone->run();
                $this->context->publishGenerator($generator);

                $generate($generator);
            }
        );
    }
}
