<?php
namespace CrudGenerator\Utils;

class PhpStringParserFactory
{
    /**
     * @return \CrudGenerator\Utils\PhpStringParser
     */
    public static function getInstance()
    {
        return new PhpStringParser(
            new \Twig_Environment(
                new \Twig_Loader_String()
            )
        );
    }
}
