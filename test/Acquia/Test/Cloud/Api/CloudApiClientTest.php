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
     * @param Acquia_Cloud_Api_CloudApiClient $cloudapi
     * @param array $responseData
     */
    public function addMockPostResponse(Acquia_Cloud_Api_CloudApiClient $cloudapi, array $responseData)
    {
        $json = Acquia_Common_Json::encode($responseData);
        $response = Acquia_Common_Json::decode($json);
        $cloudapi->expects($this->any())
            ->method('post')
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

    public function getEnvironmentData($stage = 'dev')
    {
        return array(
            'livedev' => 'enabled',
            'db_clusters' => array(1234),
            'ssh_host' => 'server-1.myhostingstage.hosting.example.com',
            'name' => $stage,
            'vcs_path' => ($stage == 'dev') ? 'master' : 'tags/v1.0.1',
            'default_domain' => "mysitegroup{$stage}.myhostingstage.example.com",
        );
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

    public function testMockSiteCall()
    {
        $siteName = 'myhostingstage:mysitegroup';
        $responseData = array (
            'production_mode' => '1',
            'title' => 'My Site',
            'vcs_type' => 'git',
            'vcs_url' => 'mysitegroup@git.example.com:mysitegroup.git',
            'unix_username' => 'mysitegroup',
            'name' => $siteName,
        );

        $cloudapi = $this->getMockCloudApiClient(array('get'));
        $this->addMockResponse($cloudapi, $responseData);

        $site = $cloudapi->site($siteName);
        $this->assertEquals($site['hosting_stage'], 'myhostingstage');
        $this->assertEquals($site['site_group'], 'mysitegroup');
    }

    public function testMockEnvironmentsCall()
    {
        $siteName = 'myhostingstage:mysitegroup';
        $responseData = array (
            $this->getEnvironmentData('dev'),
            $this->getEnvironmentData('test'),
        );

        $cloudapi = $this->getMockCloudApiClient(array('get'));
        $this->addMockResponse($cloudapi, $responseData);

        $environments = $cloudapi->environments($siteName);
        $this->assertTrue($environments instanceof Acquia_Cloud_Api_Response_Environments);
        $this->assertTrue($environments['dev'] instanceof Acquia_Cloud_Api_Response_Environment);
        $this->assertTrue($environments['test'] instanceof Acquia_Cloud_Api_Response_Environment);
    }

    public function testMockEnvironmentCall()
    {
        $siteName = 'myhostingstage:mysitegroup';
        $responseData = $this->getEnvironmentData('dev');

        $cloudapi = $this->getMockCloudApiClient(array('get'));
        $this->addMockResponse($cloudapi, $responseData);

        $env = $cloudapi->environment($siteName, 'dev');
        foreach($responseData as $key => $value) {
            $this->assertEquals($value, $env[$key]);
        }
    }

    public function testMockInstallDistroByNameCall()
    {
        $siteName = 'myhostingstage:mysitegroup';
        $environment = 'dev';
        $type = 'distro_name';
        $source = 'acquia-drupal-7';

        // Response is an Acquia Cloud Task
        $responseData = array(
            'recipient' => '',
            'created' => time(),
            // The values encoded in the body can come back in any order
            'body' => sprintf('{"env":"%s","site":"%s","type":"%s","source":"%s"}', $environment, $siteName, $type, $source),
            'id' => 12345,
            'hidden' => 0,
            'result' => '',
            'queue' => 'site-install',
            'percentage' => '',
            'state' => 'waiting',
            'started' => '',
            'cookie' => '',
            'sender' => 'cloud_api',
            'description' => "Install {$source} to dev",
            'completed' => '',
        );

        $cloudapi = $this->getMockCloudApiClient(array('post'));
        $this->addMockPostResponse($cloudapi, $responseData);
        $task = $cloudapi->installDistro($siteName, $environment, $type, $source);
        foreach($responseData as $key => $value) {
            $this->assertEquals($value, $task[$key]);
        }
    }

}
