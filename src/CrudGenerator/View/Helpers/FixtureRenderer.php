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
        if (in_array($metadata->getType(), array('integer', 'float')) === true) {
            $data = $this->faker->randomNumber();
        } elseif (in_array($metadata->getType(), array('string', 'text')) === true) {
            $data = ($metadata->getLength() === null) ?
                        '"' . $this->faker->text(50) . '"' :
                        '"' . ($metadata->getLength() <=  5) ?
                            '"test"' : $this->faker->text($metadata->getLength()) . '"';
        } elseif ($metadata->getType() === 'date') {
            $data = 'new DateTime()';
        } elseif (in_array($metadata->getType(), array('bool', 'boolean')) === true) {
            $data = 'true';
        }

        return $data;
    }
}
