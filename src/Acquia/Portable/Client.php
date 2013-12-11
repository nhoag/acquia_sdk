<?php

class Acquia_Portable_Client {

    protected $base_url;
    protected $config;
    protected $headers;

    public function __construct($base_url, $config)
    {
        $this->base_url = $base_url;
        $this->config = $config;
    }


    final public function getConfig($key = false)
    {
        return $key ? $this->config[$key] : $this->config;
    }

    public function getDefaultHeaders()
    {
        return $this->defaultHeaders;
    }

    public function setDefaultHeaders($default_headers) {
        $this->headers = $default_headers;
    }

    protected function make_request($ch, $watchdog = '')
    {
        if (!$server_output = curl_exec($ch)) {
            throw new RuntimeException(curl_error($ch) . $watchdog);
        }
        return Acquia_Common_Json::decode($server_output);
    }

    /**
     * Helper function that makes the curl calls (GET).
     * @throws RuntimeException
     */
    protected function get($params)
    {
        $vars = $this->config;

        if (is_array($params[1])) {
            $vars = array_merge($vars, $params[1]);
        }

        $url = "{$this->base_url}{$params[0]}";
        while(preg_match('/([{]\+?(\w+)[}])/', $url, $matches)) {
            if (isset($vars[$matches[2]])) {
                $url = str_replace($matches[1], $vars[$matches[2]], $url);
            }
            else {
                throw new RuntimeException("Missing variable '{$matches[2]}' in API 'get' request.");
            }
        }
        $username = $this->config['username'];
        $password = $this->config['password'];
        $return_value = FALSE;
        if ($ch = curl_init($url)) {
            $headers = array();
            foreach($this->headers as $header => $value) {
                $headers[] = "{$header}: {$value}";
            }
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 150);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

            $return_value = $this->make_request($ch, " [Requesting the URL '{$url}' with user '{$username}'']");
            curl_close($ch);
        }
        else {
            throw new RuntimeException("Curl init failed in API 'get' request.");
        }
        return $return_value;
    }

    /**
     * Helper function that makes the curl calls (POST).
     * @throws RuntimeException
     */
    protected function post($params, $unused, $body)
    {
        $vars = $this->config;

        if (is_array($params[1])) {
            $vars = array_merge($vars, $params[1]);
        }

        $url = "{$this->base_url}{$params[0]}";
        while(preg_match('/([{]\+?(\w+)[}])/', $url, $matches)) {
            if (isset($vars[$matches[2]])) {
                $url = str_replace($matches[1], $vars[$matches[2]], $url);
            }
            else {
                throw new RuntimeException("Missing variable '{$matches[2]}' in API 'post' request.");
            }
        }
        $username = $this->config['username'];
        $password = $this->config['password'];
        $return_value = FALSE;
        if ($ch = curl_init($url)) {
            $headers = array();
            foreach($this->headers as $header => $value) {
                $headers[] = "{$header}: {$value}";
            }
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 150);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

            curl_setopt($ch, CURLOPT_POST, 0);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);

            $return_value = $this->make_request($ch, "  [Posting to the URL '{$url}' with user '{$username}'']");
            curl_close($ch);
        }
        else {
            throw new RuntimeException("Curl init failed in API 'post' request.");
        }
        return $return_value;
    }
}