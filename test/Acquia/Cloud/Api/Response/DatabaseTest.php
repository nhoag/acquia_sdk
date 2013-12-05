<?php

class Acquia_Test_Cloud_Api_Response_DatabaseTest extends PHPUnit_Framework_TestCase {

    protected $class_name = 'Acquia_Cloud_Api_Response_Database';
    protected $data_value = 'data_value';

    public function testResponseConstructorWithArray()
    {
        $data = array('name' => $this->data_value);
        $response = new $this->class_name($data);
        $this->assertEquals($response['name'], $this->data_value);
        $this->assertEquals("{$response}", $this->data_value);
    }

    public function testResponseConstructorWithString()
    {
        $response = new $this->class_name($this->data_value);
        $this->assertEquals($response['name'], $this->data_value);
        $this->assertEquals("{$response}", $this->data_value);
    }

}