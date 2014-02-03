[![Build Status](https://travis-ci.org/fezfez/codeGenerator.png?branch=master)](https://travis-ci.org/fezfez/codeGenerator)
[![Coverage Status](https://coveralls.io/repos/fezfez/codeGenerator/badge.png?branch=master)](https://coveralls.io/r/fezfez/codeGenerator?branch=master)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/fezfez/codeGenerator/badges/quality-score.png?s=fc3829220661fc6edf510608d909cf7b4776713c)](https://scrutinizer-ci.com/g/fezfez/codeGenerator/)

This is alpha, certainly contains bugs, please dont use it on production

Running tests
-------------
    composer install --dev
    ./vendor/bin/phpunit

Roadmap
=======

Historic
-------
* Add in web

Metadata source
---------
* PDO
    * Dependencies between table
* Doctrine2
    * Dependencies between entities

FormGenerator
---------
* Choise form type (select, input etc... )
* Add Fk management
* Add support of  Symfony 2
* Add support of Phalcon PHP

ArchitectGenerator
--------
* Add FK management
* Add support of Phalcon PHP

CrudGenerator
--------
* Add support of  Symfony 2
* Add support of Phalcon PHP

Documentation
--------
* Create documentation "How to use"
* Create documentation "How to create a generator"