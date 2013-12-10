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
     * @param Acquia_Cloud_Api_CloudApiClient $cloudapi
     * @param array $responseData
     */
    public function addMockResponse(Acquia_Cloud_Api_CloudApiClient $cloudapi, array $responseData)
    {
        $json = Acquia_Common_Json::encode($responseData);
        $response = Acquia_Common_Json::decode($json);
        $cloudapi->expects($this->any())
            ->method('get')
            ->will($this->returnValue($response));
    }

    /**
     * @return Acquia_Cloud_Api_CloudApiClient
     */
    public function getMockCloudApiClient($methods = null)
    {
        return $this->getMock('Acquia_Cloud_Api_CloudApiClient', $methods, array(
                'https://cloudapi.example.com',
                array(
                    'base_url' => 'https://cloudapi.example.com',
                    'username' => 'test-username',
                    'password' => 'test-password',
                )));
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

    public function testMockSitesCall()
    {
        $siteName = 'myhostingstage:mysitegroup';
        $responseData = array($siteName);
        $cloudapi = $this->getMockCloudApiClient(array('get'));
        $this->addMockResponse($cloudapi, $responseData);

        $sites = $cloudapi->sites();
        $this->assertTrue($sites instanceof Acquia_Cloud_Api_Response_Sites);
        $this->assertTrue($sites[$siteName] instanceof Acquia_Cloud_Api_Response_Site);
    }

}
