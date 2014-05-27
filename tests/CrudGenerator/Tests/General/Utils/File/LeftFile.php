<?php

namespace MyNamespace;

class LeftFile
{
    /**
     * @var string
     */
    private $myVar = 'test';

    /**
     * @param string $value
     */
    public function setMyVar($value)
    {
        $this->myVar = $value;
    }
}
