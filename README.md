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

Template
--------
* Fk management in DAO, DTO, Hydrator, From
* Choise form type (select, input etc... )
* Symfony 2
* Phalcon PHP

Generator
---------
* Dependencies between entities, table
* Environment manager (more flexible for unit tests)
* Clear unit test
