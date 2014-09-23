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
}
