<?php

class Acquia_Test_Cloud_Api_Response_SitesTest extends PHPUnit_Framework_TestCase {

    protected $data_value;

    public function __construct() {
        $this->data_value = array(
            'data:zero',
            'data:one',
            'data:two'
        );
    }

    public function testSitesResponseConstructor()
    {
        $responses = new Acquia_Cloud_Api_Response_Sites($this->data_value);
        $iterator = $responses->getIterator();
        while($iterator->valid()) {
            $response = $iterator->current();
            $this->assertEquals($response['name'], $iterator->key());
            $this->assertEquals("{$response}", $iterator->key());
            $iterator->next();
        }
    }

}
