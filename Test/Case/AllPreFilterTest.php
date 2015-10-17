<?php
/**
 * All app tests
 */
class AllPreFilterTest extends PHPUnit_Framework_TestSuite
{

    /**
     * Suite define the tests for this suite
     *
     * @return CakeTestSuite
     */
    public static function suite()
    {
        $suite = new CakeTestSuite('All app tests');
        $path =  dirname(__FILE__) . DS;
        $suite->addTestDirectoryRecursive($path);
        return $suite;
    }
}

