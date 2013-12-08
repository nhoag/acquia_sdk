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

        // Find max number of possible flag combinations
        $combinations = pow(2, $flag_count);

        // Iterate through each flag combination
        for ($i = 0; $i < $combinations; $i++) {
            $test_flags = 0;
            $test_strings = array();
            $expected_strings = array();

            // for each flag combination test if the flag key is set or not
            for ($flag_key = 0; $flag_key < $flag_count; $flag_key++) {
                $test_strings[] = $test_pattern[$flag_list[$flag_key]]['test'];

                // if the flag_key is set
                //     store its flag value and store its expected transformation
                // else store its the untransformed test pattern
                if (($i & pow(2,$flag_key)) > 0) {
                    $test_flags |= $flag_list[$flag_key];
                    $expected_strings[] = $test_pattern[$flag_list[$flag_key]]['expected'];
                }
                else {
                    $expected_strings[] = $test_pattern[$flag_list[$flag_key]]['test'];
                }

            }

            // We don't need proper JSON for testing, so just send ':' delimited strings
            $test_string = implode(':', $test_strings);
            $expected_string = implode(':', $expected_strings);
            $this->assertEquals($expected_string, Acquia_Common_Json::encode_optional($test_string, $test_flags));
        }
    }

}
