<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\View\Helpers;

use CrudGenerator\Metadata\DataObject\MetaDataColumn;
use Faker\Generator;

class FixtureRenderer
{
    /**
     * @var Generator
     */
    private $faker = null;

    /**
     * @param Generator $faker
     */
    public function __construct(Generator $faker)
    {
        $this->faker = $faker;
    }

    /**
     * @param  MetaDataColumn $metadata
     * @return string
     */
    public function render(MetaDataColumn $metadata)
    {
        $data = '';

        if ($this->isNumber($metadata->getType()) === true) {
            $data = $this->faker->randomNumber();
        } elseif ($this->isString($metadata->getType()) === true) {
            $data = $this->generateString($metadata);
        } elseif ($this->isDate($metadata->getType()) === true) {
            $data = 'new DateTime()';
        } elseif ($this->isBool($metadata->getType()) === true) {
            $data = 'true';
        }

        return $data;
    }

    /**
     * @param MetaDataColumn $metadata
     * @return string
     */
    private function generateString(MetaDataColumn $metadata)
    {
        if ($metadata->getLength() === null) { ?
            return '"' . $this->faker->text(50) . '"';
        } elseif($metadata->getLength() <=  5) {
            return 'test';
        } else {
            return $this->faker->text($metadata->getLength()) . '"';
        }
    }

    /**
     * @param string $type
     * @return boolean
     */
    private function isString($type)
    {
        return in_array($type, array('string', 'text'));
    }

    /**
     * @param string $type
     * @return boolean
     */
    private function isNumber($type)
    {
        return in_array($type, array('integer', 'float'));
    }

    /**
     * @param string $type
     * @return boolean
     */
    private function isBool($type)
    {
        return in_array($type, array('bool', 'boolean'));
    }

    /**
     * @param string $type
     * @return boolean
     */
    private function isDate($type)
    {
        return $type === 'date';
    }
}
