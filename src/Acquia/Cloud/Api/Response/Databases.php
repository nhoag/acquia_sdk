<?php

/**
 * @file
 * AcquiaCloudApiClient Databases response
 *
 * NOTICE: This source code was derived from acquia-sdk-php (v0.3.3), covered
 * by the GPLv3 software license, on 2 Dec 2013.
 *
 * @see https://github.com/cpliakas/acquia-sdk-php/blob/0.3.3/src/Acquia/Cloud/Api/Response/Databases.php
 */

class AcquiaCloudApiResponseDatabases extends ArrayObject
{
    /**
     * @param array $dbs
     */
    public function __construct($dbs)
    {
        foreach ($dbs as $db) {
            $this[$db['name']] = new AcquiaCloudApiResponseDatabase($dbs);
        }
    }
}
