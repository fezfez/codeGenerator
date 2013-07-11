<?php
namespace CrudGenerator\Tests\ZF2;

class TestListener implements \PHPUnit_Framework_TestListener
{
    protected static $dbh = null;

    /**
     * @param \PHPUnit_Framework_TestSuite $suite
     */
    public function startTestSuite(\PHPUnit_Framework_TestSuite $suite)
    {
        echo "Add zf2 dependency\n\n";
        if(null === self::$dbh) {
            exec('composer require zendframework/zendframework:2.1.*');
            exec('composer require doctrine/doctrine-orm-module:0.*');
            self::$dbh = true;
        }
    }

    /**
     * @param \PHPUnit_Framework_TestSuite $suite
     */
    public function endTestSuite(\PHPUnit_Framework_TestSuite $suite) { }

    public function addError(\PHPUnit_Framework_Test $test, \Exception $e, $time) { }

    public function addFailure(\PHPUnit_Framework_Test $test, \PHPUnit_Framework_AssertionFailedError $e, $time) { }

    public function addIncompleteTest(\PHPUnit_Framework_Test $test, \Exception $e, $time) { }

    public function addSkippedTest(\PHPUnit_Framework_Test $test, \Exception $e, $time) { }

    public function startTest(\PHPUnit_Framework_Test $test) { }

    public function endTest(\PHPUnit_Framework_Test $test, $time) { }

    public function __destruct()
    {
        self::$dbh = NULL;
    }
}