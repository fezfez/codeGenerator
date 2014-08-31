<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CrudGenerator\Utils;

use Symfony\Component\Console\Output\Output;

use CrudGenerator\Context\ContextInterface;

/**
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class OutputWeb extends Output
{
	private $context = null;

	public function __construct(ContextInterface $context)
	{
		$this->context = $context;
		parent::__construct();
	}
    /**
     * @var string
     */
    private $buffer = '';

    /**
     * {@inheritdoc}
     */
    protected function doWrite($message, $newline)
    {
        $this->context->log($message);
    }
}
