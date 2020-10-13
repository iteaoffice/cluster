<?php

/**
 * ITEA Office all rights reserved
 *
 * PHP Version 7
 *
 * @category    Cluster
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2004-2019 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 *
 * @link        http://github.com/iteaoffice/cluster for the canonical source repository
 */

declare(strict_types=1);

namespace Cluster;

use BjyAuthorize\Guard\Route;
use Cluster\Acl\Assertion\ClusterAssertion;
use Cluster\Acl\Assertion\ReportAssertion;
use Project\Acl\Assertion\Project as ProjectAssertion;

return [
    'bjyauthorize' => [
        'guards' => [

        ],
    ],
];
