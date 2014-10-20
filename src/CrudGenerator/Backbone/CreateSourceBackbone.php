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
use CrudGenerator\Generators\Questions\MetadataSource\MetadataSourceQuestion;
use CrudGenerator\MetaData\Config\MetaDataConfigDAO;
use CrudGenerator\MetaData\Config\ConfigException;
use CrudGenerator\Generators\ResponseExpectedException;

class CreateSourceBackbone
{
    /**
     * @var MetadataSourceQuestion
     */
    private $metadataSourceQuestion = null;
    /**
     * @var MetaDataConfigDAO
     */
    private $metadataConfigDAO = null;
    /**
     * @var ContextInterface
     */
    private $context = null;

    /**
     * Constructor.
     *
     * @param MetadataSourceQuestion $metadataSourceQuestion
     * @param MetaDataConfigDAO $metaDataConfigDAO
     * @param ContextInterface $context
     */
    public function __construct(
        MetadataSourceQuestion $metadataSourceQuestion,
        MetaDataConfigDAO $metaDataConfigDAO,
        ContextInterface $context
    ) {
        $this->metadataSourceQuestion = $metadataSourceQuestion;
        $this->metadataConfigDAO      = $metaDataConfigDAO;
        $this->context                = $context;
    }

    /**
     * @throws ResponseExpectedException
     */
    public function run()
    {
        $source = $this->metadataSourceQuestion->ask();
        try {
            $this->metadataConfigDAO->save($source);
            $this->context->log('New source created', 'valid');
        } catch (ConfigException $e) {
            $this->context->log($e->getMessage(), 'error');
            throw new ResponseExpectedException("Response expected");
        }
    }
}
