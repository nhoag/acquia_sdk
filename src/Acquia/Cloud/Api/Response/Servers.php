<?php

/**
 * @file
 * Servers response object for Acquia_Cloud_Api_CloudApiClient
 *
 * NOTICE: This source code was derived from acquia-sdk-php (v0.3.3), covered
 * by the GPLv3 software license, on 2 Dec 2013.
 *
 * @see https://github.com/cpliakas/acquia-sdk-php/blob/0.3.3/src/Acquia/Cloud/Api/Response/Servers.php
 */

class Acquia_Cloud_Api_Response_Servers extends ArrayObject
{
    /**
     * @param array $servers
     */
    public function __construct($servers)
    {
        foreach ($servers as $server) {
            $this[$server['name']] = new Acquia_Cloud_Api_Response_Server($server);
        }
    }
}
