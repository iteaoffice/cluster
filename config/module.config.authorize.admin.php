<?php

/**
 * ITEA Office all rights reserved
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2021 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 */

declare(strict_types=1);

namespace Cluster;

use BjyAuthorize\Guard\Route;

return [
    'bjyauthorize' => [
        // resource providers provide a list of resources that will be tracked
        // in the ACL. like roles, they can be hierarchical
        'guards' => [
            Route::class => [
                ['route' => 'zfcadmin/cluster/list', 'roles' => ['office'],],
                ['route' => 'zfcadmin/cluster/edit', 'roles' => ['office'],],
                ['route' => 'zfcadmin/cluster/view', 'roles' => ['office'],],
                ['route' => 'zfcadmin/cluster/new', 'roles' => ['office'],],
            ],
        ],
    ],
];
