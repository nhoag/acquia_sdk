<?php

class Acquia_Test_Cloud_Api_Response_SiteTest extends PHPUnit_Framework_TestCase {

    protected $class_name = 'Acquia_Cloud_Api_Response_Site';
    protected $data_value = 'data_value';

    public function testResponseConstructorWithArray()
    {
        $hosting_stage = 'stage';
        $site_group = 'group';
        $this->data_value = "{$hosting_stage}:{$site_group}";
        $data = array('name' => $this->data_value);
        $response = new $this->class_name($data);
        $this->assertEquals($response['name'], $this->data_value);
        $this->assertEquals($response['hosting_stage'], $hosting_stage);
        $this->assertEquals($response['site_group'], $site_group);
        $this->assertEquals("{$response}", $this->data_value);
    }

    public function testResponseConstructorWithString()
    {
        $hosting_stage = 'stage';
        $site_group = 'group';
        $this->data_value = "{$hosting_stage}:{$site_group}";
        $response = new $this->class_name($this->data_value);
        $this->assertEquals($response['name'], $this->data_value);
        $this->assertEquals($response['hosting_stage'], $hosting_stage);
        $this->assertEquals($response['site_group'], $site_group);
        $this->assertEquals("{$response}", $this->data_value);
    }

}