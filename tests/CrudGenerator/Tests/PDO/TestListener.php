<?php
namespace CrudGenerator\Tests\PDO;

class TestListener implements \PHPUnit_Framework_TestListener
{
    protected static $dbh = null;

    /**
     * @param \PHPUnit_Framework_TestSuite $suite
     */
    public function startTestSuite(\PHPUnit_Framework_TestSuite $suite)
    {
        if (null === self::$dbh) {
            self::$dbh = new \PDO('sqlite::database:sqlite3');
            self::$dbh->exec(
                "CREATE TABLE messages (
                id INTEGER PRIMARY KEY,
                title VARCHAR(255),
                message TEXT,
                time TEXT)"
            );
            echo "\n\nCreate tmp database\n\n";
        }
    }

    /**
     * @param \PHPUnit_Framework_TestSuite $suite
     */
    public function endTestSuite(\PHPUnit_Framework_TestSuite $suite)
    {
    }

    public function addError(\PHPUnit_Framework_Test $test, \Exception $e, $time)
    {
    }

    public function addFailure(\PHPUnit_Framework_Test $test, \PHPUnit_Framework_AssertionFailedError $e, $time)
    {
    }

    public function addIncompleteTest(\PHPUnit_Framework_Test $test, \Exception $e, $time)
    {
    }

    public function addSkippedTest(\PHPUnit_Framework_Test $test, \Exception $e, $time)
    {
    }

    public function startTest(\PHPUnit_Framework_Test $test)
    {
    }

    public function endTest(\PHPUnit_Framework_Test $test, $time)
    {
    }

    public function __destruct()
    {
        self::$dbh = null;
        unlink(':database:sqlite2');
        echo "\n\nDelete tmp database\n\n";
    }
}
