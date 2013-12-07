<?php

class Acquia_Test_Common_JsonTest extends PHPUnit_Framework_TestCase
{
    protected function getTestJson() {
        $test_file = dirname(__FILE__) . "/.JsonTestArray.json";
        return file_get_contents($test_file);
    }

    protected function getTestArray() {
        $test_file = dirname(__FILE__) . "/.JsonTestArray.php";
        return eval('return ' . file_get_contents($test_file) . ';');
    }

    public function testJsonEncode()
    {
        $expected = $this->getTestJson();
        echo "\n\nEXPECTED:\n";
        print_r($expected);

        $actual = Acquia_Common_Json::encode($this->getTestArray());
        echo "\n\nACTUAL:\n";
        print_r($actual);
        echo "\n\n";

        $this->assertEquals($expected, $actual);

        $this->assertEquals($this->getTestJson(), Acquia_Common_Json::encode($this->getTestArray()));
    }

    public function testJsonDecode()
    {
         $this->assertEquals($this->getTestArray(), Acquia_Common_Json::decode($this->getTestJson()));
    }
}
