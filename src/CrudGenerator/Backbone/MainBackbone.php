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

use CrudGenerator\Context\ContextInterface;

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
            function() {
                $this->historyBackbone->run();
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
            function() {
                $generator = $this->preapreForGenerationBackbone->run();
                $this->context->publishGenerator($generator);

                if (true === $this->context->confirm('Preview a file ?', 'view_file')) {
                    $this->generateFileBackbone->run($generator);
                }
                if (true === $this->context->confirm('Generate file(s) ?', 'generate_files')) {
                    $this->generateBackbone->run($generator);
                }
            }
        );
    }
}
