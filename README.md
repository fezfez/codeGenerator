[![Build Status](https://travis-ci.org/fezfez/codeGenerator.png?branch=master)](https://travis-ci.org/fezfez/codeGenerator)
[![Code Coverage](https://scrutinizer-ci.com/g/fezfez/codeGenerator/badges/coverage.png?s=56a1921623a18b0405091624044c6d1e8a4452ac)](https://scrutinizer-ci.com/g/fezfez/codeGenerator/)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/fezfez/codeGenerator/badges/quality-score.png?s=fc3829220661fc6edf510608d909cf7b4776713c)](https://scrutinizer-ci.com/g/fezfez/codeGenerator/)

Code Generator
=============

What is "Code Generator" ?
------------------------

"Code generator" is tool who gonna help you to generate code.


Compatibility
------------
PHP : 5.4, 5.5, 5.6, HHVM 3.3

Database driver : 

 - PostgreSQL
 - MySQL
 - Oracle
 - Doctrine2 in Zend Framework 2 environment

How to use
----------
Refer to [How to use page][1] 
 
Installation
------------
Installation of CodeGenerator uses composer. For composer documentation, please refer to [getcomposer.org](http://getcomposer.org/).

```sh
composer require fezfez/code-generator
mkdir data/ && mkdir data/crudGenerator/ && mkdir data/crudGenerator/History/ && chmod 777 data/crudGenerator/History/ && mkdir data/crudGenerator/Config/ && chmod 777 data/crudGenerator/Config/
```


  [1]: https://github.com/fezfez/codeGenerator/blob/master/HOWTOUSE.md
