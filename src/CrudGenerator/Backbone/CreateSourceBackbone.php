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
use CrudGenerator\Generators\Questions\Web\MetaDataSourcesQuestion;
use CrudGenerator\MetaData\Config\MetaDataConfigDAO;
use CrudGenerator\MetaData\Config\ConfigException;

class CreateSourceBackbone
{
    /**
     * @var MetaDataSourcesQuestion
     */
    private $metaDataSourcesQuestion = null;
    /**
     * @var MetaDataConfigDAO
     */
    private $metaDataConfigDAO = null;
    /**
     * @var ContextInterface
     */
    private $context = null;

    /**
     * @param MetaDataSourcesQuestion $metaDataSourcesQuestion
     * @param MetaDataConfigDAO $metaDataConfigDAO
     * @param ContextInterface $context
     */
    public function __construct(MetaDataSourcesQuestion $metaDataSourcesQuestion, MetaDataConfigDAO $metaDataConfigDAO, ContextInterface $context)
    {
        $this->metaDataSourcesQuestion = $metaDataSourcesQuestion;
        $this->metaDataConfigDAO       = $metaDataConfigDAO;
        $this->context                 = $context;
    }

    public function run()
    {
        $source = $this->metaDataSourcesQuestion->ask()->getConfig();
        try {
            $this->metaDataConfigDAO->save($source);
        } catch (ConfigException $e) {
            $this->context->log($e->getMessage(), 'error');
        }
    }
}
