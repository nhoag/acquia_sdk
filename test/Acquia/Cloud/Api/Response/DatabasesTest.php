<?php

class Acquia_Test_Cloud_Api_Response_DatabasesTest extends PHPUnit_Framework_TestCase {

    protected $data_value;

    public function __construct() {
        $this->data_value = array(
            array('name' => 'data:zero'),
            array('name' => 'data:one'),
            array('name' => 'data:two')
        );
    }

    public function testResponsesConstructor()
    {
        $responses = new Acquia_Cloud_Api_Response_Databases($this->data_value);
        $iterator = $responses->getIterator();
        while($iterator->valid()) {
            $response = $iterator->current();
            $this->assertEquals($response['name'], $iterator->key());
            $this->assertEquals("{$response}", $iterator->key());
            $iterator->next();
        }
    }

}
