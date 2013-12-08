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
        $this->assertEquals($this->getTestJson(), Acquia_Common_Json::encode($this->getTestArray()));
    }

    public function testJsonDecode()
    {
         $this->assertEquals($this->getTestArray(), Acquia_Common_Json::decode($this->getTestJson()));
    }

    public function testJsonEncodeOptional()
    {
        $test_pattern = array(
            Acquia_Common_Json::HEX_TAG => array(
                'test' => '"<foo>"',
                'expected' => '"\u003Cfoo\u003E"'
            ),
            Acquia_Common_Json::HEX_AMP => array(
                'test' => '"&blong&"',
                'expected' => '"\u0026blong\u0026"'
            ),
            Acquia_Common_Json::HEX_APOS => array(
                'test' => '"\'bar\'"',
                'expected' => '"\u0027bar\u0027"'
            ),
            Acquia_Common_Json::HEX_QUOT => array(
                'test' => '"\"baz\""',
                'expected' => '"\u0022baz\u0022"'
            ),
        );

        $flag_list = array_keys($test_pattern);
        $flag_count = count($flag_list);
        $combinations = pow(2, $flag_count);
        for ($i = 0; $i < $combinations; $i++) {
            $flag = 0;
            $test_strings = array();
            $expected_strings = array();
            for ($j = 0; $j < $flag_count; $j++) {
                $test_strings[] = $test_pattern[$flag_list[$j]]['test'];
                if (($i & pow(2,$j)) > 0) {
                    $flag |= $flag_list[$j];
                    $expected_strings[] = $test_pattern[$flag_list[$j]]['expected'];
                }
                else {
                    $expected_strings[] = $test_pattern[$flag_list[$j]]['test'];
                }

            }
            $test_string = implode(':', $test_strings);
            $expected_string = implode(':', $expected_strings);
            $this->assertEquals($expected_string, Acquia_Common_Json::encode_optional($test_string, $flag));
        }
    }

}
