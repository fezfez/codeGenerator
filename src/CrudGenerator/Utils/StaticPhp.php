<?php
namespace CrudGenerator\Utils;

class StaticPhp
{
    /**
     * Do a sprintf with a plain text
     *
     * @param string $text          The text as this format "my text %s, $value"
     * @param array  $realVariables An array of variable used be the text array('value' => 'My value !')
     * @return string
     */
    public function staticsprintf($text, array $realVariables)
    {
        $questionRawExplode = explode(',', $text);
        $questionText       = array_shift($questionRawExplode);
        $questionVariables  = array_map('trim', $questionRawExplode);

        $placeholder = array();
        foreach ($questionVariables as $questionVariable) {
            $placeholder[] = $this->phpInterpretStatic(
                $questionVariable,
                $realVariables
            );
        }

        return vsprintf($questionText, $placeholder);
    }

    /**
     * Interpret a php plain text
     *
     * @param string $text               A php call as plain text example : $foo->bar()->baz()
     * @param array  $variableVariable   An array of variable used be the text array('foo' => $foo)
     * @throws \InvalidArgumentException
     * @return mixed
     */
    public function phpInterpretStatic($text, array $variableVariable)
    {
        $textExplode  = explode('->', $text);
        $variableName = array_shift($textExplode);

        if (isset($variableVariable[$variableName]) === false) {
            throw new \InvalidArgumentException(sprintf('var %s does not exist', $variableName));
        }

        $textExplode = array_map(
            function($value) {
                return str_replace('()', '', $value);
            },
            $textExplode
        );

        $instance = $variableVariable[$variableName];
        $lastKey  = end(array_keys($textExplode));

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
