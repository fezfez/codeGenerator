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
namespace CrudGenerator\Generators\Validator;

use JsonSchema\Validator;
use CrudGenerator\MetaData\DataObject\MetaDataInterface;

/**
 *
 * @author Stéphane Demonchaux
 */
class GeneratorValidator
{
    /**
     * @var mixed
     */
    private $schema = null;
    /**
     * @var Validator
     */
    private $validator = null;

    /**
     * @param mixed $schema
     * @param Validator $validator
     */
    public function __construct($schema, Validator $validator)
    {
        $this->schema    = $schema;
        $this->validator = $validator;
    }

    /**
     * @param array $data
     * @param MetaDataInterface $metadata
     * @throws \InvalidArgumentException
     */
    public function isValid(array $data, MetaDataInterface $metadata = null)
    {
        $this->validator->reset();
        $this->validator->check(json_decode(json_encode($data), false), $this->schema);

        if ($this->validator->isValid() === false) {
            throw new \InvalidArgumentException('The schema is not valid');
        }

        if ($metadata !== null) {
            $metadataAllowed = false;
            foreach ($data['metadataTypeAccepted'] as $metadataType) {
                if (true === is_a($metadata, $metadataType)) {
                    $metadataAllowed = true;
                }
            }

            if (false === $metadataAllowed) {
                throw new \InvalidArgumentException(
                    sprintf('The metadata of type "%s" are not allowed in this generator ', get_class($metadata))
                );
            }
        }
    }

    /**
     * @return multitype:
     */
    public function getErrors()
    {
        return $this->validator->getErrors();
    }

    public function reset()
    {
        $this->validator->reset();
    }
}
