<?php

class Acquia_Test_Cloud_Api_Response_DatabaseTest extends PHPUnit_Framework_TestCase {

    protected $data_value = 'data_value';

    public function testDatabaseResponseConstructorWithArray()
    {
        $data = array('name' => $this->data_value);
        $response = new Acquia_Cloud_Api_Response_Database($data);
        $this->assertEquals($response['name'], $this->data_value);
        $this->assertEquals("{$response}", $this->data_value);
    }

    public function testDatabaseResponseConstructorWithString()
    {
        $response = new Acquia_Cloud_Api_Response_Database($this->data_value);
        $this->assertEquals($response['name'], $this->data_value);
        $this->assertEquals("{$response}", $this->data_value);
    }

}
