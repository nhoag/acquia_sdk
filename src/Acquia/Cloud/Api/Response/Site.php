<?php

/**
 * @file
 * AcquiaCloudApiClient Site response
 *
 * NOTICE: This source code was derived from acquia-sdk-php (v0.3.3), covered
 * by the GPLv3 software license, on 2 Dec 2013.
 *
 * @see https://github.com/cpliakas/acquia-sdk-php/blob/0.3.3/src/Acquia/Cloud/Api/Response/Site.php
 */

class AcquiaCloudApiResponseSite extends ArrayObject
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
        list($this['hosting_stage'], $this['site_group']) = explode(':', $data['name']);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this['name'];
    }
}
