<?php

/**
 * @file An Acquia AcquiaServiceManagerAware implementation
 *
 * NOTICE: This source code was derived from acquia-sdk-php (v0.3.3), covered
 * by the GPLv3 software license, on 2 Dec 2013.
 *
 * @see https://github.com/cpliakas/acquia-sdk-php/blob/0.3.3/LICENSE.txt
 */

interface Acquia_Common_AcquiaServiceManagerAware
{
    /**
     * Returns the parameters that can be used by the service manager to
     * instantiate the client.
     */
    public function getBuilderParams();
}
