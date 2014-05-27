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
use CrudGenerator\Generators\ResponseExpectedException;

class MainBackbone
{
    /**
     * @var HistoryBackbone
     */
    private $historyBackbone = null;
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
     * @param MetaDataSourcesConfiguredQuestion $historyQuestion
     * @param PreapreForGenerationBackbone $preapreForGenerationBackbone
     */
    public function __construct(
        HistoryBackbone $historyBackbone,
        PreapreForGenerationBackbone $preapreForGenerationBackbone,
        GenerateFileBackbone $generateFileBackbone,
        GenerateBackbone $generateBackbone,
        CreateSourceBackbone $createSourceBackbone,
        ContextInterface $context
    ) {
        $this->historyBackbone              = $historyBackbone;
        $this->preapreForGenerationBackbone = $preapreForGenerationBackbone;
        $this->generateFileBackbone         = $generateFileBackbone;
        $this->generateBackbone             = $generateBackbone;
        $this->createSourceBackbone         = $createSourceBackbone;
        $this->context                      = $context;
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function run()
    {
        if ($this->context->confirm('Create new metadataSource', 'create_metadatasource')) {
            try {
                $this->createSourceBackbone->run();
                $this->context->log('New source created', 'valid');
            } catch (ResponseExpectedException $e) { // only web throw this exception
                return $this->context;
            }
        }
        if ($this->context->confirm('Wake history', 'select_history')) {
            $this->historyBackbone->run();
        }
        $generator = $this->preapreForGenerationBackbone->run();
        $this->context->log($generator->getFiles(), 'files');
        if ($this->context->confirm('view file', 'view_file')) {
        	$this->generateFileBackbone->run($generator);
        }
        if ($this->context->confirm('Generate file', 'generate_files')) {
            $this->generateBackbone->run($generator);
        }
    }
}
