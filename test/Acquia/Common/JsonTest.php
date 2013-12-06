<?php

class Acquia_Test_Common_JsonTest extends PHPUnit_Framework_TestCase
{

    /**
     * Indents a flat JSON string to make it more human-readable.
     * JSON_PRETTY_PRINT option is not available until PHP 5.4
     *
     * @param string $json The original JSON string to process.
     *
     * @return string Indented version of the original JSON string.
     */
    protected function pretty_print($json) {

        $result = '';
        $pos = 0;
        $string_length = strlen($json);
        $indentation = '  ';
        $newline = "\n";
        $previous_char = '';
        $out_of_quotes = true;

        // If there are already newlines, assume formatted
        if (strpos($json, $newline)) {
            return;
        }

        for ($i=0; $i<=$string_length; $i++) {

            // Grab the next character in the string.
            $char = substr($json, $i, 1);

            // Are we inside a quoted string?
            if ($char == '"' && $previous_char != '\\') {
                $out_of_quotes = !$out_of_quotes;

                // If this character is the end of an element,
                // output a new line and indent the next line.
            } else if(($char == '}' || $char == ']') && $out_of_quotes) {
                $result .= $newline;
                $pos --;
                for ($j=0; $j<$pos; $j++) {
                    $result .= $indentation;
                }
            }

            // Add the character to the result string.
            $result .= $char;

            // If the last character was the beginning of an element,
            // output a new line and indent the next line.
            if (($char == ',' || $char == '{' || $char == '[') && $out_of_quotes) {
                $result .= $newline;
                if ($char == '{' || $char == '[') {
                    $pos ++;
                }

                for ($j = 0; $j < $pos; $j++) {
                    $result .= $indentation;
                }
            }

            $previous_char = $char;
        }

        return $result;
    }

    protected function getTestJson() {
        $test_file = dirname(__FILE__) . "/.test.json";
        return file_get_contents($test_file);
    }

    protected function getTestArray() {
        return array(
            "foo" => array(
                "bar" => "bar foo",
                "Food" => array(
                    "bar" => "X",
                    "Fool" => array(
                        "Foolery" => array(
                            "ID" => "LAMA",
                            "SortOf" => "LAMA",
                            "Foot" => "Lorem Aliquam Morbi Aenean",
                            "Aenean" => "LAMA",
                            "Aliquam" => "ABC 1234:5678",
                            "Foodie" => array(
                                "para" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
                                "FoosBall" => array(
                                    "ABC",
                                    "123"
                                ),
                            ),
                            "Footsie" => "quisquam"
                        )
                    )
                )
            ),
            'test' => array('<foo>',"'bar'",'"baz"','&blong&', "\xc3\xa9")
        );
    }

    public function testJsonEncode()
    {
       $this->assertEquals($this->pretty_print(Acquia_Common_Json::encode($this->getTestArray())),$this->getTestJson());
    }

    public function testJsonDecode()
    {
        $this->assertEquals(Acquia_Common_Json::decode($this->getTestJson()),$this->getTestArray());
    }
}

