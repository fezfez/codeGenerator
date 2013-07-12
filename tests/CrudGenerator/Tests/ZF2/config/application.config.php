<?php
return array(
    'modules' => array(
        'DoctrineModule',
        'DoctrineORMModule',
        'TestZf2'
    ),
    'module_listener_options' => array(
        'module_paths' => array(
            './../../../../../vendor',
            './../module',
        ),
        'config_glob_paths' => array(
            'config/autoload/{,*.}{global,local}.php',
        ),
    )
);