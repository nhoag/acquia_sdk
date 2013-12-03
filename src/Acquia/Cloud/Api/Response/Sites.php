<?php

/**
 * @file
 * AcquiaCloudApiClient Sites response
 *
 * NOTICE: This source code was derived from acquia-sdk-php (v0.3.3), covered
 * by the GPLv3 software license, on 2 Dec 2013.
 *
 * @see https://github.com/cpliakas/acquia-sdk-php/blob/0.3.3/src/Acquia/Cloud/Api/Response/Sites.php
 */

class AcquiaCloudApiResponseSites extends ArrayObject
{
    /**
     * @param array $sites
     */
    public function __construct($sites)
    {
        foreach ($sites as $site) {
            $this[$site] = new AcquiaCloudApiResponseSite($site);
        }
    }
}
