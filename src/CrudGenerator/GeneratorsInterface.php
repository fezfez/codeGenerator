<?php

namespace CrudGenerator\Generators;

use CrudGenerator\Generators\DataObject;
use CrudGenerator\Generators\FileManager;
use CrudGenerator\Generators\Hydrator;
use CrudGenerator\Generators\View\ZendView;

interface GeneratorsInterface
{
    public function __construct(
        ZendView $zendView,
        OutputInterface $output,
        FileManager $fileManager,
        Hydrator $hydrator
    );

    public function generate(DataObject $dataObject);
}