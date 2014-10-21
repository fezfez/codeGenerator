<?php
/**
  * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
     * @return array
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
