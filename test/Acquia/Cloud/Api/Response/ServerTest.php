<?php

class Acquia_Test_Cloud_Api_Response_ServerTest extends PHPUnit_Framework_TestCase {

    protected $data_value = 'data_value';

    public function testServerResponseConstructorWithArray()
    {
        $data = array('name' => $this->data_value);
        $response = new Acquia_Cloud_Api_Response_Server($data);
        $this->assertEquals($response['name'], $this->data_value);
        $this->assertEquals("{$response}", $this->data_value);
    }

    public function testServerResponseConstructorWithString()
    {
        $response = new Acquia_Cloud_Api_Response_Server($this->data_value);
        $this->assertEquals($response['name'], $this->data_value);
        $this->assertEquals("{$response}", $this->data_value);
    }

}
