<?php


namespace CrudGenerator\Generators;

use CrudGenerator\DataObject;

interface Generator
{
    function doGenerate(DataObject $dataObject);
}