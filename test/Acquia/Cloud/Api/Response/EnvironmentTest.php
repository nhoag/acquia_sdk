<?php

class Acquia_Test_Cloud_Api_Response_EnvironmentTest extends PHPUnit_Framework_TestCase {

    protected $data_value = 'data_value';

    public function testEnvironmentResponseConstructorWithArray()
    {
        $data = array('name' => $this->data_value);
        $response = new Acquia_Cloud_Api_Response_Environment($data);
        $this->assertEquals($response['name'], $this->data_value);
        $this->assertEquals("{$response}", $this->data_value);
    }

    public function testEnvironmentResponseConstructorWithString()
    {
        $response = new Acquia_Cloud_Api_Response_Environment($this->data_value);
        $this->assertEquals($response['name'], $this->data_value);
        $this->assertEquals("{$response}", $this->data_value);
    }

}
