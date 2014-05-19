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
namespace CrudGenerator\GeneratorsEmbed\ArchitectGenerator;

use CrudGenerator\Generators\GeneratorDataObject;
use CrudGenerator\Context\ContextInterface;

class MetadataToArray
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

    /**
     * @param Architect $DTO
     * @return Architect
     */
    public function ask(GeneratorDataObject $generator)
    {
        foreach ($generator->getDTO()->getMetadata()->getColumnCollection() as $column) {
            $response = $this->context->ask(
                'Attribute name for "' . $column->getName() . '"',
                'metadataToArray' . $column->getName(),
                (null === $generator->getDTO()->getAttributeName($column->getName())) ? $column->getName() : $generator->getDTO()->getAttributeName($column->getName()),
                true
            );

            if ($response !== null) {
                $generator->getDTO()->setAttributeName($column->getName(), $response);
            }
        }

        return $generator;
    }
}