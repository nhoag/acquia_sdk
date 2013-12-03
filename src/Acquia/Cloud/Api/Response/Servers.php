<?php

/**
 * @file
 * AcquiaCloudApiClient Servers response
 *
 * NOTICE: This source code was derived from acquia-sdk-php (v0.3.3), covered
 * by the GPLv3 software license, on 2 Dec 2013.
 *
 * @see https://github.com/cpliakas/acquia-sdk-php/blob/0.3.3/src/Acquia/Cloud/Api/Response/Servers.php
 */

class AcquiaCloudApiResponseServers extends ArrayObject
{
    /**
     * @param array $servers
     */
    public function __construct($servers)
    {
        foreach ($servers as $server) {
            $this[$server['name']] = new AcquiaCloudApiResponseServer($server);
        }
    }
}
