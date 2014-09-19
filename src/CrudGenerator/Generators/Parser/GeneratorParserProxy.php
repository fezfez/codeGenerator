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
namespace CrudGenerator\Generators\Parser;

use CrudGenerator\Context\ContextInterface;
use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\MetaData\DataObject\MetaDataInterface;

/**
 * Find all generator allow in project
 *
 * @author Stéphane Demonchaux
 */
class GeneratorParserProxy implements GeneratorParserInterface
{
    /**
     * @var ContextInterface
     */
    private $context = null;

    /**
     * @param ContextInterface $context
     */
    public function __construct(ContextInterface $context)
    {
        $this->context = $context;
    }

    /* (non-PHPdoc)
     * @see \CrudGenerator\Generators\Parser\GeneratorParserInterface::init()
     */
    public function init(GeneratorDataObject $generator, MetaDataInterface $metadata)
    {
        $generatorParser = GeneratorParserFactory::getInstance($this->context);

        return $generatorParser->init($generator, $metadata);
    }
}
