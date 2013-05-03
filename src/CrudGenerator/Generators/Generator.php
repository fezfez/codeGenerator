<?php


namespace CrudGenerator\Generators;

use CrudGenerator\Generators\DataObject;

interface Generator
{
    function doGenerate(DataObject $dataObject);
}