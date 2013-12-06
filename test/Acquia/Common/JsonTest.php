<?php

class Acquia_Test_Common_JsonTest extends PHPUnit_Framework_TestCase
{
    protected function getTestJson() {
        return '{"foo":{"bar":"bar foo","Food":{"bar":"X","Fool":{"Foolery":{"ID":"LAMA","SortOf":"LAMA","Foot":"Lorem Aliquam Morbi Aenean","Aenean":"LAMA","Aliquam":"ABC 1234:5678","Foodie":{"para":"Lorem ipsum dolor sit amet, consectetur adipiscing elit.","FoosBall":["ABC","123"]},"Footsie":"quisquam"}}}},"test":["\u003Cfoo\u003E","\u0027bar\u0027","\u0022baz\u0022","\u0026blong\u0026","\u00e9"]}';
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
       $this->assertEquals(Acquia_Common_Json::encode($this->getTestArray()),$this->getTestJson());
    }

    public function testJsonDecode()
    {
        $this->assertEquals(Acquia_Common_Json::decode($this->getTestJson()),$this->getTestArray());
    }
}

