<?php
/**
  * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace CrudGenerator\Generators\Strategies;

use CrudGenerator\View\View;
use CrudGenerator\Context\ContextInterface;

/**
 * Base code generator, extends it and implement doGenerate method
 * to make you own Generator
 *
 * @author Stéphane Demonchaux
 */
class SandBoxStrategy implements StrategyInterface
{
    /**
     * @var View View manager
     */
    protected $view = null;
    /**
     * @var ContextInterface Output
     */
    protected $context = null;

    /**
     * Base code generator
     * @param View             $view
     * @param ContextInterface $context
     */
    public function __construct(
        View $view,
        ContextInterface $context
    ) {
        $this->view    = $view;
        $this->context = $context;
    }

    /* (non-PHPdoc)
     * @see CrudGenerator\Generators\Strategies.StrategyInterface::generateFile()
     */
    public function generateFile(array $datas, $skeletonDir, $pathTemplate, $pathTo)
    {
        $continue = true;
        while ($continue) {
            $results = $this->view->render(
                $skeletonDir,
                $pathTemplate,
                $datas
            );

            $this->context->log("<info>[LOG] Generate $pathTo \nfrom $skeletonDir$pathTemplate</info>");
            $this->context->log($results);

            $continue = $this->context->confirm(
                "\n<question>Regenerate ?</question> ",
                'sanbox_regenerate'
            );
        }
    }
}
