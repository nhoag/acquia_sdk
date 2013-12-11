<?php


class Acquia_Test_Cloud_Api_CloudApiClientTest extends PHPUnit_Framework_TestCase
{
    /**
     * @param $methods array Methods to mock
     * @return Acquia_Cloud_Api_CloudApiClient
     */
    public function getCloudApiClient($methods = array('make_request'))
    {
        $cloudapi = $this->getMock('Acquia_Cloud_Api_CloudApiClient', $methods, array(
                'https://cloudapi.example.com',
                array(
                    'base_url' => 'https://cloudapi.example.com',
                    'base_path' => Acquia_Cloud_Api_CloudApiClient::BASE_PATH,
                    'username' => 'test-username',
                    'password' => 'test-password',
                )));

        $cloudapi->setDefaultHeaders(array(
                'Content-Type' => 'application/json; charset=utf-8',
                'User-Agent' => 'acquia_sdk/7.1.0 (jonathan.webb@acquia.com)'
                    . ' PHP/' . PHP_VERSION
            ));

        return $cloudapi;
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
            ->method('make_request')
            ->will($this->returnValue($response));
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

    public function getDatabaseData($name = "zero")
    {
        $instance_name = 'db' . rand();
        return array(
            "username" => "test-username",
            "password" => "test-password",
            "instance_name" => $instance_name,
            "name" => $name,
            "db_cluster" => "1234",
            "host" => 'server-1.myhostingstage.hosting.example.com'
        );
    }

    public function getBackupData($date = '1978-11-29') {
        return array(
            'link' => "http://mysitedev.myhostingstage.hosting.example.com/AH_DOWNLOAD?dev=123456789deadbeef&d=/mnt/files/dbname.dev/backups/dev-mysite-dbname-{$date}.sql.gz&t=1386777107",
            'deleted' => 0,
            'completed' => 1386657182,
            'path' => "backups/dev-mysite-dbname-{$date}.sql.gz&t=1386777107",
            'type' => 'daily',
            'checksum' => '123456789deadbeef',
            'name' => 'dbname',
            'id' => rand(10000,99999),
            'started' => 1386657182
        );
    }

    public function getServerData($type = 'web')
    {
        $number = rand(1000,9999);
        $server_name = "{$type}-{$number}";
        $server_ip = rand(1,254) . '.' . rand(1,254);

        $server_data = array(
            'services' => array(),
            'ec2_region' => 'aq-south-1',
            'ami_type' => 'c1.medium',
            'fqdn' => '{$server_name}.myhostingstage.hosting.example.com',
            'name'=> $server_name,
            'ec2_availability_zone' => 'aq-east-1z',
        );

        switch($type) {
            case 'bal':
                $server_data['services']['varnish'] = array(
                    'status' => 'active',
                );
                $server_data['services']['external_ip'] = "172.16.{$server_ip}";
                break;
            case 'web':
                $server_data['services']['web'] = array(
                    'php_max_procs' => '2',
                    'env_status' => 'active',
                    'status' => 'online',
                );
                break;
            case 'db':
                $server_data['services']['database'] = array();
                break;
            case 'free':
            case 'staging':
            case 'ded':
                $server_data['services']['web'] = array(
                    'php_max_procs' => '2',
                    'env_status' => 'active',
                    'status' => 'online',
                );
                $server_data['services']['database'] = array();
                break;
            case 'vcs':
                $server_data['services']['vcs'] = array (
                    'vcs_url' => 'mysite@vcs-1234.myhostingstage.hosting.example.com:mysite.git',
                    'vcs_type' => 'git',
                    'vcs_path' => 'master',
                );
                break;
        }

        return $server_data;
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

        $cloudapi = Acquia_Cloud_Api_CloudApiClient::factory($expected);
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

        $cloudapi = $this->getCloudApiClient();
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

        $cloudapi = $this->getCloudApiClient();
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

        $cloudapi = $this->getCloudApiClient();
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

        $cloudapi = $this->getCloudApiClient();
        $this->addMockResponse($cloudapi, $responseData);

        $env = $cloudapi->environment($siteName, 'dev');
        foreach($responseData as $key => $value) {
            $this->assertEquals($value, $env[$key]);
        }
    }

    public function testMockServersCall()
    {
        $siteName = 'myhostingstage:mysitegroup';
        $responseData = array (
            $this->getServerData('bal'),
            $this->getServerData('bal'),
            $this->getEnvironmentData('free'),
            $this->getEnvironmentData('vcs'),
        );

        $cloudapi = $this->getCloudApiClient();
        $this->addMockResponse($cloudapi, $responseData);

        $servers = $cloudapi->servers($siteName, 'dev');
        $this->assertTrue($servers instanceof Acquia_Cloud_Api_Response_Servers);
        $this->assertTrue($servers[$responseData[0]['name']] instanceof Acquia_Cloud_Api_Response_Server);
        $this->assertTrue($servers[$responseData[1]['name']] instanceof Acquia_Cloud_Api_Response_Server);
        $this->assertTrue($servers[$responseData[2]['name']] instanceof Acquia_Cloud_Api_Response_Server);
        $this->assertTrue($servers[$responseData[3]['name']] instanceof Acquia_Cloud_Api_Response_Server);
    }

    public function testMockServerCall()
    {
        $siteName = 'myhostingstage:mysitegroup';
        $responseData = $this->getServerData('free');

        $cloudapi = $this->getCloudApiClient();
        $this->addMockResponse($cloudapi, $responseData);

        $env = $cloudapi->server($siteName, 'dev', 'free');
        foreach($responseData as $key => $value) {
            $this->assertEquals($value, $env[$key]);
        }
    }

    public function testMockSiteDatabasesCall()
    {
        $siteName = 'myhostingstage:mysitegroup';
        $responseData = array (
            array('name' => 'one'),
            array('name' => 'two'),
        );

        $cloudapi = $this->getCloudApiClient();
        $this->addMockResponse($cloudapi, $responseData);

        $databases = $cloudapi->siteDatabases($siteName);
        $this->assertTrue($databases instanceof Acquia_Cloud_Api_Response_Databases);
        $this->assertTrue($databases['one'] instanceof Acquia_Cloud_Api_Response_Database);
        $this->assertTrue($databases['two'] instanceof Acquia_Cloud_Api_Response_Database);
    }

    public function testMockSiteDatabaseCall()
    {
        $siteName = 'myhostingstage:mysitegroup';
        $responseData = $this->getDatabaseData('one');

        $cloudapi = $this->getCloudApiClient();
        $this->addMockResponse($cloudapi, $responseData);

        $database = $cloudapi->siteDatabase($siteName, 'one');
        $this->assertTrue($database instanceof Acquia_Cloud_Api_Response_Database);
        foreach($responseData as $key => $value) {
            $this->assertEquals($value, $database[$key]);
        }
    }

    public function testMockEnvironmentDatabasesCall()
    {
        $siteName = 'myhostingstage:mysitegroup';
        $responseData = array (
            $this->getDatabaseData('one'),
            $this->getDatabaseData('two'),
        );

        $cloudapi = $this->getCloudApiClient();
        $this->addMockResponse($cloudapi, $responseData);

        $databases = $cloudapi->environmentDatabases($siteName, 'dev');
        $this->assertTrue($databases instanceof Acquia_Cloud_Api_Response_Databases);
        $this->assertTrue($databases['one'] instanceof Acquia_Cloud_Api_Response_Database);
        $this->assertTrue($databases['two'] instanceof Acquia_Cloud_Api_Response_Database);
    }

    public function testMockEnvironmentDatabaseCall()
    {
        $siteName = 'myhostingstage:mysitegroup';
        $responseData = $this->getDatabaseData('one');

        $cloudapi = $this->getCloudApiClient();
        $this->addMockResponse($cloudapi, $responseData);

        $database = $cloudapi->environmentDatabase($siteName, 'dev', 'one');
        $this->assertTrue($database instanceof Acquia_Cloud_Api_Response_Database);
        foreach($responseData as $key => $value) {
            $this->assertEquals($value, $database[$key]);
        }
    }

    public function testMockDatabaseBackupsCall()
    {
        $siteName = 'myhostingstage:mysitegroup';
        $responseData = array(
            $this->getBackupData('2013-12-11'),
            $this->getBackupData('2013-12-10'),
            $this->getBackupData('2013-12-09')
        );

        $cloudapi = $this->getCloudApiClient();
        $this->addMockResponse($cloudapi, $responseData);

        $database = $cloudapi->databaseBackups($siteName, 'dev', 'one');
        foreach($responseData as $key => $value) {
            $this->assertEquals($value, $database[$key]);
        }
    }

    public function testMockDatabaseBackupCall()
    {
        $siteName = 'myhostingstage:mysitegroup';
        $responseData = $this->getBackupData('2013-12-11');

        $cloudapi = $this->getCloudApiClient();
        $this->addMockResponse($cloudapi, $responseData);

        $database = $cloudapi->databaseBackups($siteName, 'dev', 'one');
        foreach($responseData as $key => $value) {
            $this->assertEquals($value, $database[$key]);
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

        $cloudapi = $this->getCloudApiClient();
        $this->addMockResponse($cloudapi, $responseData);
        $task = $cloudapi->installDistro($siteName, $environment, $type, $source);
        foreach($responseData as $key => $value) {
            $this->assertEquals($value, $task[$key]);
        }
    }

    public function testMockTaskInfoCall()
    {
        $siteName = 'myhostingstage:mysitegroup';
        $environment = 'dev';
        $type = 'distro_name';
        $source = 'acquia-drupal-7';
        $taskId = 12345;

        // Response is an Acquia Cloud Task
        $responseData = array(
            'recipient' => '',
            'created' => time(),
            // The values encoded in the body can come back in any order
            'body' => sprintf('{"env":"%s","site":"%s","type":"%s","source":"%s"}', $environment, $siteName, $type, $source),
            'id' => $taskId,
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

        $cloudapi = $this->getCloudApiClient();
        $this->addMockResponse($cloudapi, $responseData);
        $task = $cloudapi->taskInfo($siteName, $taskId);
        $this->assertEquals($taskId, $task['id']);
        foreach($responseData as $key => $value) {
            $this->assertEquals($value, $task[$key]);
        }
    }

}
