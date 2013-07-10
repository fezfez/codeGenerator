<?php
namespace CrudGenerator\Tests\Diff\DiffPHP;

use CrudGenerator\Diff\DiffPHP;

class DiffTest extends \PHPUnit_Framework_TestCase
{
    public function testReturn()
    {
        $filePath = __DIR__ . '/../File/';

        $SUT = new DiffPHP();
        $results = $SUT->diff(
            $filePath . 'LeftFile.php',
            $filePath . 'RightFile.php'
        );

        $this->assertContains(
            '-    private $myVar = \'test\';
+    private $myVar = true;',
            $results
        );
    }

    public function testFailOnReadLeft()
    {
        $filePath = __DIR__ . '/../File/';

        $SUT = new DiffPHP();

        $this->setExpectedException('Exception');

        $results = $SUT->diff(
            $filePath . 'LefrtFile.php',
            $filePath . 'RightFile.php'
        );
    }

    public function testFailOnReadRight()
    {
        $filePath = __DIR__ . '/../File/';

        $SUT = new DiffPHP();

        $this->setExpectedException('Exception');

        $results = $SUT->diff(
            $filePath . 'LeftFile.php',
            $filePath . 'RighttFile.php'
        );
    }
}

