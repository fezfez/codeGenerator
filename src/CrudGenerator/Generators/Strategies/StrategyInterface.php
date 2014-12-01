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

/**
 * Base code generator, extends it and implement doGenerate method
 * to make you own Generator
 *
 * @author Stéphane Demonchaux
 */
interface StrategyInterface
{
    /**
     * @param  array       $datas
     * @param  string      $skeletonDir
     * @param  string      $pathTemplate
     * @param  string      $pathTo
     * @return string|null
     */
    public function generateFile(array $datas, $skeletonDir, $pathTemplate, $pathTo);
}
