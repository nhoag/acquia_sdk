<?php

class Acquia_Test_Cloud_Api_Response_DatabaseTest extends PHPUnit_Framework_TestCase {

    public function testDatabaseResponseConstructorWithArray()
    {
        $data = array('name' => 'database_name');
        $response = new Acquia_Cloud_Api_Response_Database($data);
        $this->assertEquals($response['name'], 'database_name');
    }

    public function testDatabaseResponseConstructorWithString()
    {
        $data = 'database_name';
        $response = new Acquia_Cloud_Api_Response_Database($data);
        $this->assertEquals($response['name'], 'database_name');
    }

    public function testDatabaseResponseStringCast()
    {
        $data = 'database_name';
        $response = new Acquia_Cloud_Api_Response_Database($data);
        $this->assertEquals("{$response}", 'database_name');
    }
}