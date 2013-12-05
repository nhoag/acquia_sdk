<?php

class Acquia_Test_Cloud_Api_Response_ServerTest extends PHPUnit_Framework_TestCase {

    private $class_name = 'Acquia_Cloud_Api_Response_Server';
    private $data_value = 'data_value';

    public function testResponseConstructorWithArray()
    {
        $data = array('name' => $this->data_value);
        $response = new $this->class_name($data);
        $this->assertEquals($response['name'], $this->data_value);
        $this->assertEquals("{$response}", $this->data_value);
    }

    public function testResponseConstructorWithString()
    {
        $data = $this->data_value;
        $response = new $this->class_name($data);
        $this->assertEquals($response['name'], $this->data_value);
        $this->assertEquals("{$response}", $this->data_value);
    }

}