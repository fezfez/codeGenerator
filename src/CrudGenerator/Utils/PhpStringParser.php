<?php
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
     * @param array $variables
     */
    public function __construct(\Twig_Environment $twig, array $variables = array())
    {
        $this->twig      = $twig;
        $this->variables = $variables;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return PhpStringParser
     */
    public function addVariable($name, $value)
    {
        $this->variables[$name] = $value;
        return $this;
    }

    /**
     * @param string $name
     * @return boolean
     */
    public function issetVariable($name)
    {
        return isset($this->variables[$name]);
    }

    /**
     * @param string $string
     * @return string
     */
    public function parse($string)
    {
        return $this->twig->render(
            $string,
            $this->variables
        );
    }
}
