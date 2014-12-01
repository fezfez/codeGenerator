<?php
/**
 * This file is part of the Code Generator package.
 *
 * (c) Stéphane Demonchaux <demonchaux.stephane@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CrudGenerator\Utils;

class PhpStringParser
{
    /**
     * @var \Twig_Environment
     */
    private $twig = array();
    /**
     * @var array
     */
    private $variables = array();

    /**
     * @param \Twig_Environment $twig
     * @param array             $variables
     */
    public function __construct(\Twig_Environment $twig, array $variables = array())
    {
        $this->twig      = $twig;
        $this->variables = $variables;
    }

    /**
     * @param  string          $name
     * @param  mixed           $value
     * @return PhpStringParser
     */
    public function addVariable($name, $value)
    {
        $this->variables[$name] = $value;

        return $this;
    }

    /**
     * @param  string  $name
     * @return boolean
     */
    public function issetVariable($name)
    {
        return isset($this->variables[$name]);
    }

    /**
     * @param  string $string
     * @return string
     */
    public function parse($string)
    {
        return $this->twig->render(
            $string,
            $this->variables
        );
    }

    /**
     * Interpret a php plain text
     *
     * @param  string                    $text A php call as plain text example : $foo->bar()->baz()
     * @throws \InvalidArgumentException
     * @return mixed
     */
    public function staticPhp($text)
    {
        $textExplode      = explode('->', $text);
        $variableName     = str_replace('$', '', array_shift($textExplode));
        $variableVariable = $this->variables;

        if (isset($variableVariable[$variableName]) === false) {
            throw new \InvalidArgumentException(
                sprintf('variable %s does not exist', $variableName)
            );
        }

        $textExplode = array_map(
            function ($value) {
                return str_replace('()', '', $value);
            },
            $textExplode
        );

        $instance = $variableVariable[$variableName];
        $keys     = array_values($textExplode);
        $lastKey  = array_pop($keys);

        foreach ($textExplode as $key => $method) {
            if ($instance === null && $lastKey !== $key) {
                throw new \InvalidArgumentException(sprintf('method %s return null', $method));
            } elseif (false === method_exists($instance, $method)) {
                throw new \InvalidArgumentException(sprintf('method %s does not exist on %s', $method, $text));
            } else {
                $instance = $instance->$method();
            }
        }

        return $instance;
    }
}
