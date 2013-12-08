<?php


class Acquia_Test_Cloud_Api_CloudApiClientTest extends PHPUnit_Framework_TestCase
{
    /**
     * @return Acquia_Cloud_Api_CloudApiClient
     */
    public function getCloudApiClient()
    {
        return Acquia_Cloud_Api_CloudApiClient::factory(array(
                'base_url' => 'https://cloudapi.example.com',
                'username' => 'test-username',
                'password' => 'test-password',
            ));
    }

    /**
     * @expectedException RuntimeException
     */
    public function testRequireUsername()
    {
        Acquia_Cloud_Api_CloudApiClient::factory(array(
                'password' => 'test-password',
            ));
    }

    /**
     * @expectedException RuntimeException
     */
    public function testRequirePassword()
    {
        Acquia_Cloud_Api_CloudApiClient::factory(array(
                'username' => 'test-username',
            ));
    }

    public function testGetBuilderParams()
    {
        $expected = array (
            'base_url' => 'https://cloudapi.example.com',
            'username' => 'test-username',
            'password' => 'test-password',
        );

        $cloudapi = $this->getCloudApiClient();
        $this->assertEquals($expected, $cloudapi->getBuilderParams());
    }

    public function testGetBasePath()
    {
        $cloudapi = $this->getCloudApiClient();
        $this->assertEquals('/v1', $cloudapi->getConfig('base_path'));
    }

}
