<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Generators\Parser\Lexical;

use Garoevans\PhpEnum\Enum;

class QuestionResponseTypeEnum extends Enum
{
    /**
     * @var string
     */
    const __default = self::TEXT;
    /**
     * @var string
     */
    const TEXT = 'text';
    /**
     * @var string
     */
    const BOOLEAN = 'boolean';
    /**
     * @var string
     */
    const COLLECTION_BOOLEAN = 'collectionBoolean';
    /**
     * @var string
     */
    const COLLECTION_TEXT = 'collectionText';
}
