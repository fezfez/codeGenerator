<?php
namespace CrudGenerator\Utils;

class PhpStringParser
{
    /**
     * @var array
     */
    private $variables = array();

    /**
     * @param array $variables
     */
    public function __construct($variables = array())
    {
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
        return preg_replace_callback(
            '/(\<\?=|\<\?php=|\<\?php)(.*?)\?\>/',
            array(
                &$this,
                'evalBlock'
            ),
            $string
        );
    }

    /**
     * @param mixed $matches
     * @return string
     */
    private function evalBlock($matches)
    {
        if (is_array($this->variables) && count($this->variables)) {
            foreach ($this->variables as $var_name => $var_value) {
                $$var_name = $var_value;
            }
        }

        $evalEnd = '';
        if ($matches[2][count($matches[2] - 1)] !== ';') {
            $evalEnd = ';';
        }

        $returnBlock = '';
        eval('$returnBlock = ' . $matches[2] . $evalEnd);

        return $returnBlock;
    }
}
