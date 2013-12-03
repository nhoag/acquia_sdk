<?php

/**
 * @file
 * Server response object for Acquia_Cloud_Api_CloudApiClient
 *
 * NOTICE: This source code was derived from acquia-sdk-php (v0.3.3), covered
 * by the GPLv3 software license, on 2 Dec 2013.
 *
 * @see https://github.com/cpliakas/acquia-sdk-php/blob/0.3.3/src/Acquia/Cloud/Api/Response/Server.php
 */

class Acquia_Cloud_Api_Response_Server extends ArrayObject
{
    /**
     * @param array|string $data
     */
    public function __construct($data)
    {
        if (is_string($data)) {
            $data = array('name' => $data);
        }
        parent::__construct($data);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this['name'];
    }
}
