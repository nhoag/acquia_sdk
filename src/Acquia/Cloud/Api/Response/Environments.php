<?php

/**
 * @file
 * AcquiaCloudApiClient Environments response
 *
 * NOTICE: This source code was derived from acquia-sdk-php (v0.3.3), covered
 * by the GPLv3 software license, on 2 Dec 2013.
 *
 * @see https://github.com/cpliakas/acquia-sdk-php/blob/0.3.3/src/Acquia/Cloud/Api/Response/Environments.php
 */

class AcquiaCloudApiResponseEnvironments extends ArrayObject
{
    /**
     * @param array $envs
     */
    public function __construct($envs)
    {
        foreach ($envs as $env) {
            $this[$env['name']] = new AcquiaCloudApiResponseEnvironment($env);
        }
    }
}
