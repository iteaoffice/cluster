<?php

/**
 * ITEA Office all rights reserved
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2021 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 */

declare(strict_types=1);

namespace Cluster\Options;

use Laminas\Stdlib\AbstractOptions;

/**
 * Class ModuleOptions
 * @package Cluster\Options
 */
class ModuleOptions extends AbstractOptions
{
    private string $reportingPortalApiUrl = 'https://tool.eureka-clusters-ai.eu';
    private string $bearerToken = 'abcd';

    public function getReportingPortalApiUrl(): string
    {
        return $this->reportingPortalApiUrl;
    }

    public function setReportingPortalApiUrl(string $reportingPortalApiUrl): ModuleOptions
    {
        $this->reportingPortalApiUrl = $reportingPortalApiUrl;
        return $this;
    }

    public function getBearerToken(): string
    {
        return $this->bearerToken;
    }

    public function setBearerToken(string $bearerToken): ModuleOptions
    {
        $this->bearerToken = $bearerToken;
        return $this;
    }
}
