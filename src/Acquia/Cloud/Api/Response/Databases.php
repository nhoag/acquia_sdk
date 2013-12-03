<?php

/**
 * @file
 * Databases response object for Acquia_Cloud_Api_CloudApiClient
 *
 * NOTICE: This source code was derived from acquia-sdk-php (v0.3.3), covered
 * by the GPLv3 software license, on 2 Dec 2013.
 *
 * @see https://github.com/cpliakas/acquia-sdk-php/blob/0.3.3/src/Acquia/Cloud/Api/Response/Databases.php
 */

class Acquia_Cloud_Api_Response_Databases extends ArrayObject
{
    /**
     * @param array $dbs
     */
    public function __construct($dbs)
    {
        foreach ($dbs as $db) {
            $this[$db['name']] = new Acquia_Cloud_Api_Response_Database($dbs);
        }
    }
}
