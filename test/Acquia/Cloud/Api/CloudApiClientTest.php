<?php


class Acquia_Test_Cloud_Api_CloudApiClientTest extends PHPUnit_Framework_TestCase
{

    /**
     * @expectedException RuntimeException
     */
    public function testRequireUsername()
    {
        Acquia_Cloud_Api_CloudApiClient::factory(array(
                'password' => 'test-password',
            ));
    }

}
