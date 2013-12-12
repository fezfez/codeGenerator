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
     * @param string $value
     * @return PhpStringParser
     */
    public function addVariable($name, $value)
    {
    	$this->variables[$name] = $value;
    	return $this;
    }

    /**
     * @param string $string
     * @return mixed
     */
    public function parse($string)
    {
    	return preg_replace_callback('/(\<\?=|\<\?php=|\<\?php)(.*?)\?\>/', array(&$this, 'eval_block'), $string);
    }

    /**
     * @param mixed $matches
     * @return string
     */
    private function eval_block($matches)
    {
        if( is_array($this->variables) && count($this->variables) )
        {
            foreach($this->variables as $var_name => $var_value)
            {
                $$var_name = $var_value;
            }
        }

        $eval_end = '';

        if( $matches[1] == '<?=' || $matches[1] == '<?php=' )
        {
            if( $matches[2][count($matches[2]-1)] !== ';' )
            {
                $eval_end = ';';
            }
        }

        $return_block = '';

        eval('$return_block = ' . $matches[2] . $eval_end);

        return $return_block;
    }
}